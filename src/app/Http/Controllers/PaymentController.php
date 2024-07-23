<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Customer;
use App\Models\CreditCard;


class PaymentController extends Controller
{
    public function showCreditRegistrationForm()
    {
        return view('credit');
    }

    public function saveCreditCard(Request $request)
    {
        $paymentMethodId = $request->input('payment_method_id');

        if (!$paymentMethodId) {
            return redirect()->back()->with('error', '支払い方法の情報が不足しています。');
        }

        Stripe::setApiKey(env('STRIPE_SECRET'));

        $user = auth()->user();
        $creditCard = $user->creditCard()->first();
        $customerId = $creditCard ? $creditCard->customer_id : null;

        try {
            $paymentMethod = \Stripe\PaymentMethod::retrieve($paymentMethodId);

            if (!$customerId) {
                $customer = \Stripe\Customer::create([
                    'email' => $user->email,
                ]);
                $customerId = $customer->id;

                $paymentMethod->attach(['customer' => $customerId]);

                \Stripe\Customer::update($customerId, [
                    'invoice_settings' => ['default_payment_method' => $paymentMethodId],
                ]);

                $creditCard = new CreditCard();
                $creditCard->user_id = $user->id;
                $creditCard->customer_id = $customerId;
                $creditCard->payment_method_id = $paymentMethodId;
                $creditCard->save();
            } else {
                $paymentMethod->attach(['customer' => $customerId]);

                \Stripe\Customer::update($customerId, [
                    'invoice_settings' => ['default_payment_method' => $paymentMethodId],
                ]);

                $creditCard->payment_method_id = $paymentMethodId;
                $creditCard->save();
            }

            $orderData = session('order_data');
            if ($orderData) {
                $orderData['payment_method_id'] = $paymentMethodId;
                $orderData['payment_method'] = 'credit_card';
                session(['order_data' => $orderData]);
            }

            return redirect()->route('order.details');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'クレジットカードの登録に失敗しました: ' . $e->getMessage());
        }
    }
}
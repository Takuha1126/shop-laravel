<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Customer;
use App\Models\Product;
use App\Models\CreditCard;
use App\Models\Order;
use Stripe\PaymentIntent;
use Stripe\PaymentMethod;
use Stripe\Exception\ApiErrorException;
use Stripe\Checkout\Session;


class PaymentController extends Controller
{
    public function showRegistrationForm()
    {
        return view('credit');
    }

    public function saveCreditCard(Request $request)
{
    if (!$request->stripeToken) {
        return redirect()->back()->with('error', 'クレジットカード情報の取得に失敗しました。');
    }

    Stripe::setApiKey(env('STRIPE_SECRET'));


        $customer = Customer::create([
            'email' => auth()->user()->email,
            'source' => $request->stripeToken,
            'name' => $request->card_holder_name,
        ]);

        $defaultSource = null;
        if ($customer->default_source) {
            $defaultSource = \Stripe\Customer::retrieveSource(
                $customer->id,
                $customer->default_source
            );
        }

        if (!$defaultSource) {
            return redirect()->back()->with('error', 'クレジットカード情報の保存に失敗しました。');
        }

        $creditCard = new CreditCard();
        $creditCard->user_id = auth()->user()->id;
        $creditCard->customer_id = $customer->id;
        $creditCard->last_four = substr($defaultSource->last4, -4);
        $creditCard->brand = $defaultSource->brand;
        $creditCard->save();

        $orderData = session('order_data');
        $order = null;

        if (!$order) {
            $order = new Order();
            $order->user_id = auth()->user()->id;
            $order->product_id = $orderData['product_id'];
        }

        $order->payment_method = 'credit_card';
        $order->save();

        $orderData['payment_method'] = 'credit_card';
        session(['order_data' => $orderData]);

        return redirect()->route('order.details')->with('status', 'クレジットカード情報が登録されました。');

}


}
<?php

namespace App\Http\Controllers;

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
        $token = $request->input('stripeToken');

        Stripe::setApiKey(env('STRIPE_SECRET'));

        $customer = Customer::create([
            'email' => auth()->user()->email,
            'source' => $token,
            'name' => $request->card_holder_name,
        ]);

        $creditCard = new CreditCard();
        $creditCard->user_id = auth()->user()->id;
        $creditCard->customer_id = $customer->id;
        $creditCard->save();

        $orderData = session('order_data');
        if ($orderData) {
            $orderData['payment_method'] = 'credit_card';
            session(['order_data' => $orderData]);
        }

        return redirect()->route('order.details');
    }
}
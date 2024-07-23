<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Stripe\Stripe;
use App\Models\Product;
use App\Models\Order;
use App\Models\Category;
use App\Http\Requests\SubmitOrderRequest;
use Stripe\Charge;
use Illuminate\Support\Facades\Session;

class OrderController extends Controller
{
    public function showOrderDetails()
    {
        $user = Auth::user();
        $categories = Category::all();
        $orderData = session('order_data');


        $productId = $orderData['product_id'];
        $product = Product::findOrFail($productId);

        $order = new \stdClass();
        if (isset($orderData['order_id'])) {
            $order->id = $orderData['order_id'];
            $order->product_id = $productId;
            $order->payment_method = $orderData['payment_method'] ?? null;
        }

        return view('purchase', compact('orderData', 'product', 'order', 'categories','user'));
    }

    public function storeTemporaryOrder(Request $request)
    {
        $userId = Auth::id();
        $productId = $request->input('product_id');

        $orderId = Str::uuid()->toString();

        $orderData = [
            'user_id' => $userId,
            'product_id' => $productId,
            'order_id' => $orderId,
        ];

        session(['order_data' => $orderData]);

        return redirect()->route('order.details');
    }

    public function submitOrder(SubmitOrderRequest  $request)
    {
        try {
            $paymentMethod = $request->input('payment_method');
            $paymentMethodId = $request->input('payment_method_id');
            $orderData = session('order_data');
            $productId = $orderData['product_id'];
            $product = Product::findOrFail($productId);

            if ($request->payment_method === 'credit_card') {
                $creditCard = auth()->user()->creditCard;

                if (!$creditCard) {
                    return redirect()->back()->with('error', 'クレジットカード情報が登録されていません。');
                }
            }

            if ($product->user_id == auth()->id()) {
                return redirect()->back()->with('error', '自身が出品した商品は購入できません。');
            }

            $profile = auth()->user()->profile;
            if ($profile->postal_code === '000-0000' || $profile->address === 'デフォルト住所' || $profile->building_name === 'デフォルト建物名') {
                return redirect()->back()->with('error', '購入する前に住所を設定してください。');
            }

            $orderData = session('order_data');
            if (!$orderData) {
                throw new \Exception('注文データがセッションに見つかりません。');
            }

            $product = Product::findOrFail($orderData['product_id']);
            $amount = $product->price;

            $order = new Order();
            $order->user_id = auth()->user()->id;
            $order->product_id = $orderData['product_id'];
            $order->payment_method = $paymentMethod;
            $order->amount = $amount;
            $order->save();

            $product->purchase();

            Stripe::setApiKey(env('STRIPE_SECRET'));

            if ($paymentMethod === 'credit_card') {
                $paymentIntent = \Stripe\PaymentIntent::create([
                    'amount' => $amount,
                    'currency' => 'jpy',
                    'payment_method' => $paymentMethodId,
                    'customer' => $creditCard->customer_id,
                    'confirmation_method' => 'manual',
                    'confirm' => true,
                    'return_url' => route('purchase.success', ['orderId' => $order->id]),
                ]);

                if ($paymentIntent->status === 'succeeded') {
                    session()->forget('order_data');

                    return redirect()->route('purchase.success', ['orderId' => $order->id])->with('status', '支払いが完了しました。');
                } else {
                    throw new \Exception('支払いに失敗しました。');
                }
            } else {
                return redirect()->route('purchase.success', ['orderId' => $order->id])->with('status', '注文が完了しました。');
            }

        } catch (\Stripe\Exception\CardException $e) {
            return redirect()->back()->with('error', '支払いに失敗しました。: ' . $e->getMessage());
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'エラーが発生しました。: ' . $e->getMessage());
        }
    }


    public function success($orderId)
    {
        $order = Order::find($orderId);

        return view('success', compact('order'));
    }

    public function edit()
    {
        $orderData = session('order_data');
        $productId = $orderData['product_id'];

        $order = new \stdClass();
        $order->product_id = $productId;

        $order->payment_method = isset($orderData['payment_method']) ? $orderData['payment_method'] : null;

        return view('payment', compact('order'));
    }



    public function update(Request $request)
    {
        $orderData = session('order_data');

        $orderData['payment_method'] = $request->input('payment_method');
        Session::put('order_data', $orderData);

        $orderId = $orderData['order_id'];

        $order = new \stdClass();
        $order->id = $orderId;


        $order->payment_method = $orderData['payment_method'];

        return redirect()->route('order.details', ['id' => $orderData['product_id']]);
    }
}

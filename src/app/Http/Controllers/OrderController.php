<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Charge;
use Stripe\PaymentIntent;
use App\Models\Product;
use App\Models\Order;
use App\Models\Category;
use App\Models\Favorite;
use App\Http\Requests\SubmitOrderRequest;

class OrderController extends Controller
{
    public function showOrderDetails()
    {
        $user = Auth::user();
        $categories = Category::all();
        $orderData = session('order_data');

        if (!$orderData) {
            return redirect()->back()->with('error', '注文情報がありません。');
        }

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

        if (!$userId || !$productId) {
            return redirect()->back()->with('error', '不正なデータです。');
        }

        $orderId = Str::uuid()->toString();

        $orderData = [
            'user_id' => $userId,
            'product_id' => $productId,
            'order_id' => $orderId,
        ];

        session(['order_data' => $orderData]);

        return redirect()->route('order.details');
    }

    public function submitOrder(SubmitOrderRequest $request)
{
    try {
        $orderData = session('order_data');
        if (!$orderData) {
            throw new \Exception('注文データがセッションにありません。');
        }

        $product = Product::findOrFail($orderData['product_id']);
        $amount = $product->price;

        // 支払い方法に応じて処理を分岐
        switch ($request->payment_method) {
            case 'credit_card':
                $creditCard = auth()->user()->creditCard;

                if (!$creditCard) {
                    return redirect()->back()->with('error', 'クレジットカード情報が登録されていません。');
                }

                // プロファイル情報のチェック（適宜修正してください）
                $profile = auth()->user()->profile;
                if ($profile->postal_code === '000-0000' || $profile->address === 'デフォルト住所' || $profile->building_name === 'デフォルト建物名') {
                    return redirect()->back()->with('error', '購入する前に住所を設定してください。');
                }

                Stripe::setApiKey(env('STRIPE_SECRET'));
                $charge = Charge::create([
                    'amount' => $amount * 100, // Stripeは通貨を最小単位で扱うので、通貨に合わせて調整する必要があります
                    'currency' => 'jpy',
                    'description' => 'Order ' . $orderData['order_id'],
                    'customer' => $creditCard->customer_id,
                ]);

                break;

            case 'convenience_store':
                Stripe::setApiKey(env('STRIPE_SECRET'));
                $paymentIntent = PaymentIntent::create([
                    'amount' => $amount * 100, // Stripeでは通貨を最小単位で扱います
                    'currency' => 'jpy',
                    'payment_method_types' => ['konbini'],
                    'capture_method' => 'automatic', // 手動で支払いを確定させる設定
                ]);

                break;

            case 'bank_transfer':
                Stripe::setApiKey(env('STRIPE_SECRET'));
                $paymentIntent = PaymentIntent::create([
                    'amount' => $amount * 100, // Stripeでは通貨を最小単位で扱います
                    'currency' => 'jpy',
                    'payment_method_types' => ['bank_transfer'],
                    'capture_method' => 'manual', // 手動で支払いを確定させる設定
                ]);

                break;

            default:
                throw new \Exception('支払い方法が正しくありません。');
        }

        // 注文を保存する
        $order = new Order();
        $order->user_id = auth()->user()->id;
        $order->product_id = $orderData['product_id'];
        $order->payment_method = $request->payment_method;
        $order->amount = $amount;
        $order->payment_intent_id = isset($paymentIntent) ? $paymentIntent->id : null;
        $order->save();

        // 注文データをセッションに保存
        session(['order_data' => $orderData]);

        // 成功したら詳細ページにリダイレクト
        return redirect()->route('purchase.success', ['orderId' => $order->id])->with('status', '注文が成功しました。');

    } catch (\Stripe\Exception\CardException $e) {
        return redirect()->back()->with('error', 'クレジットカードの処理中にエラーが発生しました。: ' . $e->getMessage());
    } catch (\Exception $e) {
        return redirect()->back()->with('error', $e->getMessage());
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
        $order->payment_method = $orderData['payment_method'] ?? null;

        return view('payment', compact('order'));
    }

    public function update(Request $request)
    {
        $orderData = session('order_data');

        try {
            $orderData['payment_method'] = $request->input('payment_method');
            Session::put('order_data', $orderData);

            return redirect()->route('order.details', ['id' => $orderData['product_id']])->with('success', '支払い方法が更新されました。');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', '支払い方法の更新に失敗しました。');
        }
    }
}

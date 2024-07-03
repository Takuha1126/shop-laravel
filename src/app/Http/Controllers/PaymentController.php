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
        return view('payment_methods.credit');
    }

    public function saveCreditCard(Request $request)
{
    if (!$request->stripeToken) {
        \Log::error('Stripe token is missing.');
        return redirect()->back()->with('error', 'クレジットカード情報の取得に失敗しました。');
    }

    Stripe::setApiKey(env('STRIPE_SECRET'));

    try {
        \Log::info('Creating Stripe customer.');
        $customer = Customer::create([
            'email' => auth()->user()->email,
            'source' => $request->stripeToken,
            'name' => $request->card_holder_name,
        ]);

        \Log::info('Stripe customer created: ' . json_encode($customer));

        // 顧客のデフォルトソース（カード情報）を取得
        $defaultSource = null;
        if ($customer->default_source) {
            $defaultSource = \Stripe\Customer::retrieveSource(
                $customer->id,
                $customer->default_source
            );
        }

        if (!$defaultSource) {
            \Log::error('Error: default source not found.');
            return redirect()->back()->with('error', 'クレジットカード情報の保存に失敗しました。');
        }

        \Log::info('Default source retrieved: ' . json_encode($defaultSource));

        \Log::info('Saving credit card to database.');
        $creditCard = new CreditCard();
        $creditCard->user_id = auth()->user()->id;
        $creditCard->customer_id = $customer->id;
        $creditCard->last_four = substr($defaultSource->last4, -4);
        $creditCard->brand = $defaultSource->brand;
        $creditCard->save();

        // セッションから注文情報を取得
        $orderData = session('order_data');
        $order = null;

        if ($orderData && isset($orderData['order_id'])) {
            $order = Order::find($orderData['order_id']);
        }

        if (!$order) {
            // 注文情報が存在しない場合、新しい注文を作成
            $order = new Order();
            $order->user_id = auth()->user()->id;
            $order->product_id = $orderData['product_id'];
        }

        // 支払い方法を設定して注文を保存
        $order->payment_method = 'credit_card';
        $order->save();

        \Log::info('Credit card saved successfully.');

        // セッションの注文情報を更新
        $orderData['payment_method'] = 'credit_card'; // セッションに支払い方法を追加または更新
        session(['order_data' => $orderData]);

        return redirect()->route('order.details')->with('status', 'クレジットカード情報が登録されました。');
    } catch (\Exception $e) {
        \Log::error('Error saving credit card: ' . $e->getMessage());
        return redirect()->back()->with('error', $e->getMessage());
    }
}


    public function showPreparationForm()
    {
        return view('payment_methods.convenience_store');
    }
    
    public function processConvenienceStore(Request $request)
{
    try {
        // セッションから注文データを取得
        $orderData = session('order_data');
        if (!$orderData) {
            return redirect()->back()->with('error', '注文データがセッションに見つかりません。');
        }

        // 選択されたコンビニ情報をセッションに保存
        $convenienceStore = $request->input('convenience_store');
        $orderData['convenience_store'] = $convenienceStore;
        session(['order_data' => $orderData]);

        // デバッグ用に注文データをログに出力
        \Log::info('注文データ:', ['order_data' => $orderData]);

        // 商品の価格を取得
        $product = Product::findOrFail($orderData['product_id']);
        $amount = $product->price;

        // 注文情報をデータベースに保存
        $order = new Order();
        $order->user_id = auth()->user()->id;
        $order->product_id = $orderData['product_id'];
        $order->payment_method = 'convenience_store'; // コンビニ払いの場合の決まり
        $order->amount = $amount;
        $order->save();

        // PaymentIntentを作成して支払いを準備
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $paymentIntent = PaymentIntent::create([
            'amount' => $amount, // Stripeでは通貨を最小単位で扱います
            'currency' => 'jpy',
            'payment_method_types' => ['konbini'],
            'capture_method' => 'automatic', // 手動で支払いを確定させる設定
            'payment_method_options' => [
                'konbini' => [
                    'expires_after_days' => 3,
                    'product_description' => 'Order ' . substr($orderData['order_id'], 0, 16),
                ]
            ]
        ]);

        // デバッグ用にPaymentIntentをログに出力
        \Log::info('PaymentIntent:', ['payment_intent' => $paymentIntent]);

        // Orderモデルにpayment_intent_idを保存
        $order->payment_intent_id = $paymentIntent->id;
        $order->save();

        // セッションに支払い方法を保存
        $orderData['payment_method'] = 'convenience_store';
        session(['order_data' => $orderData]);

        // 成功した場合、詳細ページにリダイレクトする
        return redirect()->route('order.details')->with('status', 'コンビニ払いの準備が完了しました。');
    } catch (\Exception $e) {
        // エラーが発生した場合はエラーメッセージをログに記録
        \Log::error('コンビニ払い処理中にエラーが発生しました:', ['message' => $e->getMessage()]);

        // エラーメッセージをフラッシュしてフォームにリダイレクト
        return redirect()->back()->with('error', 'コンビニ払いの準備中にエラーが発生しました。');
    }
}

    public function showBankTransferForm()
    {
        return view('payment_methods.bank_transfer');
    }

public function processBankTransfer(Request $request)
{
    try {
        // セッションから注文データを取得
        $orderData = session('order_data');

        // 注文情報がセッションに存在しない場合のエラーハンドリング
        if (!$orderData || !isset($orderData['order_id'])) {
            throw new \Exception('注文情報がセッションに見つかりませんでした。');
        }

        // 商品情報を取得
        $product = Product::findOrFail($orderData['product_id']);
        $amount = $product->price;

        // Stripe APIキーをセット
        Stripe::setApiKey(env('STRIPE_SECRET'));

        // Stripe Checkoutセッションを作成
        $session = Session::create([
            'payment_method_types' => ['us_bank_account'],
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'jpy',
                        'unit_amount' => $amount, // Stripeでは通貨を最小単位で扱います
                        'product_data' => [
                            'name' => '注文ID ' . $orderData['order_id'],
                        ],
                    ],
                    'quantity' => 1,
                ],
            ],
            'mode' => 'payment',
            'success_url' => route('order.details'),
        ]);

        // セッションIDをログに出力
        \Log::info('Stripe Checkout Session ID:', ['session_id' => $session->id]);

        // セッションIDをセッションに保存
        session(['stripe_checkout_session_id' => $session->id]);

        // 成功した場合、StripeのCheckoutページにリダイレクト
        return redirect($session->url);
    } catch (\Exception $e) {
        \Log::error('銀行振込みの処理中にエラーが発生しました。エラーメッセージ: ' . $e->getMessage());
        return redirect()->back()->with('error', '銀行振込みの準備中にエラーが発生しました。エラーメッセージ: ' . $e->getMessage());
    }
}
}
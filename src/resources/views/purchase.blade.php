@extends('layouts.add')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}" />
@endsection

@section('content')
    <div class="main">
        <div class="main__group">
            <div class="main__ttl">
                <div class="main__item">
                    @if (App::environment('local'))
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->productName }}">
                    @else
                        <img src="{{ Storage::disk('s3')->url($product->image) }}" alt="{{ $product->productName }}">
                    @endif
                    <div class="item__ttl">
                        <p class="item__title">{{ $product->productName }}</p>
                        <p class="item__price">¥{{ $product->price }}</p>
                    </div>
                </div>
                <div class="main__change">
                    <div class="change__payment">
                        <p class="payment__title">支払い方法</p>
                        <a class="payment__button" href="{{ route('payment.edit', ['order' => $orderData['product_id']]) }}">変更する</a>
                    </div>
                    <div class="change__place">
                        <p class="place__title">配送先</p>
                        <a class="place__button" href="{{ route('user.change')}}">変更する</a>
                    </div>
                </div>
            </div>
            <div class="main__about">
                <div class="main__logo">
                    <div class="about__price">
                        <p class="about__title">商品の代金</p>
                        <p class="about__price">¥{{ $product->price }}</p>
                    </div>
                    <div class="about__total">
                        <p class="total__title">支払い金額</p>
                        <p class="total__price">¥{{ $product->price }}</p>
                    </div>
                    <div class="about__method">
                        <p class="method__title">支払い方法</p>
                        <p class="method__description">
                            @if ($order && isset($order->payment_method))
                                @if ($order->payment_method === 'credit_card')
                                    クレジットカード払い
                                @elseif ($order->payment_method === 'convenience_store')
                                    コンビニ払い
                                @elseif ($order->payment_method === 'bank_transfer')
                                    銀行振込
                                @else
                                    {{ $order->payment_method }}
                                @endif
                            @else
                                支払い情報を設定してください
                            @endif
                        </p>
                    </div>
                </div>
                @if(session('error'))
                    <p class="error">{{ session('error') }}</p>
                @endif
                @error('payment_method')
                    <p class="error">{{ $message }}</p>
                @enderror
                <div class="button">
                    <form id="payment-form" action="{{ route('order.submit') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="payment_method" value="{{ $orderData['payment_method'] ?? '' }}">
                        <input type="hidden" name="address" value="{{ $user->profile }}">
                        <button type="submit" class="order__button">購入する</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
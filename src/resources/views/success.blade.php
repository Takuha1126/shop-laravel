@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/success.css') }}" />
@endsection

@section('content')
<div class="main">
    <div class="main__group">
        <p class="group__title">購入完了</p>
        <div class="group__item">
            @if ($order)
                <p class="group__item-about">商品の名前: {{ $order->product->productName }}</p>
                <p class="group__item-about">支払い方法:
                    @if ($order->payment_method === 'credit_card')
                        クレジットカード払い
                    @elseif ($order->payment_method === 'convenience_store')
                        コンビニ払い
                    @elseif ($order->payment_method === 'bank_transfer')
                        銀行振込
                    @else
                        {{ $order->payment_method }}
                    @endif
                </p>
            @else
                <p>注文情報が見つかりません。</p>
            @endif
        </div>
        <div class="group__link">
            <a href="{{ route('home.index') }}">ホームへ戻る</a>
        </div>
    </div>
</div>
@endsection

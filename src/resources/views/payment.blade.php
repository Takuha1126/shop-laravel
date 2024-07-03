@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/payment.css') }}">
@endsection

@section('content')
<div class="main">
    <p class="main__title">支払い方法の選択・変更</p>
    <form action="{{ route('payment.update', ['order' => $order->product_id]) }}" method="POST" id="payment-selection-form">
        @csrf
        @method('PUT')
        <div class="main__group">
            <label for="payment_method" class="label">支払い方法を選択してください:</label>
            <select name="payment_method" id="payment_method" class="main__item">
                <option value="credit_card" {{ $order->payment_method == 'credit_card' ? 'selected' : '' }}>クレジットカード</option>
                <option value="convenience_store" {{ $order->payment_method == 'convenience_store' ? 'selected' : '' }}>コンビニ</option>
                <option value="bank_transfer" {{ $order->payment_method == 'bank_transfer' ? 'selected' : '' }}>銀行振込</option>
            </select>
        </div>
        <button type="submit" id="update-payment-method" class="btn-primary">支払い方法を更新する</button>
    </form>
</div>

<script>
    function handlePaymentUpdate(event) {
        event.preventDefault();

        var paymentMethod = document.getElementById('payment_method').value;
        
        switch (paymentMethod) {
            case 'credit_card':
                window.location.href = '{{ route('credit.show') }}';
                break;
            case 'convenience_store':
                window.location.href = '{{ route('convenience_store.show') }}';
                break;
            case 'bank_transfer':
                window.location.href = '{{ route('bank_transfer.show') }}';
                break;
            default:
                document.getElementById('payment-selection-form').submit();
                break;
        }
    }

    document.getElementById('update-payment-method').addEventListener('click', handlePaymentUpdate);
</script>
@endsection

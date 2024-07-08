@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/credit.css') }}" />
@endsection

@section('content')
<div class="main">
    <p class="main__title">クレジットカード情報の登録</p>
    <form id="credit-card-form" action="{{ route('credit.save') }}" method="POST">
        @csrf
        <div class="main__group">
            <label for="card_number" class="label">カード番号</label>
            <div id="card-number" class="main__item"></div>
        </div>
        <div class="main__group">
            <label for="expiry_date" class="label">有効期限 (MM/YY)</label>
            <div id="card-expiry" class="main__item"></div>
        </div>
        <div class="main__group">
            <label for="cvc" class="label">CVC</label>
            <div id="card-cvc" class="main__item"></div>
        </div>
        <div class="main__group">
            <label for="card_holder_name" class="label">名義</label>
            <input type="text" name="card_holder_name" id="card-holder-name" class="main__item" required>
        </div>
        <div class="button">
            <button type="submit" class="btn-primary">登録する</button>
        </div>
    </form>
</div>

<script src="https://js.stripe.com/v3/"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var stripe = Stripe('{{ env('STRIPE_KEY') }}');
        var elements = stripe.elements();

        var style = {
            base: {
                fontSize: '16px',
                color: '#32325d',
                fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                '::placeholder': {
                    color: '#aab7c4'
                }
            }
        };

        var cardNumber = elements.create('cardNumber', { style: style });
        cardNumber.mount('#card-number');

        var cardExpiry = elements.create('cardExpiry', { style: style });
        cardExpiry.mount('#card-expiry');

        var cardCvc = elements.create('cardCvc', { style: style });
        cardCvc.mount('#card-cvc');

        var form = document.getElementById('credit-card-form');
        var submitButton = form.querySelector('button[type="submit"]');

        form.addEventListener('submit', function (event) {
            event.preventDefault();
            submitButton.disabled = true;

            stripe.createToken(cardNumber).then(function (result) {
                if (result.error) {
                    var errorElement = document.getElementById('card-errors');
                    errorElement.textContent = result.error.message;
                    submitButton.disabled = false;
                } else {
                    var hiddenInput = document.createElement('input');
                    hiddenInput.setAttribute('type', 'hidden');
                    hiddenInput.setAttribute('name', 'stripeToken');
                    hiddenInput.setAttribute('value', result.token.id);
                    form.appendChild(hiddenInput);

                    form.submit();
                }
            });
        });
    });
</script>
@endsection

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
                <label for="card-number" class="label">カード番号</label>
                <div id="card-number" class="main__item"></div>
            </div>
            <div class="main__group">
                <label for="card-expiry" class="label">有効期限 (MM/YY)</label>
                <div id="card-expiry" class="main__item"></div>
            </div>
            <div class="main__group">
                <label for="card-cvc" class="label">CVC</label>
                <div id="card-cvc" class="main__item"></div>
            </div>
            <div class="main__group">
                <label for="card-holder-name" class="label">名義</label>
                <input type="text" name="card_holder_name" id="card-holder-name" class="main__item">
                <p id="card-holder-name-error" class="error"></p>
                <p id="card-number-error" class="error"></p>
            </div>
            <div class="button">
                <button type="submit" class="btn-primary" id="submit-btn">登録する</button>
            </div>
        </form>
    </div>
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var stripeKey = '{{ env('STRIPE_KEY') }}';

            var stripe = Stripe(stripeKey);
            var elements = stripe.elements();

            var cardNumber = elements.create('cardNumber');
            cardNumber.mount('#card-number');

            var cardExpiry = elements.create('cardExpiry');
            cardExpiry.mount('#card-expiry');

            var cardCvc = elements.create('cardCvc');
            cardCvc.mount('#card-cvc');

            var form = document.getElementById('credit-card-form');
            var submitButton = form.querySelector('#submit-btn');
            var cardHolderNameInput = form.querySelector('#card-holder-name');
            var cardNumberError = form.querySelector('#card-number-error');
            var cardHolderNameError = form.querySelector('#card-holder-name-error');

            function clearErrorMessages() {
                cardNumberError.textContent = '';
                cardHolderNameError.textContent = '';
            }

            form.addEventListener('submit', function (event) {
                event.preventDefault();
                clearErrorMessages();

                var cardHolderName = cardHolderNameInput.value.trim();
                if (cardHolderName === '') {
                    cardHolderNameError.textContent = '名義を入力してください。';
                    return;
                }

                stripe.createPaymentMethod({
                    type: 'card',
                    card: cardNumber,
                    billing_details: { name: cardHolderName }
                }).then(function(result) {
                    if (result.error) {
                        alert('支払い方法の作成に失敗しました。エラー詳細: ' + result.error.message);
                    } else {
                        var hiddenInput = document.createElement('input');
                        hiddenInput.setAttribute('type', 'hidden');
                        hiddenInput.setAttribute('name', 'payment_method_id');
                        hiddenInput.setAttribute('value', result.paymentMethod.id);
                        form.appendChild(hiddenInput);

                        form.submit();
                    }
                });
            });
        });
    </script>
@endsection
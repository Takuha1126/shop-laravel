@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/credit.css') }}" />
@endsection

@section('content')
<div class="main">
    <p class="main__title">銀行振り込みの準備</p>
    <form action="{{ route('bank-transfer.process') }}" method="POST" id="bank-transfer-form">
        @csrf
        <div class="main__group">
            <label for="account_holder_name" class="label">口座名義人名</label>
            <input type="text" id="account_holder_name" name="account_holder_name" class="main__item" required>
        </div>

        <div class="main__group">
            <label for="account_number" class="label">口座番号</label>
            <input type="text" id="account_number" name="account_number" class="main__item" required>
        </div>

        <div class="main__group">
            <label for="routing_number" class="label">支店コード</label>
            <input type="text" id="routing_number" name="routing_number" class="main__item" required>
        </div>

        <button type="submit" class="btn-primary">準備する</button>
    </form>
</div>
@endsection
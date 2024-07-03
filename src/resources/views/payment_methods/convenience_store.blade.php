@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/credit.css') }}" />
@endsection

@section('content')
<div class="main">
    <p class="main__title">コンビニ払いの準備</p>
    <form action="{{ route('convenience-store.process') }}" method="POST" id="convenience-store-form">
        @csrf
        <div class="main__group">
            <label for="convenience_store">コンビニを選択してください:</label>
            <select name="convenience_store" id="convenience_store">
                <option value="seicomart">セイコーマート</option>
                <option value="lawson">ローソン</option>
                <option value="familymart">ファミリーマート</option>
                <option value="seven_eleven">セブンイレブン</option>
            </select>
        </div>
        <button type="submit" class="btn-primary">準備する</button>
    </form>
</div>
@endsection
@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/register.css') }}" />
@endsection

@section('content')
<div class="main__ttl">
    <div class="main__item">
        <p class="main__title">管理者の会員登録</p>
    </div>
    <div class="main__about">
        <form action="{{ route('admin.register') }}"  method="POST">
            @csrf
            <div class="main__name">
                <label class="label">名前</label>
                <input type="text" name="name">
                @error('name')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="main__email">
                <label class="label">メールアドレス</label>
                <input type="email" name="email">
                @error('email')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="main__password">
                <label class="label">パスワード</label>
                <input type="password" name="password">
                @error('password')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="button">
                <button type="submit" class="button__ttl">登録する</button>
            </div>
        </form>
    </div>
    <div class="main__link">
        <a class="link__title" href="{{ route('admin.login') }}">ログインはこちら</a>
    </div>
</div>
@endsection
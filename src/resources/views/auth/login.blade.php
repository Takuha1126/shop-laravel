@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/login.css') }}" />
@endsection

@section('content')
    <div class="main__ttl">
        <div class="main__item">
            <p class="main__title">ログイン</p>
        </div>
        <div class="main__about">
            <form action="{{ route('login') }}"  method="POST">
                @csrf
                <div class="main__email">
                    <label class="label">メールアドレス</label>
                    <input type="email" name="email">
                    @error('email')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>
                <div class="main__password">
                    <label class="label">パスワード</label>
                    <input type="password" name="password">
                    @error('password')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>
                <div class="button">
                    <button type="submit" class="button__ttl">ログインする</button>
                </div>
            </form>
        </div>
        <div class="main__link">
            <a class="link__title" href="{{route('register')}}">会員登録はこちら</a>
        </div>
    </div>
@endsection
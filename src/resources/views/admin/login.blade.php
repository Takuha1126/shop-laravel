@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/login.css') }}" />
@endsection

@section('content')
    <div class="main__ttl">
        <div class="main__item">
            <p class="main__title">管理者のログイン</p>
        </div>
        <div class="main__about">
            <form action="{{ route('admin.login') }}"  method="POST">
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
                    <button class="button__ttl">ログインする</button>
                </div>
            </form>
        </div>
        <div class="main__link">
            <a class="link__title" href="{{route('admin.register')}}">会員登録はこちら</a>
        </div>
    </div>
@endsection
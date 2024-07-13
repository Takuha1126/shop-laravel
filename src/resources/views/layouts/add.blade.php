<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/layouts/add.css') }}" />
    @yield('css')
</head>
<body>
    <header class="header">
    <div class="header__group">
        <div class="header__ttl">
            <p class="header__title">
                @if (App::environment('local'))
                    <img src="{{ asset('storage/images/logo.svg') }}">
                @else
                    <img src="https://s3-ap-northeast-1.amazonaws.com/shop-laravel/images/logo.svg" alt="Logo">
                @endif
            </p>
        </div>
        <nav class="nav">
            <div class="nav__item">
                <form id="search-form" action="{{ route('products.search') }}" method="post">
                    @csrf
                    <select id="category_select" name="category_name">
                        <option value="">何をお探しですか？</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->name }}">{{ $category->name }}</option>
                            @endforeach
                    </select>
                </form>
            </div>
            <div class="nav__item">
                <form action="{{ route('logout') }}" method="post">
                    @csrf
                    <button type="submit" class="button__logout">ログアウト</button>
                </form>
            </div>
            <div class="nav__item">
                <a class="button__ttl" href="{{ route('user.show') }}">マイページ</a>
            </div>
            <div class="nav__item">
                <a href="{{ route('product.index') }}" class="button__title">出品</a>
            </div>
        </nav>
    </div>
</header>
<main>
    @yield('content')
</main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var selectInput = document.getElementById('category_select');

        selectInput.addEventListener('change', function() {
            document.getElementById('search-form').submit();
        });
    });
</script>
</body>
</html>

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
                    <input type="hidden" name="source" value="category_search">
                    <input type="text" id="category_name_input" name="category_name" list="category_list" placeholder="なにをお探しですか？">
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
        var searchInput = document.getElementById('category_name_input');
        var categoryList = [
            @foreach($categories as $category)
                "{{ $category->name }}",
            @endforeach
        ];

        searchInput.addEventListener('input', function() {
            var value = this.value.trim().toLowerCase();
            var autocompleteList = document.createElement('div');
            autocompleteList.setAttribute('id', 'autocomplete-list');
            autocompleteList.setAttribute('class', 'autocomplete-items');

            categoryList.forEach(function(category) {
                if (category.toLowerCase().indexOf(value) > -1) {
                    var option = document.createElement('div');
                    option.textContent = category;
                    option.addEventListener('click', function() {
                        searchInput.value = category;
                        autocompleteList.innerHTML = '';
                    });
                    autocompleteList.appendChild(option);
                }
            });

            var searchForm = document.getElementById('search-form');
            searchForm.appendChild(autocompleteList);
        });

        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                document.getElementById('search-form').submit();
            }
        });
    });
</script>
</body>
</html>

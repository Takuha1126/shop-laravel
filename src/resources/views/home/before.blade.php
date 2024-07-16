<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>検索機能</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/home/before.css') }}">
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
                    <input type="text" name="keyword" id="search-input" placeholder="なにをお探しですか？">
                    <div id="search-results"></div>
                    <div id="login-required-message" class="login-required-message">
                        検索を行うにはログインが必要です。ログインしてください。
                        <a href="{{ route('login') }}" class="btn btn-primary">ログイン</a>
                    </div>
                </div>
                <div class="nav__item">
                    <a href="{{ route('login') }}" class="button__ttl">ログイン</a>
                </div>
                <div class="nav__item">
                    <a href="{{ route('register') }}" class="button__ttl">会員登録</a>
                </div>
                <div class="nav__item">
                    <a class="button__title" href="{{ route('login') }}">出品</a>
                </div>
            </nav>
        </div>
    </header>
    <main class="main">
        <div class="main__ttl">
            <div class="main__border">
                <div class="main__title">
                    <a class="main__button">おすすめ</a>
                    <a class="main__button--my" href="{{ route('login') }}">マイリスト</a>
                </div>
            </div>
            <div class="main__item">
                <div class="item__ttl">
                    @if ($products->isEmpty())
                        <p>現在、おすすめ商品はありません。</p>
                    @else
                        @foreach($products as $product)
                            <a class="item__button" href="{{ route('item.detail', ['id' => $product->id]) }}">
                                <img src="{{ Storage::url($product->image) }}" alt="{{ $product->productName }}">
                            </a>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </main>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            var searchUrl = '{{ route('products.search') }}';
            $('#search-input').on('input', function() {
                var keyword = $(this).val();
                if (keyword.length >= 1) {
                    $.ajax({
                        url: searchUrl,
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            _token: '{{ csrf_token() }}',
                            keyword: keyword
                        },
                        success: function(response) {
                            var results = '';
                            $.each(response, function(index, product) {
                                var productLink = '/item/' + product.id;
                                results += '<a class="item__button" href="' + productLink + '">';
                                results += '<img src="/storage/' + product.image + '" alt="' + product.productName + '">';
                                results += '</a>';
                            });
                            $('#search-results').html(results);
                            hideLoginRequiredMessage();
                        },
                        error: function(xhr) {
                            console.log(xhr.responseText);
                            showLoginRequiredMessage();
                        }
                    });
                } else {
                    $('#search-results').empty();
                    hideLoginRequiredMessage();
                }
            });

            function showLoginRequiredMessage() {
                $('#login-required-message').fadeIn();
            }

            function hideLoginRequiredMessage() {
                $('#login-required-message').fadeOut();
            }
        });
    </script>
</body>
</html>

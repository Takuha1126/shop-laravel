<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/detail/before.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header class="header">
        <div class="header__group">
            <div class="header__ttl">
                <p class="header__title"><img src="https://s3-ap-northeast-1.amazonaws.com/shop-laravel/images/logo.svg" alt="Logo"></p>
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
            <div class="main__item">
                <img src="{{ Storage::url($product->image) }}" alt="{{ $product->productName }}">
            </div>
            <div class="main__about">
                <div class="about__group">
                    <div class="about__ttl">
                        <p class="about__title">{{ $product->productName }}</p>
                        <p class="about__brand">{{ $product->brand }}</p>
                    </div>
                    <div class="about__price">
                        <p class="price__ttl">¥{{ $product->price }}(値段)</p>
                    </div>
                    <div class="about__action">
                        <div class="action__favorite">
                            <a class="comment__link" href="{{ route('login') }}">
                                <i class="far fa-star"></i>
                                <p class="star__number">{{ $product->favorites->count() }}</p>
                            </a>
                        </div>
                        <div class="action__comment">
                            <a class="comment__link" href="{{ route('login') }}">
                                <i class="far fa-comment"></i>
                                <p class="comment__number">{{ $product->comments->count() }}</p>
                            </a>
                        </div>
                    </div>
                    <div class="about__button">
                        <a class="button__purchase" href="{{ route('login') }}">購入する</a>
                    </div>
                </div>
                <div class="about__description">
                    <div class="about__ttl">
                        <p class="about__title">商品の説明</p>
                    </div>
                    <div class="about__detail">
                        <p class="detail__title">{!! nl2br(e($product->description)) !!}</p>
                    </div>
                </div>
                <div class="about__information">
                    <div class="about__ttl">
                        <p class="about__title">商品の情報</p>
                    </div>
                    <div class="about__category">
                        <p class="category__title">カテゴリー</p>
                        <div class="category__item">
                            @foreach ($product->categories as $category)
                                <span class="category__type">{{ $category->name }}</span>
                            @endforeach
                        </div>
                    </div>
                    <div class="about__status">
                        <p class="status__title">商品の状態</p>
                        <p class="status__type"> {{ $product->status }}</p>
                    </div>
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

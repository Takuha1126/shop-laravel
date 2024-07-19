@extends('layouts.add')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/detail/after.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
@endsection

@section('content')
    <div class="main">
        <div class="main__ttl">
            <div class="main__item">
                @if (App::environment('local'))
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->productName }}">
                @else
                    <img src="{{ Storage::disk('s3')->url($product->image) }}" alt="{{ $product->productName }}">
                @endif
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
                            <i class="favorite-icon {{ $isFavorite ? 'fas text-black' : 'far' }} fa-star" data-product-id="{{ $product->id }}"></i>
                            <p id="favorite-count" class="star__number">{{ $product->favorites->count() }}</p>
                        </div>
                        <div class="action__comment">
                            <a class="comment__link" href="{{ route('comment.index', ['id' => $product->id]) }}">
                                <i class="far fa-comment"></i>
                                <p class="comment__number">{{ $product->comments->count() }}</p>
                            </a>
                        </div>
                    </div>
                    <div class="about__button">
                        @if ($soldOut)
                            <p class="about__sold">SOLD OUT</p>
                        @else
                            <form action="{{ route('order.storeTemporary') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <button type="submit" class="button__purchase">購入する</button>
                            </form>
                        @endif
                    </div>
                </div>
                <div class="about__description">
                    <div class="description__ttl">
                        <p class="description__title">商品の説明</p>
                    </div>
                    <div class="about__detail">
                        <p class="detail__title">{!! nl2br(e($product->description)) !!}</p>
                    </div>
                </div>
                <div class="about__information">
                    <div class="information__ttl">
                        <p class="information__title">商品の情報</p>
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
                        <p class="status__type">{{ $product->status }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.main__about').on('click', '.favorite-icon', function(event) {
                event.preventDefault();

                var iconElement = $(this);
                var productId = iconElement.data('product-id');
                var favoriteCountElement = iconElement.siblings('.star__number');
                var isFavorite = iconElement.hasClass('fas text-black');
                var favoriteCount = parseInt(favoriteCountElement.text());

                isFavorite = !isFavorite;
                if (isFavorite) {
                    iconElement.addClass('fas text-black').removeClass('far');
                    favoriteCount++;
                } else {
                    iconElement.removeClass('fas text-black').addClass('far');
                    favoriteCount--;
                }
                favoriteCountElement.text(favoriteCount);
                localStorage.setItem('favorite-' + productId, isFavorite.toString());

                $.ajax({
                    url: '{{ route("favorite.toggle") }}',
                    type: 'POST',
                    data: {
                        product_id: productId,
                        _token: '{{ csrf_token() }}'
                    },
                    error: function(xhr, status, error) {
                        alert('お気に入りの更新に失敗しました');
                        console.error(xhr);
                    }
                });
            });
        });
    </script>
@endsection

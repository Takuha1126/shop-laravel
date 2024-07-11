@extends('layouts.add')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/home/index.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
@endsection

@section('content')
    <main class="main">
        <div class="main__ttl">
            <div class="main__border">
                <div class="main__title">
                    <a class="main__button active-button" id="recommendation-button">おすすめ</a>
                    <a class="main__button--my" id="mylist-button">マイリスト</a>
                </div>
            </div>
            <div class="main__item">
                <div id="recommendation" class="item__ttl">
                    @if (count($recommendedProducts) > 0)
                        @foreach ($recommendedProducts as $product)
                            <div class="recommended-product">
                                <a class="item__button" href="{{ route('item.detail', ['id' => $product->id]) }}">
                                    <img src="{{ Storage::url($product->image) }}" alt="{{ $product->productName }}">
                                </a>
                            </div>
                        @endforeach
                    @else
                        <p>現在、おすすめ商品はありません。</p>
                    @endif
                </div>
                <div id="mylist" class="item__ttl" style="display: none;">
                    @if (count($favoriteProducts) > 0)
                        @foreach ($favoriteProducts as $product)
                            <div class="favorite-product" data-product-id="{{ $product->id }}">
                                <a class="item__button" href="{{ route('item.detail', ['id' => $product->id]) }}">
                                    <img src="{{ Storage::url($product->image) }}" alt="{{ $product->productName }}">
                                </a>
                            </div>
                        @endforeach
                    @else
                        <p>現在、マイリストには商品がありません。</p>
                    @endif
                </div>
            </div>
        </div>
    </main>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    $(document).ready(function () {
        $('#recommendation-button').on('click', function () {
            $('#recommendation-button').addClass('active-button');
            $('#mylist-button').removeClass('active-button');
            $('#recommendation').show();
            $('#mylist').hide();
            $('#search-results').hide();
        });

        $('#mylist-button').on('click', function () {
            $('#mylist-button').addClass('active-button');
            $('#recommendation-button').removeClass('active-button');
            $('#recommendation').hide();
            $('#mylist').show();
            $('#search-results').hide();
        });
    });

</script>
@endsection

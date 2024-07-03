@extends('layouts.add')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/search.css') }}" />
@endsection

@section('content')
    <div class="container">
        <div class="container__ttl">
            <div class="container__item">
                <p class="container__title">検索結果</p>
                @if ($products->count() > 0)
                    <div class="container__group">
                        @foreach ($products as $product)
                            <div class="container__about">
                                <div class="group__card">
                                    <a href="{{ route('item.detail', ['id' => $product->id]) }}"><img src="https://s3-ap-northeast-1.amazonaws.com/shop-laravel/{{ $product->image }}" alt="{{ $product->productName }}"></a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p>該当する商品が見つかりませんでした。</p>
                @endif
            </div>
        </div>
    </div>
@endsection
@extends('layouts.add')

@section('css')
<link rel="stylesheet" href="{{ asset('css/user/mypage.css') }}" />
@endsection

@section('content')
    <div class="main">
        <div class="main__group">
            <div class="main__ttl">
                <div class="main__title">
                    @if ($profile && $profile->profile_image && $profile->profile_image !== 'default.jpg')
                        @if (App::environment('local'))
                            <img src="{{ asset('storage/' . $profile->profile_image) }}" alt="Profile Image">
                        @else
                            <img src="{{ Storage::disk('s3')->url($profile->profile_image) }}" alt="Profile Image">
                        @endif
                    @else
                        @if (App::environment('local'))
                            <img src="{{ asset('storage/profile_images/default.jpg') }}" alt="Default Profile Image">
                        @else
                            <img src="{{ Storage::disk('s3')->url('profile_images/default.jpg') }}" alt="Default Profile Image">
                        @endif
                    @endif
                    <p class="title__user">{{ $profile->name }}</p>
                </div>
                <div class="button">
                    <a href="{{ route('user.edit', ['id' => $profile->id]) }}" class="button__edit">プロフィールを編集</a>
                </div>
            </div>
            <div class="main__about">
                <div class="about__border">
                    <div class="about__button">
                        <a class="about__button-listing active" data-target="listing">出品した商品</a>
                        <a class="about__button-buy" data-target="buy">購入した商品</a>
                    </div>
                </div>
                <div class="main__item" id="itemContainer">
                    <div class="item__ttl">
                        @foreach($productsForSale as $product)
                            <a class="item__button listing" href="{{ route('item.detail', ['id' => $product->id]) }}">
                                @if (App::environment('local'))
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->productName }}">
                                @else
                                    <img src="{{ Storage::disk('s3')->url($product->image) }}" alt="{{ $product->productName }}">
                                @endif
                            </a>
                        @endforeach
                        @foreach($purchasedProducts as $product)
                            <a class="item__button buy" href="{{ route('item.detail', ['id' => $product->product->id]) }}">
                                @if (App::environment('local'))
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->productName }}">
                                @else
                                    <img src="{{ Storage::disk('s3')->url($product->image) }}" alt="{{ $product->productName }}">
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const buttons = document.querySelectorAll(".about__button a");

        const defaultButton = document.querySelector('.about__button-listing');
        if (defaultButton) {
            defaultButton.classList.add("active");
        }

        const items = document.querySelectorAll(".item__button");
        items.forEach(item => {
            if (item.classList.contains("listing")) {
                item.style.display = "block";
            } else {
                item.style.display = "none";
            }
        });

        buttons.forEach(button => {
            button.addEventListener("click", function() {
                const target = this.getAttribute("data-target");

                buttons.forEach(btn => btn.classList.remove("active"));
                this.classList.add("active");

                items.forEach(item => {
                    if ((target === "listing" && item.classList.contains("listing")) ||
                        (target === "buy" && item.classList.contains("buy"))) {
                        item.style.display = "block";
                    } else {
                        item.style.display = "none";
                    }
                });
            });
        });
    });
    </script>
@endsection
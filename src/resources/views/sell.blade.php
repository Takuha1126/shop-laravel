<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/sell.css') }}" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
</head>
<body>
    <header class="header">
        <div class="header__ttl"></div>
    </header>
    @if (session('success'))
    <div class="success">
        {!! session('success') !!}
    </div>
    @endif
    <main class="main">
        <div class="main__ttl">
            <div class="main__group">
                <p class="main__title">商品の出品</p>
            </div>
            <div class="main__item">
                <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                    <div class="main__photo">
                        <label class="label">商品画像</label>
                        <div class="photo__border">
                            <label for="fileInput" class="custom__button" id="fileLabel">画像を選択する</label>
                            <input type="file" accept="image/*" id="fileInput" name="image">
                        </div>
                        @error('image')
                                <p class="error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="main__detail">
                        <div class="detail__border">
                            <p class="detail__title">商品の詳細</p>
                        </div>
                        <div class="detail__about">
                            <div class="detail__text">
                                <label class="label">カテゴリー</label>
                                <select id="categories" name="categories[]" multiple>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->name }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('categories')
                                    <p class="error">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="detail__text">
                                <label class="label">商品の状態</label>
                                <input type="text" name="status">
                            </div>
                            @error('status')
                                <p class="error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="main__detail">
                        <div class="detail__border">
                            <p class="detail__title">商品名と説明</p>
                        </div>
                        <div class="detail__about">
                            <div class="detail__text">
                                <label class="label">商品名</label>
                                <input type="text" name="productName">
                            </div>
                            @error('productName')
                                <p class="error">{{ $message }}</p>
                            @enderror
                            <div class="detail__text">
                                <label class="label">ブランド名</label>
                                <input type="text" name="brand">
                                @error('brand')
                                    <p class="error">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="detail__text">
                                <label class="label">商品の説明</label>
                                <textarea  name="description"></textarea>
                            </div>
                            @error('description')
                                <p class="error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="main__detail">
                        <div class="detail__border">
                            <p class="detail__title">販売価格</p>
                        </div>
                        <div class="detail__text">
                            <label class="label">販売価格</label>
                            <div class="input__container">
                                <span class="input__prefix">￥</span>
                                <input type="text" name="price">
                            </div>
                            @error('price')
                                <p class="error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="button">
                        <button class="button__ttl">出品する</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#categories').select2();

            const fileInput = document.getElementById('fileInput');
            const label = document.getElementById('fileLabel');

            fileInput.addEventListener('change', function () {
                if (fileInput.files.length > 0) {
                    label.classList.add('selected');
                } else {
                    label.classList.remove('selected');
                }
            });
        });
    </script>
</body>
</html>
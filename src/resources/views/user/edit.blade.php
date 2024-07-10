@extends('layouts.add')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/user/edit.css') }}" />
@endsection

@section('content')
    <div class="main">
        <div class="main__ttl">
            <p class="main__title">プロフィール設定</p>
        </div>
        <div class="main__group">
            <form action="{{ route('user.update', ['id' => $profile->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="photo__about">
                    @if ($profile && $profile->profile_image && $profile->profile_image !== 'default.jpg')
                        <img id="preview" src="https://s3-ap-northeast-1.amazonaws.com/shop-laravel/{{ $profile->profile_image }}">
                    @else
                        <img id="preview" src='https://s3-ap-northeast-1.amazonaws.com/shop-laravel/profile_images/default.jpg'>
                    @endif
                    <div class="photo__border">
                        <label for="fileInput" class="custom__button">画像を選択する</label>
                        <input id="fileInput" type="file" accept="image/*" style="display:none;" onchange="previewImage(event)" name="profile_image" capture="false">

                    </div>
                </div>
                <div class="main__edit">
                    <div class="edit__item">
                        <label class="label">ユーザー名</label>
                        <input type="text" name="name" value="{{ $profile->name }}">
                        @error('name')
                            <p class="error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="edit__item">
                        <label class="label">郵便番号</label>
                        <input type="text" name="postal_code" value="{{ $profile->postal_code }}">
                        @error('postal_code')
                            <p class="error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="edit__item">
                        <label class="label">住所</label>
                        <input type="text" name="address" value="{{ $profile->address }}">
                        @error('address')
                            <p class="error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="edit__item">
                        <label class="label">建物名</label>
                        <input type="text" name="building_name" value="{{ $profile->building_name }}">
                        @error('building_name')
                            <p class="error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="edit__button">
                    <button type="submit" class="update__button">更新する</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        function previewImage(event) {
            var input = event.target;

            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    var preview = document.getElementById('preview');
                    if (preview) {
                        preview.src = e.target.result;
                        preview.style.display = 'block';
                    } else {
                        console.error('プレビュー用のエレメントが見つかりません。');
                    }
                };

                reader.readAsDataURL(input.files[0]);
            } else {
                console.error('ファイルが選択されていません。');
            }
        }
    </script>
@endsection

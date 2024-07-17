<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>フリマアプリ</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/change.css') }}" />
</head>
<body>
    <header class="header">
        <div class="header__ttl"></div>
    </header>
    <main class="main">
        <div class="main__ttl">
            <p class="main__title">配送先の変更</p>
        </div>
        <div class="main__item">
            <form action="{{ route('user.address.update') }}" method="POST">
                @csrf
                <div class="main__about">
                    <label class="label">郵便番号</label>
                    <input type="text" name="postal_code"  value="{{ $profile->postal_code }}">
                    @error('postal_code')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="main__about">
                    <label class="label">住所</label>
                    <input type="text" name="address" value="{{ $profile->address }}">
                    @error('address')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="main__about">
                    <label class="label">建物名</label>
                    <input type="text" name="building_name"  value="{{ $profile->building_name }}">
                    @error('building_name')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="button">
                    <button class="button__ttl">更新する</button>
                </div>
            </form>
        </div>
    </main>
<body>
</html>
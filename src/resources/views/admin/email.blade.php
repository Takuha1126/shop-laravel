<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/admin/email.css') }}" />
</head>
<body>
    <header class="header">
        <div class="header__ttl">
            <p class="header__title"><img src="{{ asset('storage/images/logo.svg') }}" alt="Logo"></p>
            <nav class="nav">
                <div class="nav__button">
                    <a class="nav__home" href="{{ route('admin.index')}}">ホーム</a>
                </div>
                <div class="logout">
                    <form action="{{ route('admin.logout') }}"  method="POST">
                        @csrf
                        <button type="submit" class="logout__button">ログアウト</button>
                    </form>
                </div>
            </nav>
        </div>
    </header>
    <main class="main">
        <div class="main__ttl">
            <div class="main__item">
                <p class="main__title">メール内容を入力してください</p>
            </div>
            <div class="main__about">
                <div class="email__first">
                    <form action="{{ route('send.mail') }}" method="POST">
                    @csrf
                        <div class="form-group">
                            <label for="user_id" class="label">対象ユーザー</label>
                            <select name="user_id" class="form-control">
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">
                                        {{ $user->profile->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <p class="error">{{$message}}</p>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="message_content" class="label">メッセージ内容</label>
                            <textarea class="form-control" name="message_content" rows="3"></textarea>
                            @error('message_content')
                                <p class="error">{{$message}}</p>
                            @enderror
                        </div>
                        <div class="button">
                            <button type="submit" class="first-mail">メール送信</button>
                        </div>
                    </form>
                </div>
                <div class="email__all">
                    <div class="all__title">
                        <p class="all__ttl">ユーザー全員に送りたい場合はこちらにメール内容を入力してください</p>
                    </div>
                    <form action="{{ route('send.all') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="message_content" class="label">メッセージ内容</label>
                            <textarea class="form-control" name="message_content" rows="3"></textarea>
                            @error('message_all')
                                <p class="error">{{$message}}</p>
                            @enderror
                        </div>
                        <div class="button">
                            <button type="submit" class="second-mail">全員にメール送信</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
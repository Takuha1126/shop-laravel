<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理画面</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/admin/index.css') }}" />
</head>
<body>
    <header class="header">
        <div class="header__ttl">
            <p class="header__title">
                @if (App::environment('local'))
                    <img src="{{ asset('storage/images/logo.svg') }}">
                @else
                    <img src="{{ Storage::disk('s3')->url('/images/logo.svg') }}">
                @endif
            </p>
            <nav class="nav">
                <div class="nav__button">
                    <a class="nav__email" href="{{ route('email.show')}}">メール</a>
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
            <p class="main__title">ユーザー一覧</p>
        </div>
        <div class="main__about">
            <table class="about__table">
                <tr class="about__tr">
                    <th class="about__th">名前</th>
                    <th class="about__th">メールアドレス</th>
                    <th class="about__th">コメント一覧</th>
                    <th class="about__th">削除</th>
                </tr>
                @if($users->isNotEmpty())
                    @foreach ($users as $user)
                        <tr class="about__tr">
                            <td class="about__td">{{ $user->profile->name }}</td>
                            <td class="about__td">{{ $user->email }}</td>
                            <td class="about__td"><a href="{{ route('admin.comment', ['user' => $user->id]) }}" class="about__link">コメント一覧</a></td>
                            <td class="about__td">
                                <form action="{{route('user.destroy',$user->id) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button class="button__delete">削除</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr class="about__tr">
                        <td class="about__td" colspan="4">登録されたユーザーはいません。</td>
                    </tr>
                @endif
            </table>
        </div>
    </div>
</main>

</body>
</html>
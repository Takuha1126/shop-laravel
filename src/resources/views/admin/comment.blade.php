<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理画面</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/admin/comment.css') }}" />
</head>
<body>
    <header class="header">
        <div class="header__ttl">
            <p class="header__title">
                @if (App::environment('local'))
                    <img src="{{ asset('storage/images/logo.svg') }}">
                @else
                    <img src="https://s3-ap-northeast-1.amazonaws.com/shop-laravel/images/logo.svg" alt="Logo">
                @endif
            </p>
            <nav class="nav">
                <div class="nav__button">
                    <a class="nav__home" href="{{ route('admin.index')}}">ホーム</a>
                </div>
            </nav>
        </div>
    </header>
    @if(session('success'))
        <div class="success">
            {{ session('success') }}
        </div>
    @endif
    <main class="main">
        <div class="main__ttl">
            <div class="main__item">
                <p class="main__title">{{ $user->profile->name }}さんのコメント一覧</p>
            </div>
            <div class="main__about">
                <table class="about__table">
                    <tr class="about__tr">
                        <th class="about__th">投稿日時</th>
                        <th class="about__th">商品名</th>
                        <th class="about__th">コメント</th>
                        <th class="about__th">削除</th>
                    </tr>
                    @if($comments->isNotEmpty())
                        @foreach ($comments as $comment)
                        <tr class="about__tr">
                            <td class="about__td">{{ $comment->created_at->format('Y-m-d H:i:s') }}</td>
                            <td class="about__td">{{ $comment->product->productName }}</td>
                            <td class="about__td">{{ $comment->content }}</td>
                            <td class="about__td">
                                <form action="{{ route('comments.remove', ['id' => $comment->id]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="button__delete">削除</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr class="about__tr">
                            <td class="about__td" colspan="4">コメントはしていません</td>
                        </tr>
                    @endif
                </table>
            </div>
        </div>
    </main>
</body>
</html>
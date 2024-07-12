<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/layouts/app.css') }}" />
    @yield('css')
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
        </div>
    </header>
    <main>
        @yield('content')
    </main>
</body>
</html>
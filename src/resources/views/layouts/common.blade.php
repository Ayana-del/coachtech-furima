<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>COACHTECHフリマ</title>
    <link rel="stylesheet" href="{{ asset('css/layouts/common.css') }}"> @yield('css')
</head>

<body>
    <header class="header">
        <h1 class="header__logo">
            <a href="/">
                <img src="{{ asset('img/COACHTECH_hederlogo.png') }}" alt="COACHTECH">
            </a>
        </h1>

        <div class="header__search">
            @yield('search')
        </div>

        <nav class="header-nav">
            <ul class="header-nav__list">
                @guest
                {{-- 会員登録・ログイン・メール認証画面「以外」の時は、ログインを表示する --}}
                @if (!Route::is(['register', 'login', 'verification.notice']))
                <li class="header-nav__item">
                    <a href="{{ route('login') }}" class="header-nav__link">ログイン</a>
                </li>
                @endif
                @endguest
                @auth
                {{-- メール認証画面（認証待ち）などの「特殊な画面以外」の時は、ログアウトを表示する --}}
                @if (!Route::is(['verification.notice']))
                <li class="header-nav__item">
                    <form action="{{ route('logout') }}" method="post">
                        @csrf
                        <button class="header-nav__button">ログアウト</button>
                    </form>
                </li>
                @endif
                @endauth
            </ul>
        </nav>
    </header>

    <main>
        @yield('content') </main>
</body>

</html>
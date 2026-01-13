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
                @auth
                <li class="header-nav__item">
                    <form class="form" action="{{ route('logout') }}" method="post">
                        @csrf
                        <button class="header-nav__button">ログアウト</button>
                    </form>
                </li>
                @endauth
                @guest
                <li class="header-nav__item">
                    <a href="{{ route('login') }}" class="header-nav__link">ログイン</a>
                </li>
                @endguest
            </ul>
        </nav>
    </header>

    <main>
        @yield('content') </main>
</body>

</html>
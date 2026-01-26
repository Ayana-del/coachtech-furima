<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>COACHTECH</title>
    <link rel="stylesheet" href="{{ asset('css/layouts/common.css') }}">
    @yield('css')
</head>

<body>
    <header class="header">
        <div class="header__inner">
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
                    @if (!Route::is(['register', 'login', 'verification.notice']))
                    <li class="header-nav__item">
                        <a href="{{ route('login') }}" class="header-nav__link">ログイン</a>
                    </li>
                    <li class="header-nav__item">
                        <a href="{{ route('login') }}" class="header-nav__link">マイページ</a>
                    </li>
                    <li class="header-nav__item">
                        <a href="{{ route('login') }}" class="header-nav__link">出品</a>
                    </li>
                    @endif
                    @endguest

                    @auth
                    @if (!Route::is(['verification.notice']))
                    <li class="header-nav__item">
                        <form action="{{ route('logout') }}" method="post">
                            @csrf
                            <button class="header-nav__button">ログアウト</button>
                        </form>
                    </li>
                    {{-- ログイン後は「マイページ」に移動する設定でOKです（ルートを後で作る場合） --}}
                    <li class="header-nav__item">
                        <a href="/mypage" class="header-nav__link">マイページ</a>
                    </li>
                    <li class="header-nav__item">
                        <a href="/sell" class="header-nav__link">出品</a>
                    </li>
                    @endif
                    @endauth
                </ul>
            </nav>
        </div>
    </header>

    <main>
        @yield('content')
    </main>
</body>

</html>
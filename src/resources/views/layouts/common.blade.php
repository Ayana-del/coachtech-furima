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

            {{-- 検索エリア --}}
            <div class="header__search">
                @if (!Route::is(['register', 'login', 'verification.notice']))
                @php
                $isMypage = Request::is('mypage*');
                $searchAction = $isMypage ? '/mypage' : route('item.index');

                // 現在のURLにある 'tab' を取得。なければデフォルトを設定
                $currentTab = request()->get('tab');
                if (empty($currentTab)) {
                $currentTab = $isMypage ? 'sell' : 'recommend';
                }
                @endphp

                {{--
                    修正ポイント: 
                    1. マイページ側のJSから制御するために form に id="search-form" を付与
                    2. 隠し入力フィールドに id="search-tab" を付与
                --}}
                <form action="{{ $searchAction }}" method="GET" id="search-form">
                    <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="何をお探しですか？">

                    <input type="hidden" name="tab" id="search-tab" value="{{ $currentTab }}">
                </form>
                @endif
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
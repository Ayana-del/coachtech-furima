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
    </header>

    <main>
        @yield('content') </main>
</body>

</html>
{{-- resources/views/mypage.blade.php --}}
<h1>マイページ</h1>
<p>ユーザー名: {{ $user->profile->name ?? '未設定' }}</p>

<h2>出品した商品</h2>
<ul>
    @foreach($sellItems as $item)
    <li>{{ $item->name }}</li>
    @endforeach
</ul>

<h2>購入した商品</h2>
<ul>
    @foreach($buyItems as $item)
    <li>{{ $item->name }}</li>
    @endforeach
</ul>
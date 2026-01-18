<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Profile;
use App\Models\User;

class ProfileController extends Controller
{
    public function edit()
    {
        /** @var User $user */
        $user = Auth::user();
        // 過去の設定値を渡す
        $profile = $user->profile ?? new Profile();
        return view('profile.index', compact('user', 'profile'));
    }

    public function update(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        // バリデーション
        $request->validate([
            'name' => 'required|string|max:255',
            'postcode' => 'required|string|max:8',
            'address' => 'required|string|max:255',
            'building' => 'nullable|string|max:255',
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // 1. ユーザー名の更新 (usersテーブル)
        $user->update(['name' => $request->name]);

        // 2. プロフィールデータの準備
        $profileData = $request->only(['postcode', 'address', 'building']);
        $profileData['name'] = $request->name;

        // 3. 画像の保存
        if ($request->hasFile('image_url')) {
            if ($user->profile && $user->profile->image_url) {
                Storage::disk('public')->delete($user->profile->image_url);
            }
            $path = $request->file('image_url')->store('profiles', 'public');
            $profileData['image_url'] = $path;
        }

        // 4. プロフィール情報の保存
        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            $profileData
        );

        // 遷移先
        return redirect()->route('mypage.index')->with('message', 'プロフィールを更新しました');
    }
}

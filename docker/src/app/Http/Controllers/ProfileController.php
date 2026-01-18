<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Profile;


class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        // 過去の設定値を渡す
        $profile = $user->profile ?? new Profile();
        return view('profile.index', compact('user', 'profile'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        // バリデーション
        $request->validate([
            'name' => 'required|string|max:255',
            'postcode' => 'required|string|max:8',
            'address' => 'required|string|max:255',
            'building' => 'nullable|string|max:255',
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // １. ユーザー名の更新
        $user->update(['name' => $request->name]);

        // ２. 画像の保存
        $profileData = $request->only(['postcode', 'address', 'building']);

        if ($request->hasFile('image_url')) {
            // 古い画像を削除（任意）
            if ($user->profile && $user->profile->image_url) {
                Storage::disk('public')->delete($user->profile->image_url);
            }
            // storage/app/public/profiles に保存
            $path = $request->file('image_url')->store('profiles', 'public');
            $profileData['image_url'] = $path;
        }

        // 4. プロフィール情報の保存
        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            $profileData
        );
        //遷移先(一覧へ)
        return redirect()->route('item.index')->with('message', 'プロフィールを更新しました');
    }
}

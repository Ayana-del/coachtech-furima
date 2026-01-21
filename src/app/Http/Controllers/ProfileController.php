<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Profile;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * プロフィール編集画面の表示
     */
    public function edit()
    {
        /** @var User $user */
        $user = Auth::user();
        $profile = $user->profile ?? new Profile();

        return view('profiles.edit', compact('user', 'profile'));
    }

    /**
     * プロフィールの更新・保存処理
     */
    public function update(ProfileRequest $request)
    {
        /** @var User $user */
        $user = Auth::user();

        // 1. 初回設定かどうかを、更新前の住所が空かどうかで判定
        $isFirstTime = empty($user->profile->address);

        // 2. Userテーブル（名前）の更新
        $user->update(['name' => $request->name]);

        // 3. Profileテーブル用データの準備
        $profileData = $request->only(['name', 'postcode', 'address', 'building']);

        // --- 画像処理 ---
        if ($request->has('delete_image')) {
            if ($user->profile && $user->profile->image_url) {
                Storage::disk('public')->delete($user->profile->image_url);
            }
            $profileData['image_url'] = null;
        } elseif ($request->hasFile('image_url')) {
            if ($user->profile && $user->profile->image_url) {
                Storage::disk('public')->delete($user->profile->image_url);
            }
            $path = $request->file('image_url')->store('profiles', 'public');
            $profileData['image_url'] = $path;
        }

        // 4. Profileテーブルの保存（存在しなければ新規作成、あれば更新）
        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            $profileData
        );

        // 5. 初回かリピーターかでリダイレクト先を分岐
        if ($isFirstTime) {
            // 住所が未登録だった（初回）場合は商品一覧（トップ）へ
            return redirect('/')->with('message', 'プロフィール設定が完了しました！早速お買い物を始めましょう。');
        } else {
            // すでに登録済み（2回目以降）の場合はプロフィール編集画面へ
            return redirect()->route('profile.edit')->with('message', 'プロフィールを更新しました');
        }
    }
}
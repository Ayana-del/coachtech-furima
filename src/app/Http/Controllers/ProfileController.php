<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
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
        $profile = $user->profile ?? new Profile();

        return view('profiles.edit', compact('user', 'profile'));
    }

    public function update(ProfileRequest $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $isFirstTime = empty($user->profile->address);

        $user->update(['name' => $request->name]);

        $profileData = $request->only(['name', 'postcode', 'address', 'building']);

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

        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            $profileData
        );

        if ($isFirstTime) {
            return redirect('/')->with('message', 'プロフィール設定が完了しました！早速お買い物を始めましょう。');
        } else {
            return redirect()->route('profile.edit')->with('message', 'プロフィールを更新しました');
        }
    }
}

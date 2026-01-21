<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
// 追加：リダイレクト制御に使用するクラス
use Laravel\Fortify\Http\Responses\LoginResponse;
use Illuminate\Support\Facades\Auth;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(
            \Laravel\Fortify\Http\Requests\LoginRequest::class,
            \App\Http\Requests\LoginRequest::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);

        // 会員登録画面の表示
        Fortify::registerView(function () {
            return view('auth.register');
        });

        // ログイン画面の表示
        Fortify::loginView(function () {
            return view('auth.login');
        });

        // メール認証待ち画面の表示
        Fortify::verifyEmailView(function () {
            return view('auth.verify-email');
        });

        // ログイン試行回数の制限（レートリミッター）
        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->email;
            return Limit::perMinute(10)->by($email . $request->ip());
        });

        // --- 追加：ログイン後のリダイレクト（振り分け）制御 ---
        $this->app->instance(LoginResponse::class, new class extends LoginResponse {
            public function toResponse($request)
            {
                $user = Auth::user();
                $profile = $user->profile;

                // プロフィールが未登録、または必須項目（郵便番号・住所）が空の場合はプロフィール編集へ
                if (!$profile || empty($profile->postcode) || empty($profile->address)) {
                    return redirect()->route('profile.edit');
                }

                // すべて入力済みなら商品一覧（トップ）へ
                return redirect('/');
            }
        });
    }
}

<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Request;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //全てのビューに対して、この処理を適用する
        View::composer('*', function ($view) {
            //cookieのdevice_tokenを取得
            $token = Request::cookie('device_token');

            $user = null;
            if ($token) {
                $user = User::with('userStat')
                                ->where('device_token', $token)
                                ->first();
            }

            //全てのBladeで使えるように共有する
            $view->with('currentUser', $user);
        });
    }
}

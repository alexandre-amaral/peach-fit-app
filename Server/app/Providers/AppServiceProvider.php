<?php

namespace App\Providers;

use App\Models\AdminInfo;
use App\Utils\AdminInfoUtil;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;
use App\Models\TrainingSession;
use App\Observers\TrainingSessionObserver;

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
        if ($this->app->runningInConsole()) {
            return;
        }


        $cache = Cache::class;

        if (is_null($cache::get(AdminInfoUtil::CACHE_NAME))) {

            $emailInfo = AdminInfo::where('id', AdminInfoUtil::EMAIL)->first();
            $telInfo = AdminInfo::where('id', AdminInfoUtil::TEL)->first();



            $adminInfo = [
                'email' => '',
                'tel' => '',
            ];

            if ($emailInfo != null) {
                $adminInfo['email'] = $emailInfo->info;
            }

            if ($telInfo != null) {
                $adminInfo['tel'] = $telInfo->info;
            }

            $cache::rememberForever(AdminInfoUtil::CACHE_NAME, function () use ($adminInfo) {
                return $adminInfo;
            });
        }

        View::share('admin', $cache::get(AdminInfoUtil::CACHE_NAME));
        TrainingSession::observe(TrainingSessionObserver::class);
    }
}

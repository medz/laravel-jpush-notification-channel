<?php

declare(strict_types=1);

namespace Medz\Laravel\Notifications\JPush;

use JPush\Client as JPushClient;
use Illuminate\Support\Facades\Notification;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
        Notification::extend('jpush', function ($app) {
            return new Channel(new JPushClient(
                $app['config']['services.jpush.app_key'],
                $app['config']['services.jpush.master_secret'],
                storage_path('logs/jpush.log'),
                intval($app['config']['services.jpush.retry_times']) ?: 3
            ));
        });
    }
}

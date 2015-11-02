<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Log;
use Illuminate\Support\Facades\Queue;
use App\NotifyLog;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Queue::after(function ($connection, $job, $data) {
            if(!empty($data['id'])){
                NotifyLog::where('job', '=', $data['id'])->update(array('status' => 201));
            }

        });

        Queue::failing(function ($connection, $job, $data) {
            if(!empty($data['id'])){
                NotifyLog::where('job', '=', $data['id'])->update(array('status' => 500));
            }
            $command = (unserialize($data['data']['command']));
            $command->failed($data['id']);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}

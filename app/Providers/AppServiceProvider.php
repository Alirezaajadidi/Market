<?php

namespace App\Providers;

use http\Client;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        Validator::extend('recaptcha', function ($attribute, $value, $parameters, $validator) {
            // POST
            $client = new \GuzzleHttp\Client();
            $response = $client->request('POST', 'https://www.google.com/recaptcha/api/siteverify', [
                'form_params' => [
                    'secret' => '6LdfhFwbAAAAAK_tWA5Hyqr9XqLprYmPKC_WOXOf',
                    'response' => $value,
                    'remoteip' => request()->ip()
                ]
            ]);

            $response = json_decode($response->getBody());
            return $response->success;
        });
    }
}

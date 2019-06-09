<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema ; 


class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        

        \View::composer('*',function($view){


            $channels = \App\Channel::all();

           /* $channels = \Cache::rememberForever('channels',function(){


                return \App\Channel::all(); 
            });*/


            $view->with('channels',$channels);
        });
        /*
        \View::share('channels',\App\Channel::all());
        */

        //Create A New Validator 
        \Validator::extend('spamfree','App\Rules\SpamFree@passes');
        
        \Validator::extend('recaptchavalid','App\Rules\Recaptcha@passes');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if($this->app->isLocal()){

            $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class) ; 
        }
    }
}

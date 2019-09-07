<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Campaign;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Schema::defaultStringLength(191);

        Validator::extend(
            'campaign',
            'App\\Validators\\CampaignUser@validate'
        );

        Validator::extend(
            'recaptcha',
            'App\\Validators\\ReCaptcha@validate'
        );

        Validator::extend(
            'datepicker',
            'App\\Validators\\Datepicker@validate'
        );

        Validator::replacer(
            'datepicker',
            'App\\Validators\\Datepicker@message'
        );

        \Blade::directive('honeypot', function ($expression) {
            return \Honeypot::generate('_mn', '_mt');
        });


        // Check Schema connected
        if( Schema::hasTable('campaigns') ){

            if( $campaign = Campaign::where([
                ['url','REGEXP','^https?\:\/\/' . preg_replace('/([^a-z0-9])/i','\\\$1',rtrim(\Request::getHost(), '/')) . '\/?$'],
            ])->first() ) {

                // Setup Cookies
                config(['session.cookie'=>'campaign_'.$campaign->id.'_session']);

            }

        }

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

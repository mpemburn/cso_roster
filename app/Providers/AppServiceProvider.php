<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Validators\MemberEmailFoundValidator;
use App\Validators\MatchesOldPasswordValidator;
use App\Validators\PasswordInvalidPatternValidator;
use Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * Add a validator method to ensure that the email is found in the database.
         */
        Validator::extend('member_email_found', MemberEmailFoundValidator::class);

        /**
         * Add a validator method to ensure that the email is found in the database.
         */
        Validator::extend('matches_old_password', MatchesOldPasswordValidator::class);

        /**
         * Add a validator method to check the pattern of the password
         */
        Validator::extend('invalid_pattern', PasswordInvalidPatternValidator::class);

        Schema::defaultStringLength(191);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() == 'local') {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }

        $this->app->bind(
            \Auth0\Login\Contract\Auth0UserRepository::class,
            \Auth0\Login\Repository\Auth0UserRepository::class
        );
    }
}

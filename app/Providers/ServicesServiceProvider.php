<?php

namespace App\Providers;

use App\Models\Member;
use App\Models\User;
use Illuminate\Support\ServiceProvider;
use App\Contracts\Services\MemberServiceContract;

use App\Services\MemberService;

/**
 * Class ServicesServiceProvider
 * @package App\Providers
 */
class ServicesServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(MemberServiceContract::class, function () {
            return new MemberService(new Member(), new User());
        });
    }
}

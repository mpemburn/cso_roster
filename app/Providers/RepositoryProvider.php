<?php
/**
 * RepositoryProvider: binds repositories to models.
 */
declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models;
use App\Repositories;
use App\Contracts\Repositories\BoardRoleRepositoryContract;
use App\Contracts\Repositories\ContactRepositoryContract;
use App\Contracts\Repositories\DuesRepositoryContract;
use App\Contracts\Repositories\GuestRepositoryContract;
use App\Contracts\Repositories\MemberRepositoryContract;
use App\Contracts\Repositories\Auth\ResetPasswordRepositoryContract;

class RepositoryProvider extends ServiceProvider {

    /**
     * Registers repositories
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ContactRepositoryContract::class, function () {
            return new Repositories\ContactRepository(
                new Models\Contact(),
                $this->app->make('log')
            );
        });

        $this->app->bind(BoardRoleRepositoryContract::class, function () {
            return new Repositories\BoardRoleRepository(
                new Models\BoardRole(),
                $this->app->make('log')
            );/**/
        });

        $this->app->bind(DuesRepositoryContract::class, function () {
            return new Repositories\DuesRepository(
                new Models\Dues(),
                $this->app->make('log')
            );/**/
        });

        $this->app->bind(GuestRepositoryContract::class, function () {
            return new Repositories\GuestRepository(
                new Models\Guest(),
                $this->app->make('log')
            );/**/
        });

        $this->app->bind(MemberRepositoryContract::class, function () {
            return new Repositories\MemberRepository(
                new Models\Member(),
                $this->app->make('log')
            );
        });
        
        $this->app->bind(ResetPasswordRepositoryContract::class, function () {
            return new Repositories\ResetPasswordRepository(
                new Models\User(),
                $this->app->make('log')
            );
        });
    }
}
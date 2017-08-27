<?php
/**
 * RepositoryProvider: binds repositories to models.
 */
declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models;
use App\Repositories;
use App\Contracts\Repositories\DuesRepositoryContract;
use App\Contracts\Repositories\MemberRepositoryContract;
use App\Contracts\Repositories\ContactRepositoryContract;

class RepositoryProvider extends ServiceProvider {

    /**
     * Registers repositories
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(MemberRepositoryContract::class, function () {
            return new Repositories\MemberRepository(
                new Models\Member(),
                $this->app->make('log')
            );
        });
        
        $this->app->bind(DuesRepositoryContract::class, function () {
            return new Repositories\DuesRepository(
                new Models\Dues(),
                $this->app->make('log')
            );/**/
        });
        
        $this->app->bind(ContactRepositoryContract::class, function () {
            return new Repositories\ContactRepository(
                new Models\Contact(),
                $this->app->make('log')
            );
        });
    }
}
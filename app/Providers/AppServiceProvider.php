<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Core\Domain\Repository\CategoryRepositoryInterface;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use App\Repositories\Transaction\DBTransaction;
use Core\UseCase\Interfaces\TransactionInterface;
use PhpParser\Builder\Trait_;
use Psy\Readline\Transient;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            CategoryRepositoryInterface::class,
            CategoryEloquentRepository::class
        );

        $this->app->bind(
            TransactionInterface::class,
            DBTransaction::class,
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}

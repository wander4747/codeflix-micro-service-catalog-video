<?php

namespace App\Providers;

use App\Events\VideoEvent;
use App\Repositories\Eloquent\CastMemberEloquentRepository;
use Illuminate\Support\ServiceProvider;
use Core\Domain\Repository\CategoryRepositoryInterface;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use App\Repositories\Eloquent\GenreEloquentRepository;
use App\Repositories\Eloquent\VideoEloquentRepository;
use App\Repositories\Transaction\DBTransaction;
use App\Services\Storage\FileStorage;
use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\Domain\Repository\VideoRepositoryInterface;
use Core\UseCase\Interfaces\FileStorageInterface;
use Core\UseCase\Interfaces\TransactionInterface;
use Core\UseCase\Video\Interfaces\VideoEventManagerInterface;

class CleanArchServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->bindRepositories();

        $this->app->bind(
            TransactionInterface::class,
            DBTransaction::class,
        );

        $this->app->bind(
            FileStorageInterface::class,
            FileStorage::class,
        );

        $this->app->singleton(
            VideoEventManagerInterface::class,
            VideoEvent::class
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    private function bindRepositories()
    {
        $this->app->singleton(
            CategoryRepositoryInterface::class,
            CategoryEloquentRepository::class
        );

        $this->app->singleton(
            GenreRepositoryInterface::class,
            GenreEloquentRepository::class
        );

        $this->app->singleton(
            CastMemberRepositoryInterface::class,
            CastMemberEloquentRepository::class
        );

        $this->app->singleton(
            VideoRepositoryInterface::class,
            VideoEloquentRepository::class
        );
    }
}

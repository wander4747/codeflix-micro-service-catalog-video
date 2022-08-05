<?php

namespace App\Repositories\Eloquent;

use App\Models\Video as Model;
use App\Repositories\Presenters\PaginationPresenter;
use Core\Domain\Builder\Video\UpdateVideoBuilder;
use Core\Domain\Entity\{
    Entity,
    Video as VideoEntity
};

use Core\Domain\ValueObject\Uuid;
use Core\Domain\Repository\VideoRepositoryInterface;

class VideoEloquentRepository implements VideoRepositoryInterface
{
    //use VideoTrait;

    public function __construct(
        protected Model $model,
    ) {}

    public function insert(Entity $entity): Entity
    {
        
    }

    public function findById(string $entityId): Entity
    {
        
    }

    public function findAll(string $filter = '', $order = 'DESC'): array
    {
       
    }

    public function paginate(string $filter = '', $order = 'DESC', int $page = 1, int $totalPage = 15): PaginationInterface
    {
        
    }

    public function update(Entity $entity): Entity
    {
       

    }

    public function delete(string $entityId): bool
    {
        
    }

    public function updateMedia(Entity $entity): Entity
    {
       
    }

  
}
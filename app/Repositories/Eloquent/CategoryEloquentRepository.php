<?php

namespace App\Repositories\Eloquent;

use App\Models\Category as Model;
use App\Repositories\Presenters\PaginationPresenter;
use Core\Domain\Entity\Category;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\Domain\Repository\PaginationInterface;

class CategoryEloquentRepository implements CategoryRepositoryInterface
{
    protected $model;

    public function __construct(Model $category)
    {
        $this->model = $category;
    }

    public function insert(Category $entity): Category
    {
        $category = $this->model->create([
            'id' => $entity->id(),
            'name' => $entity->name,
            'description' => $entity->description,
            'is_active' => $entity->isActive,
            'created_at' => $entity->createdAt(),
        ]);

        return $this->toCategory($category);
    }

    public function findById(string $categoryId): Category
    {
        if (!$category = $this->model->find($categoryId)) {
            throw new NotFoundException();
        }

        return $this->toCategory($category);
    }

    public function findAll(string $filter = '', $order = 'DESC'): array
    {
        $categories = $this->model
            ->where(function ($query) use ($filter) {
                if ($filter)
                    $query->where('name', 'LIKE', "%{$filter}%");
            })
            ->orderBy('id', $order)
            ->get();

        return $categories->toArray();
    }

    public function paginate(string $filter = '', $order = 'DESC', int $page = 1, int $totalPage = 15): PaginationInterface
    {
        return new PaginationPresenter();
    }

    public function update(Category $category): Category
    {
        return new Category(
            name: 'update'
        );
    }

    public function delete(string $categoryId): bool
    {
        return true;
    }

    private function toCategory(object $object): Category
    {
        return new Category(
            id: $object->id,
            name: $object->name
        );
    }
}

<?php

namespace Core\UseCase\Category;

use Core\Domain\Entity\Category;
use Core\Domain\Repository\CategoryRepositoryInterface;

class CreateCategoryUseCase
{

    protected CategoryRepositoryInterface $repository;

    public function __construct(CategoryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute()
    {
        $category = new Category(
            name: "test"
        );

        $this->repository->insert($category);
    }
}
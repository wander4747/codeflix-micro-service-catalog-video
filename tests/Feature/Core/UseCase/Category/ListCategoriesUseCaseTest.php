<?php

namespace Tests\Feature\Core\UseCase\Category;

use Tests\TestCase;
use App\Models\Category as Model;
use Core\UseCase\Category\ListCategoriesUseCase;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use Core\UseCase\DTO\Category\ListCategories\ListCategoriesInputDto;

class ListCategoriesUseCaseTest extends TestCase
{
    public function testListAllEmpty()
    {
        $responseUseCase = $this->createUseCase();
        $this->assertCount(0, $responseUseCase->items);
    }

    public function testListAll()
    {
        $categoriesDb = Model::factory()->count(20)->create();
        $responseUseCase = $this->createUseCase();

        $this->assertCount(15, $responseUseCase->items);
        $this->assertEquals(count($categoriesDb), $responseUseCase->total);
    }

    private function createUseCase()
    {
        $repository = new CategoryEloquentRepository(new Model());
        $useCase = new ListCategoriesUseCase($repository);
        return $useCase->execute(new ListCategoriesInputDto());
    }
}

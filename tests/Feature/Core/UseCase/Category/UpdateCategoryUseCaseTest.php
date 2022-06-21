<?php

namespace Tests\Feature\Core\UseCase\Category;

use Tests\TestCase;
use App\Models\Category as Model;
use Core\UseCase\Category\UpdateCategoryUseCase;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use Core\UseCase\DTO\Category\UpdateCategory\CategoryUpdateInputDto;

class UpdateCategoryUseCaseTest extends TestCase
{
    public function testUpdate()
    {
        $categoryDb = Model::factory()->create();

        $repository = new CategoryEloquentRepository(new Model());
        $useCase = new UpdateCategoryUseCase($repository);
        $responseUseCase = $useCase->execute(
            new CategoryUpdateInputDto(
                id: $categoryDb->id,
                name: 'name updated',
            )
        );

        $this->assertEquals('name updated', $responseUseCase->name);
        $this->assertEquals($categoryDb->description, $responseUseCase->description);

        $this->assertDatabaseHas('categories', [
            'name' => $responseUseCase->name,
        ]);
    }
}

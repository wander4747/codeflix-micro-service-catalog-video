<?php

namespace Tests\Feature\Core\UseCase\Category;

use Tests\TestCase;
use App\Models\Category as Model;
use Core\UseCase\Category\CreateCategoryUseCase;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use Core\UseCase\DTO\Category\CreateCategory\CategoryCreateInputDto;

class CreateCategoryUseCaseTest extends TestCase
{
    
    public function testCreate()
    {
        $repository = new CategoryEloquentRepository(new Model());
        $useCase = new CreateCategoryUseCase($repository);
        $responseUsecase = $useCase->execute(new CategoryCreateInputDto(
            name: 'Test',
        ));

        $this->assertEquals('Test', $responseUsecase->name);
        $this->assertNotEmpty($responseUsecase->id);

        $this->assertDatabaseHas('categories', [
            'id' => $responseUsecase->id
        ]);
    }
}

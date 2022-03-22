<?php

namespace Tests\Unit\UseCase\Category;

use Core\Domain\Entity\Category;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\UseCase\Category\CreateCategoryUseCase;
use PHPUnit\Framework\TestCase;
use Mockery;
use stdClass;
use Ramsey\Uuid\Uuid;

class CreateCategoryUseCaseUnitTest extends TestCase
{

    public function testCreateNewCategory()
    {
        $this->mockRepository = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        $this->mockRepository->shouldReceive('insert');

        $useCase = new CreateCategoryUseCase($this->mockRepository);
        $useCase->execute();

        $this->assertTrue(true);

        Mockery::close();
    }
}
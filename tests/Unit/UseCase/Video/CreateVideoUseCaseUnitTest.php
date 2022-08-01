<?php

namespace Tests\Unit\UseCase\Video;

use Core\Domain\Entity\Video;
use Core\Domain\Enum\Rating;
use Core\Domain\Repository\{
    CastMemberRepositoryInterface,
    CategoryRepositoryInterface,
    GenreRepositoryInterface,
    VideoRepositoryInterface
};
use Core\UseCase\DTO\Video\Create\{
    CreateInputVideoDto,
    CreateOutputVideoDto
};
use Core\UseCase\Interfaces\{
    FileStorageInterface,
    TransactionInterface
};
use PHPUnit\Framework\TestCase;
use Core\UseCase\Video\CreateVideoUseCase as UseCase;
use Core\UseCase\Video\Interfaces\VideoEventManagerInterface;
use Mockery;

class CreateVideoUseCaseUnitTest extends TestCase
{

    protected $useCase;
    protected function setUp(): void
    {
        $this->useCase = new UseCase(
            repository: $this->createMockRepository(),
            transaction: $this->createMockTransaction(),
            storage: $this->createMockFileStorage(),
            eventManager: $this->createMockEventManager(),
            repositoryCategory: $this->createMockRepositoryCategory(),
            repositoryGenre: $this->createMockRepositoryGenre(),
            repositoryCastMember: $this->createMockRepositoryCastMember(),
        );

        parent::setUp();
    }

    public function testExecInputOutput()
    {
        $response = $this->useCase->execute(
            input: $this->createMockInputDTO()
        );
        
        $this->assertInstanceOf(CreateOutputVideoDto::class, $response);
        $this->assertTrue(true);
    }

    protected function createMockRepository()
    {
        $mock = Mockery::mock(stdClass::class, VideoRepositoryInterface::class);
        $mock->shouldReceive('insert')->andReturn($this->createMockEntity());
        $mock->shouldReceive('updateMedia');
        return $mock;
    }

    protected function createMockRepositoryCategory(array $categoriesResponse = [])
    {
        $mock = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        $mock->shouldReceive('getIdsListIds')->andReturn($categoriesResponse);
        return $mock;
    }

    protected function createMockRepositoryGenre(array $genresResponse = [])
    {
        $mock = Mockery::mock(stdClass::class, GenreRepositoryInterface::class);
        $mock->shouldReceive('getIdsListIds')->andReturn($genresResponse);
        return $mock;
    }

    protected function createMockRepositoryCastMember(array $castMemberResponse = [])
    {
        $mock = Mockery::mock(stdClass::class, CastMemberRepositoryInterface::class);
        $mock->shouldReceive('getIdsListIds')->andReturn($castMemberResponse);
        return $mock;
    }

    protected function createMockTransaction()
    {
        $mock = Mockery::mock(stdClass::class, TransactionInterface::class);
        $mock->shouldReceive('commit');
        $mock->shouldReceive('rollback');

        return $mock;
    }

    protected function createMockFileStorage()
    {
        $mock =  Mockery::mock(stdClass::class, FileStorageInterface::class);
        $mock->shouldReceive('store')->andReturn('path/file.png');
        return $mock;
    }

    protected function createMockEventManager()
    {
        $mock = Mockery::mock(stdClass::class, VideoEventManagerInterface::class);
        $mock->shouldReceive('dispatch');
        return $mock;
    }

    protected function createMockInputDTO()
    {
        return Mockery::mock(CreateInputVideoDto::class, [
            'title',
            'description',
            2022,
            10,
            true,
            Rating::RATE10,
            [],
            [],
            [],
        ]);
    }

    protected function createMockEntity()
    {
        return Mockery::mock(Video::class, [
            'title',
            'description',
            2022,
            10,
            true,
            Rating::RATE10
        ]);
    }
}

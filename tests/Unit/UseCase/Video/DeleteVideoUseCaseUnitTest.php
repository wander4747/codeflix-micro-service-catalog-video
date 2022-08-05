<?php

namespace Tests\Unit\UseCase\Video;

use Core\Domain\Repository\VideoRepositoryInterface;
use Core\Domain\ValueObject\Uuid;
use Core\UseCase\DTO\Video\Delete\{
    DeleteInputVideoDto,
    DeleteOutputVideoDto
};
use Core\UseCase\Video\DeleteVideoUseCase;
use Mockery;
use PHPUnit\Framework\TestCase;

class DeleteVideoUseCaseUnitTest extends TestCase
{
    public function testDelete()
    {
        $useCase = new DeleteVideoUseCase(
            repository: $this->mockRepository()
        );

        $response = $useCase->execute(
            input: $this->mockInputDto()
        );

        $this->assertInstanceOf(DeleteOutputVideoDto::class, $response);
        $this->assertTrue(true);

        Mockery::close();
    }

    private function mockRepository()
    {
        $mock =  Mockery::mock(stdClass::class, VideoRepositoryInterface::class);
        $mock->shouldReceive('delete')
            ->once()
            ->andReturn(true);
        return $mock;
    }

    private function mockInputDto()
    {
        return  Mockery::mock(DeleteInputVideoDto::class, [
            Uuid::random()
        ]);
    }
}

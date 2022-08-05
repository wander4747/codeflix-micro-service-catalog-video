<?php

namespace Tests\Unit\UseCase\Video;

use Core\Domain\Entity\Video;
use Core\Domain\Enum\Rating;
use Core\Domain\Repository\VideoRepositoryInterface;
use Core\Domain\ValueObject\Uuid;
use Core\UseCase\DTO\Video\List\{
    ListInputVideoDto,
    ListOutputVideoDto
};
use Core\UseCase\Video\ListVideoUseCase;
use Mockery;
use PHPUnit\Framework\TestCase;

class ListVideoUseCaseUnitTest extends TestCase
{
    public function testList()
    {
        $uuid = Uuid::random();
        $useCase = new ListVideoUseCase(
            repository: $this->mockRepository()
        );

        $response = $useCase->execute(
            input: $this->mockInputDTO($uuid)
        );

        $this->assertInstanceOf(ListOutputVideoDto::class, $response);
        Mockery::close();
    }

    private function mockRepository()
    {
        $mock =  Mockery::mock(stdClass::class, VideoRepositoryInterface::class);
        $mock->shouldReceive('findById')
            ->once()
            ->andReturn($this->getEntity());
        return $mock;
    }

    private function mockInputDTO(string $id)
    {
        return Mockery::mock(ListInputVideoDto::class, [
            $id
        ]);
    }

    private function getEntity(): Video
    {
        return new Video(
            title: "title",
            description: "description",
            yearLaunched: 2022,
            duration: 12,
            opened: true,
            rating: Rating::L
        );
    }
}

<?php

namespace Tests\Unit\UseCase\Video;

use Core\Domain\Entity\Video;
use Core\Domain\Enum\Rating;
use Core\Domain\Repository\PaginationInterface;
use Core\Domain\Repository\VideoRepositoryInterface;
use Core\Domain\ValueObject\Uuid;
use Core\UseCase\DTO\Video\Paginate\{
    PaginateInputVideoDto
};
use Core\UseCase\Video\ListVideosUseCase;
use Mockery;
use PHPUnit\Framework\TestCase;
use Tests\Unit\UseCase\UseCaseTrait;

class ListVideosUseCaseUnitTest extends TestCase
{
    use UseCaseTrait;

    public function testListaPaginate()
    {
        $uuid = Uuid::random();
        $useCase = new ListVideosUseCase(
            repository: $this->mockRepository()
        );

        $response = $useCase->execute(
            input: $this->mockInputDTO()
        );

        $this->assertInstanceOf(PaginationInterface::class, $response);

        $this->assertTrue(true);

        Mockery::close();
    }

    private function mockRepository()
    {
        $mock =  Mockery::mock(stdClass::class, VideoRepositoryInterface::class);
        $mock->shouldReceive('paginate')
            //->once()
            ->andReturn($this->mockPagination());
        return $mock;
    }

    private function mockInputDTO()
    {
        return Mockery::mock(PaginateInputVideoDto::class, [
           "",
           "DESC",
           1,
           15
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

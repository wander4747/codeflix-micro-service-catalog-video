<?php

namespace Tests\Feature\Core\UseCase\Video;

use App\Models\Video;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\VideoRepositoryInterface;
use Core\UseCase\DTO\Video\List\ListInputVideoDto;
use Core\UseCase\Video\ListVideoUseCase;
use Tests\TestCase;

class ListVideoUseCaseTest extends TestCase
{
    public function testList()
    {
        $video = Video::factory()->create();

        $useCase = new ListVideoUseCase(
            $this->app->make(VideoRepositoryInterface::class)
        );

        $response = $useCase->execute(new ListInputVideoDto(
            id: $video->id
        ));

        $this->assertNotNull($response);
        $this->assertEquals($video->id, $response->id);
    }

    public function testException()
    {
        $this->expectException(NotFoundException::class);

        $useCase = new ListVideoUseCase(
            $this->app->make(VideoRepositoryInterface::class)
        );

        $useCase->execute(new ListInputVideoDto(
            id: 'fake_id'
        ));
    }
}
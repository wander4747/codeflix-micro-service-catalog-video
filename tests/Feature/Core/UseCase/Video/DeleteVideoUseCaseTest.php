<?php

namespace Tests\Feature\Core\UseCase\Video;

use App\Models\Video;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\VideoRepositoryInterface;
use Core\UseCase\DTO\Video\Delete\DeleteInputVideoDto;
use Core\UseCase\Video\DeleteVideoUseCase;
use Tests\TestCase;

class DeleteVideoUseCaseTest extends TestCase
{
    public function testDelete()
    {
        $video = Video::factory()->create();

        $useCase = new DeleteVideoUseCase(
            $this->app->make(VideoRepositoryInterface::class)
        );

        $response = $useCase->execute(new DeleteInputVideoDto(
            id: $video->id
        ));

        $this->assertTrue($response->success);
    }

    public function testDeleteIdNotFound()
    {
        $this->expectException(NotFoundException::class);

        $useCase = new DeleteVideoUseCase(
            $this->app->make(VideoRepositoryInterface::class)
        );

        $useCase->execute(new DeleteInputVideoDTO(
            id: 'fake_id'
        ));
    }
}
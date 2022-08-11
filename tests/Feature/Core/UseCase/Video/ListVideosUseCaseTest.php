<?php

namespace Tests\Feature\Core\UseCase\Video;

use App\Models\Video;
use Core\Domain\Repository\VideoRepositoryInterface;
use Core\UseCase\DTO\Video\Paginate\PaginateInputVideoDto;
use Tests\TestCase;
use Core\UseCase\Video\ListVideosUseCase;

class ListVideosUseCaseTest extends TestCase
{
    /**
     * @dataProvider provider
     */
    public function test_pagination(
        int $total,
        int $perPage,
    ) {
        Video::factory()->count($total)->create();

        $useCase = new ListVideosUseCase(
            $this->app->make(VideoRepositoryInterface::class)
        );

        $response = $useCase->execute(new PaginateInputVideoDto(
            filter: '',
            order: 'desc',
            page: 1,
            totalPage: $perPage
        ));

        $this->assertCount($perPage, $response->items);
        $this->assertEquals($total, $response->total);
    }

    protected function provider(): array
    {
        return [
            [
                'total' => 30,
                'perPage' => 10,
            ], [
                'total' => 20,
                'perPage' => 5,
            ], [
                'total' => 0,
                'perPage' => 0,
            ],
        ];
    }
}
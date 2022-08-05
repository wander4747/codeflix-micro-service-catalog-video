<?php 

namespace Core\UseCase\Video;

use Core\Domain\Repository\VideoRepositoryInterface;
use Core\UseCase\DTO\Video\Paginate\PaginateInputVideoDto;
use Core\UseCase\DTO\Video\Paginate\PaginateOutputVideoDto;

class ListVideosUseCase
{
    public function __construct(
        private VideoRepositoryInterface $repository
    )
    {

    }
	
    public function execute(PaginateInputVideoDto $input): PaginateOutputVideoDto
    {
        $response = $this->repository->paginate(
            filter: $input->filter,
            order: $input->order,
            page: $input->page,
            totalPage: $input->totalPage
        );
        return new PaginateOutputVideoDto(
            items: $response->items(),
            total: $response->total(),
            current_page: $response->currentPage(),
            last_page: $response->lastPage(),
            first_page: $response->firstPage(),
            per_page: $response->perPage(),
            to: $response->to(),
            from: $response->from(),
        );
    }
}
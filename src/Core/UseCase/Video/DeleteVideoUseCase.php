<?php 

namespace Core\UseCase\Video;

use Core\UseCase\DTO\Video\Create\CreateInputVideoDto;
use Core\UseCase\DTO\Video\Create\CreateOutputVideoDto;
use Throwable;
use Core\Domain\Builder\Video\BuilderVideo;
use Core\Domain\Builder\Video\Builder;
use Core\Domain\Repository\VideoRepositoryInterface;
use Core\UseCase\DTO\Video\Delete\DeleteInputVideoDto;
use Core\UseCase\DTO\Video\Delete\DeleteOutputVideoDto;

class DeleteVideoUseCase
{
	
    public function __construct(
        private VideoRepositoryInterface $repository
    )
    {
        
    }
    public function execute(DeleteInputVideoDto $input): DeleteOutputVideoDto
    {
        $deleted = $this->repository->delete($input->id);

        return new DeleteOutputVideoDTO(
            success: $deleted
        );
    }
}
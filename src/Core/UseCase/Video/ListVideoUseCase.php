<?php 

namespace Core\UseCase\Video;

use Core\Domain\Repository\VideoRepositoryInterface;
use Core\UseCase\DTO\Video\List\ListInputVideoDto;
use Core\UseCase\DTO\Video\List\ListOutputVideoDto;

class ListVideoUseCase
{
    public function __construct(
        private VideoRepositoryInterface $repository
    )
    {

    }
	
    public function execute(ListInputVideoDto $input): ListOutputVideoDto
    {
        $entity = $this->repository->findById($input->id);

        return new ListOutputVideoDto(
            id: $entity->id(),
            title: $entity->title,
            description: $entity->description,
            yearLaunched: $entity->yearLaunched,
            duration: $entity->duration,
            opened: $entity->opened,
            rating: $entity->rating,
            createdAt: $entity->createdAt(),
            categories: $entity->categoriesId,
            genres: $entity->genresId,
            castMembers: $entity->castMembersId,
            videoFile: $entity->videoFile()?->path,
            thumbFile: $entity->thumbFile()?->path(),
            thumbHalf: $entity->thumbHalf()?->path(),
            bannerFile: $entity->bannerFile()?->path(),
            trailerFile: $entity->trailerFile()?->path,
        );
    }
}
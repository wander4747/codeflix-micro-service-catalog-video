<?php 

namespace Core\UseCase\Video;

use Core\UseCase\DTO\Video\Create\CreateInputVideoDto;
use Core\UseCase\DTO\Video\Create\CreateOutputVideoDto;
use Throwable;
use Core\Domain\Builder\Video\BuilderVideo;
use Core\Domain\Builder\Video\Builder;

class CreateVideoUseCase extends BaseVideoUseCase
{
	protected function getBuilder(): Builder {
        return new BuilderVideo();
	}

    public function execute(CreateInputVideoDto $input): CreateOutputVideoDto
    {
        $this->validateAllIds($input);

        $this->builder->createEntity($input);

        try {
            $this->repository->insert($this->builder->getEntity());
            
            $this->storageFiles($input);
            $this->repository->updateMedia($this->builder->getEntity());

            $this->transaction->commit();

            return $this->output();
        } catch (Throwable $th) {
            $this->transaction->rollback();
            throw $th;
        }
    }

    private function output(): CreateOutputVideoDto
    {
        $entity = $this->builder->getEntity();

        return new CreateOutputVideoDto(
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
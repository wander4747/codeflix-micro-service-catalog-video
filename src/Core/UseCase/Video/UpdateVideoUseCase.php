<?php 

namespace Core\UseCase\Video;

use Core\Domain\Builder\Video\Builder;
use Core\Domain\Builder\Video\UpdateBuilderVideo;
use Core\UseCase\DTO\Video\Update\UpdateInputVideoDto;
use Core\UseCase\DTO\Video\Update\UpdateOutputVideoDto;

class UpdateVideoUseCase extends BaseVideoUseCase
{
    protected function getBuilder(): Builder {
        return new UpdateBuilderVideo();
	}
    
    public function execute(UpdateInputVideoDto $input): UpdateOutputVideoDto
    {
        $this->validateAllIds($input);

        $entity = $this->repository->findById($input->id);
        $entity->update(
            title: $input->title,
            description: $input->description,
        );
        
        $this->builder->setEntity($entity);
        $this->builder->addIds($input);

        try {
            $this->repository->update($this->builder->getEntity());
            
            $this->storageFiles($input);
            $this->repository->updateMedia($this->builder->getEntity());

            $this->transaction->commit();

            return $this->output();
        } catch (Throwable $th) {
            $this->transaction->rollback();
            throw $th;
        }
    }

    private function output(): UpdateOutputVideoDto
    {
        $entity = $this->builder->getEntity();

        return new UpdateOutputVideoDto(
            id: $entity->id(),
            title: $entity->title,
            description: $entity->description,
            yearLaunched: $entity->yearLaunched,
            duration: $entity->duration,
            opened: $entity->opened,
            rating: $entity->rating,
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
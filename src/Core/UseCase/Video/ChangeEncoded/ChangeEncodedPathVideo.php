<?php

namespace Core\UseCase\Video\ChangeEncoded;

use Core\Domain\Enum\MediaStatus;
use Core\Domain\Repository\VideoRepositoryInterface;
use Core\Domain\ValueObject\Media;
use Core\UseCase\Video\ChangeEncoded\DTO\ChangeEncodedVideoDTO;
use Core\UseCase\Video\ChangeEncoded\DTO\ChangeEncodedVideoOutputDTO;

class ChangeEncodedPathVideo
{
    public function __construct(
        protected VideoRepositoryInterface $repository
    )
    {
    }

    public function execute(ChangeEncodedVideoDTO $input): ChangeEncodedVideoOutputDTO
    {
        $entity = $this->repository->findById($input->id);

        $entity->setVideoFile(
            new Media(
                path: $entity->videoFile()?->path ?? '',
                status: MediaStatus::COMPLETED,
                encodedPath: $input->encodedPath
            )
        );

        $this->repository->updateMedia($entity);

        return new ChangeEncodedVideoOutputDTO(
            id: $entity->id(),
            encodedPath: $input->encodedPath
        );
    }
}

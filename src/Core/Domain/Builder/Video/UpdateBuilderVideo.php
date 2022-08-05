<?php

namespace Core\Domain\Builder\Video;

use Core\Domain\Entity\Video as Entity;
use DateTime;
use Core\Domain\ValueObject\Uuid;

class UpdateBuilderVideo extends BuilderVideo
{
    public function createEntity(object $input): Builder
    {
        $this->entity = new Entity(
            id: new Uuid($input->id),
            title: $input->title,
            description: $input->description,
            yearLaunched: $input->yearLaunched,
            duration: $input->duration,
            opened: true,
            rating: $input->rating,
            createdAt: new DateTime($input->createdAt),
        );

        foreach ($input->categories as $categoryId) {
            $this->entity->addCategory($categoryId);
        }
        
        foreach ($input->genres as $genreId) {
            $this->entity->addGenre($genreId);
        }

        foreach ($input->castMembers as $castMemberId) {
            $this->entity->addCastMember($castMemberId);
        }

        return $this;
    }

    public function setEntity(Entity $entity): Builder
    {
        $this->entity = $entity;
        return $this;
    }
}
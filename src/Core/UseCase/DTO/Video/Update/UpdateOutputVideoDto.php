<?php

namespace Core\UseCase\DTO\Video\Update;

use Core\Domain\Enum\Rating;

class UpdateOutputVideoDto
{
    public function __construct(
        public string $id,
        public string $title,
        public string $description,
        public string $yearLaunched,
        public string $duration,
        public string $opened,
        public Rating $rating,
        public array $categories = [],
        public array $genres = [],
        public array $castMembers = [],
        public ?string $videoFile = null,
        public ?string $thumbFile = null,
        public ?string $thumbHalf = null,
        public ?string $bannerFile = null,
        public ?string $trailerFile = null,
    ) {}
}
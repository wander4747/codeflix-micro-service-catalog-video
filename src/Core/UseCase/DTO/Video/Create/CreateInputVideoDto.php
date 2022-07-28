<?php

namespace Core\UseCase\DTO\Video\Create;

use Core\Domain\Enum\Rating;

class CreateInputVideoDto
{
    public function __construct(
        public string $title,
        public string $description,
        public string $yearLaunched,
        public string $duration,
        public string $opend,
        public Rating $rating,
        public array $categories,
        public array $genres,
        public array $castMembers,
        public ?array $videoFile = null,
        public ?array $thumbFile = null,
        public ?array $thumbHalf = null,
        public ?array $bannerFile = null,
        public ?array $trailerFile = null,
    ) {}
}
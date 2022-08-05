<?php

namespace Core\UseCase\DTO\Video\List;

use Core\Domain\Enum\Rating;

class ListInputVideoDto
{
    public function __construct(
        public string $id,
    ) {}
}
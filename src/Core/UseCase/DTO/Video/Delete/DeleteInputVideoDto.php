<?php

namespace Core\UseCase\DTO\Video\Delete;


class DeleteInputVideoDto
{
    public function __construct(
        public string $id,
    ) {}
}
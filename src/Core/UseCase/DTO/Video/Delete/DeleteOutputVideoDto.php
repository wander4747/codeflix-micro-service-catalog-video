<?php

namespace Core\UseCase\DTO\Video\Delete;

class DeleteOutputVideoDto
{
    public function __construct(
        public bool $success
    ) {}
}
<?php 

namespace Core\Domain\ValueObject;
use Core\Domain\Enum\MediaStatus;

class Media
{
    public function __construct(
        protected string $path,
        protected MediaStatus $status,
        protected string $encodedPath = ''
    )
    {

    }
    public function __get($property)
    {
        return $this->{$property};
    }
}
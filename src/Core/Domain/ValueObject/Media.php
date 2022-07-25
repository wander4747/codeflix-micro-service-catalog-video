<?php 

namespace Core\Domain\ValueObject;
use Core\Domain\Enum\MediaStatus;

class Media
{
    public function __construct(
        protected string $path,
        protected MediaStatus $status,
        protected ?string $encodedPath = null
    )
    {

    }
    public function __get($property)
    {
        if (isset($this->{$property})) {
            return $this->{$property};
        }

        $className = get_class($this);
        throw new \Exception("Property {$property} not found in class {$className}");
    }
}
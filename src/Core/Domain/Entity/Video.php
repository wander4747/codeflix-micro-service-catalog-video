<?php 

namespace Core\Domain\Entity;

use Core\Domain\Enum\Rating;
use Core\Domain\Exception\NotificationException;
use Core\Domain\Factory\VideoValidatorFactory;
use Core\Domain\ValueObject\Uuid;
use DateTime;
use Core\Domain\ValueObject\{
    Image,
    Media
};

class Video extends Entity {

    protected array $categoriesId = [];
    protected array $genresId = [];
    protected array $castMembersId = [];
    public function __construct(
        protected string $title,
        protected string $description,
        protected int $yearLaunched,
        protected int $duration,
        protected bool $opened,
        protected Rating $rating,
        protected ?Uuid $id = null,
        protected bool $published = false,
        protected ?DateTime $createdAt = null,
        protected ?Image $thumbFile = null,
        protected ?Image $thumbHalf = null,
        protected ?Image $bannerFile = null,
        protected ?Media $trailerFile = null,
        protected ?Media $videoFile = null,
    )
    {
        parent::__construct();

        $this->id = $this->id ?? Uuid::random();
        $this->createdAt = $this->createdAt ?? new DateTime();
        
        $this->validation();
    }

    public function addCategory(string $categoryId) 
    {
        array_push($this->categoriesId, $categoryId);
    }
    public function removeCategory(string $categoryId)
    {
        unset($this->categoriesId[array_search($categoryId, $this->categoriesId)]);
    }

    public function addGenre(string $genreId) 
    {
        array_push($this->genresId, $genreId);
    }

    public function removeGenre(string $genreId)
    {
        unset($this->genresId[array_search($genreId, $this->genresId)]);
    }

    public function addCastMember(string $castMemberId) 
    {
        array_push($this->castMembersId, $castMemberId);
    }

    public function removeCastMember(string $castMemberId)
    {
        unset($this->castMembersId[array_search($castMemberId, $this->castMembersId)]);
    }
    public function thumbFile(): ?Image
    {
        return $this->thumbFile;
    }
    public function setThumbFile(Image $thumbFile): void
    {
        $this->thumbFile = $thumbFile;
    }
    public function bannerFile(): ?Image
    {
        return $this->bannerFile;
    }
    public function setBannerFile(Image $bannerFile): void
    {
        $this->bannerFile = $bannerFile;
    }
    public function thumbHalf(): ?Image
    {
        return $this->thumbHalf;
    }
    public function setThumbHalf(Image $thumbHalf): void
    {
        $this->thumbHalf = $thumbHalf;
    }
    public function trailerFile(): ?Media
    {
        return $this->trailerFile;
    }
    public function setTrailerFile(Media $trailerFile): void
    {
        $this->trailerFile = $trailerFile;
    }
    public function videoFile(): ?Media
    {
        return $this->videoFile;
    }
    public function setVideoFile(Media $videoFile): void
    {
        $this->videoFile = $videoFile;
    }
    protected function validation()
    {
        VideoValidatorFactory::create()->validate($this);

        if ($this->notification->hasErrors()) {
            throw new NotificationException($this->notification->messages('video'));
        }
    }
}

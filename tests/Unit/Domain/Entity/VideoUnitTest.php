<?php 

namespace Tests\Unit\Domain\Entity;

use Core\Domain\Entity\Video;
use PHPUnit\Framework\TestCase;
use Core\Domain\ValueObject\Uuid;
use DateTime;
use Ramsey\Uuid\Uuid as RamseyUuid;
use Core\Domain\Enum\{
    Rating,
    MediaStatus
};
use Core\Domain\Exception\EntityValidationException;
use Core\Domain\Exception\NotificationException;
use Core\Domain\ValueObject\{
    Image,
    Media
};

class VideoUnitTest extends TestCase {
    public function testAttributes()
    {
        $uuid = (string) RamseyUuid::uuid4();
        $date = date('Y-m-d H:i:s');

        $video = new Video(
            id: new Uuid($uuid),
            title: 'New Video',
            description: 'New Description',
            yearLaunched: 2021,
            duration: 12,
            opened: true,
            rating: Rating::RATE12,
            published: false,
            createdAt: new DateTime($date),
        );

        $this->assertEquals($uuid, $video->id());
        $this->assertEquals('New Video', $video->title);
        $this->assertEquals('New Description', $video->description);
        $this->assertEquals(2021, $video->yearLaunched);
        $this->assertEquals($date, $video->createdAt());
    }

    public function testIdAndCreatedAt()
    {
        $video = new Video(
            title: 'New Video',
            description: 'New Description',
            yearLaunched: 2021,
            duration: 12,
            opened: true,
            rating: Rating::RATE12,
            published: false,
        );

        $this->assertNotEmpty($video->id());
        $this->assertNotEmpty($video->createdAt());
    }

    public function testAddCategoryId()
    {
        $categoryId = (string) RamseyUuid::uuid4();

        $video = new Video(
            title: 'New Video',
            description: 'New Description',
            yearLaunched: 2021,
            duration: 12,
            opened: true,
            rating: Rating::RATE12,
            published: false,
        );

        $video->addCategory(
            categoryId: $categoryId
        );

        $this->assertCount(1, $video->categoriesId);
    }

    public function testRemoveCategoryId()
    {
        $categoryId = (string) RamseyUuid::uuid4();

        $video = new Video(
            title: 'New Video',
            description: 'New Description',
            yearLaunched: 2021,
            duration: 12,
            opened: true,
            rating: Rating::RATE12,
            published: false,
        );

        $video->addCategory(
            categoryId: $categoryId
        );

        $this->assertCount(1, $video->categoriesId);

        $video->removeCategory(
            categoryId: $categoryId
        );

        $this->assertCount(0, $video->categoriesId);
    }

    public function testAddGenreId()
    {
        $genreId = (string) RamseyUuid::uuid4();

        $video = new Video(
            title: 'New Video',
            description: 'New Description',
            yearLaunched: 2021,
            duration: 12,
            opened: true,
            rating: Rating::RATE12,
            published: false,
        );
        
        $video->addGenre(
            genreId: $genreId
        );

        $this->assertCount(1, $video->genresId);
    }

    public function testRemoveGenreId()
    {
        $genreId = (string) RamseyUuid::uuid4();

        $video = new Video(
            title: 'New Video',
            description: 'New Description',
            yearLaunched: 2021,
            duration: 12,
            opened: true,
            rating: Rating::RATE12,
            published: false,
        );

        $video->addGenre(
            genreId: $genreId
        );

        $this->assertCount(1, $video->genresId);

        $video->removeGenre(
           genreId: $genreId,
        );

        $this->assertCount(0, $video->genresId);
    }
    public function testAddCastMember()
    {
        $castMemberId = (string) RamseyUuid::uuid4();

        $video = new Video(
            title: 'New Video',
            description: 'New Description',
            yearLaunched: 2021,
            duration: 12,
            opened: true,
            rating: Rating::RATE12,
            published: false,
        );
        
        $video->addCastMember(
            castMemberId: $castMemberId
        );

        $this->assertCount(1, $video->castMembersId);
    }
    public function testRemoveCastMemberId()
    {
        $castMemberId = (string) RamseyUuid::uuid4();

        $video = new Video(
            title: 'New Video',
            description: 'New Description',
            yearLaunched: 2021,
            duration: 12,
            opened: true,
            rating: Rating::RATE12,
            published: false,
        );

        $video->addCastMember(
            castMemberId: $castMemberId
        );

        $this->assertCount(1, $video->castMembersId);

        $video->removeCastMember(
            castMemberId: $castMemberId,
        );

        $this->assertCount(0, $video->castMembersId);
    }
    public function testValueObjectImage()
    {
        $video = new Video(
            title: 'New Video',
            description: 'New Description',
            yearLaunched: 2021,
            duration: 12,
            opened: true,
            rating: Rating::RATE12,
            thumbFile: new Image(
                path: "path/image.png"
            ),
        );

        $this->assertNotNull($video->thumbFile());
        $this->assertInstanceOf(Image::class, $video->thumbFile());
        $this->assertEquals("path/image.png", $video->thumbFile->path());
    }
    public function testValueObjectImageToThumbHalf()
    {
        $video = new Video(
            title: 'New Video',
            description: 'New Description',
            yearLaunched: 2021,
            duration: 12,
            opened: true,
            rating: Rating::RATE12,
            thumbHalf: new Image(
                path: "path/image.png"
            ),
        );

        $this->assertNotNull($video->thumbHalf());
        $this->assertInstanceOf(Image::class, $video->thumbHalf());
        $this->assertEquals("path/image.png", $video->thumbHalf->path());
    }

    public function testValueObjectImageToBanner()
    {
        $video = new Video(
            title: 'New Video',
            description: 'New Description',
            yearLaunched: 2021,
            duration: 12,
            opened: true,
            rating: Rating::RATE12,
            bannerFile: new Image(
                path: "path/image.png"
            ),
        );

        $this->assertNotNull($video->bannerFile());
        $this->assertInstanceOf(Image::class, $video->bannerFile());
        $this->assertEquals("path/image.png", $video->bannerFile()->path());
    }

    public function testValueObjectMediaTrailer()
    {
        $trailer = new Media(
            path: "path/video.mp4",
            status: MediaStatus::PENDING,
            encodedPath: "path/video.extension",
        );

        $video = new Video(
            title: 'New Video',
            description: 'New Description',
            yearLaunched: 2021,
            duration: 12,
            opened: true,
            rating: Rating::RATE12,
            trailerFile: $trailer,
        );

        $this->assertNotNull($video->trailerFile());
        $this->assertInstanceOf(Media::class, $video->trailerFile());
        $this->assertEquals("path/video.mp4", $video->trailerFile->path);
    }

    public function testValueObjectMediaVideo()
    {
        $videoFile = new Media(
            path: "path/video.mp4",
            status: MediaStatus::PENDING,
        );

        $video = new Video(
            title: 'New Video',
            description: 'New Description',
            yearLaunched: 2021,
            duration: 12,
            opened: true,
            rating: Rating::RATE12,
            videoFile: $videoFile,
        );

        $this->assertNotNull($video->videoFile());
        $this->assertInstanceOf(Media::class, $video->videoFile());
        $this->assertEquals("path/video.mp4", $video->videoFile->path);
    }

    public function testExceptions()
    {
        $this->expectException(NotificationException::class);

        new Video(
            title: 'Ne',
            description: 'Ne',
            yearLaunched: 2021,
            duration: 12,
            opened: true,
            rating: Rating::RATE12,
        );
    }
}
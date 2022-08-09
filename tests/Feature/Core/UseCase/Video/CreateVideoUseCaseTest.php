<?php

namespace Tests\Feature\Core\UseCase\Video;

use Core\Domain\Enum\Rating;
use Core\Domain\Repository\{
    CastMemberRepositoryInterface,
    CategoryRepositoryInterface,
    GenreRepositoryInterface,
    VideoRepositoryInterface
};
use App\Models\{
    CastMember,
    Category,
    Genre
};
use Core\UseCase\DTO\Video\Create\CreateInputVideoDto;
use Core\UseCase\Interfaces\FileStorageInterface;
use Core\UseCase\Interfaces\TransactionInterface;
use Core\UseCase\Video\CreateVideoUseCase;
use Core\UseCase\Video\Interfaces\VideoEventManagerInterface;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class CreateVideoUseCaseTest  extends TestCase
{
    public function testCreate()
    {
        $useCase = new CreateVideoUseCase(
            $this->app->make(VideoRepositoryInterface::class),
            $this->app->make(TransactionInterface::class),
            $this->app->make(FileStorageInterface::class),
            $this->app->make(VideoEventManagerInterface::class),
            $this->app->make(CategoryRepositoryInterface::class),
            $this->app->make(GenreRepositoryInterface::class),
            $this->app->make(CastMemberRepositoryInterface::class)
        );

        $categories = Category::factory()->count(3)->create()->pluck('id')->toArray();
        $genres = Genre::factory()->count(3)->create()->pluck('id')->toArray();
        $castMembers = CastMember::factory()->count(3)->create()->pluck('id')->toArray();

        $fakeFile = UploadedFile::fake()->create("video.mp4", 1, 'video/mp4');
        $file = [
            'tmp_name' => $fakeFile->getPathname(),
            'name' => $fakeFile->getFilename(),
            'type' => $fakeFile->getMimeType(),
            'error' => $fakeFile->getError(),
        ];

        $input = new CreateInputVideoDto(
            title: 'test',
            description: 'test',
            yearLaunched: 2020,
            duration: 120,
            opened: true,
            rating: Rating::L,
            categories: $categories,
            genres: $genres,
            castMembers: $castMembers,
            videoFile: $file,
        );

        $response = $useCase->execute($input);
        
        $this->assertEquals($input->title, $response->title);
        $this->assertEquals($input->description, $response->description);
        $this->assertEquals($input->yearLaunched, $response->yearLaunched);
        $this->assertEquals($input->duration, $response->duration);
        $this->assertEquals($input->opened, $response->opened);
        $this->assertEquals($input->rating, $response->rating);
        $this->assertEquals($input->categories, $response->categories);
        $this->assertEquals($input->genres, $response->genres);
        $this->assertEquals($input->castMembers, $response->castMembers);

        $this->assertNotNull($input->videoFile);
        $this->assertNull($input->trailerFile);
        $this->assertNull($input->bannerFile);
        $this->assertNull($input->thumbFile);
        $this->assertNull($input->thumbHalf);
    }
}
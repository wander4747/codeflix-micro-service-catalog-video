<?php

namespace Tests\Unit\UseCase\Video;

use Core\Domain\Entity\Video;
use Core\Domain\Enum\Rating;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\{
    CastMemberRepositoryInterface,
    CategoryRepositoryInterface,
    GenreRepositoryInterface,
    VideoRepositoryInterface
};
use Core\UseCase\DTO\Video\Create\{
    CreateInputVideoDto,
    CreateOutputVideoDto
};
use Core\UseCase\Interfaces\{
    FileStorageInterface,
    TransactionInterface
};
use PHPUnit\Framework\TestCase;
use Core\UseCase\Video\CreateVideoUseCase as UseCase;
use Core\UseCase\Video\Interfaces\VideoEventManagerInterface;
use Mockery;

class CreateVideoUseCaseUnitTest extends TestCase
{

    protected $useCase;
    protected function setUp(): void
    {
        $this->useCase = new UseCase(
            repository: $this->createMockRepository(),
            transaction: $this->createMockTransaction(),
            storage: $this->createMockFileStorage(),
            eventManager: $this->createMockEventManager(),
            repositoryCategory: $this->createMockRepositoryCategory(),
            repositoryGenre: $this->createMockRepositoryGenre(),
            repositoryCastMember: $this->createMockRepositoryCastMember(),
        );

        parent::setUp();
    }

    public function testExecInputOutput()
    {
        $response = $this->useCase->execute(
            input: $this->createMockInputDTO()
        );
        
        $this->assertInstanceOf(CreateOutputVideoDto::class, $response);
        $this->assertTrue(true);
    }

    /**
     * @dataProvider dataProviderIds
     */
    public function testExceptionCategoriesIds(
        string $label,
        array $ids,
    )
    {
        $this->expectException(NotFoundException::class);
        $this->expectErrorMessage(sprintf('%s %s not found', $label, implode(', ', $ids)));
        $response = $this->useCase->execute(
            input: $this->createMockInputDTO(
                categoriesIds: $ids
            )
        );
        
        $this->assertInstanceOf(CreateOutputVideoDto::class, $response);
    }
    public function testExceptionMessageCategoriesIds()
    {
        $this->expectException(NotFoundException::class);
        $this->expectErrorMessage('Categories uuid1, uuid2 not found');
        $response = $this->useCase->execute(
            input: $this->createMockInputDTO(
                categoriesIds: ['uuid1', 'uuid2']
            )
        );
        
        $this->assertInstanceOf(CreateOutputVideoDto::class, $response);
    }
    public function dataProviderIds(): array
    {
        return [
            ['Category', ['uuid-1']],
            ['Categories', ['uuid-1', 'uuid-2']],
            ['Categories', ['uuid-1', 'uuid-2', 'uuid-3']],
        ];
    }
    /**
     * @dataProvider dataProviderFiles
     */
    public function testUploadFile(
        array $video,
        array $trailer,
        array $thumb,
        array $banner,
        array $thumbHalf,
    )
    {
        $response = $this->useCase->execute(
            input: $this->createMockInputDTO(
                videoFile: $video['value'],
                trailerFile: $trailer['value'],
                thumbFile: $thumb['value'],
                bannerFile: $banner['value'],
                thumbHalf: $thumbHalf['value'],
            )
        );

        $this->assertEquals($response->videoFile, $video['expected']);
        $this->assertEquals($response->trailerFile, $trailer['expected']);
        $this->assertEquals($response->thumbFile, $thumb['expected']);
        $this->assertEquals($response->bannerFile, $banner['expected']);
        $this->assertEquals($response->thumbHalf, $thumbHalf['expected']);
    }
    public function dataProviderFiles(): array
    {
        return [
            [
                'video' => ['value' => ['tmp' => 'tmp/file.mp4'], 'expected' => 'path/file.png'],
                'trailer' => ['value' => ['tmp' => 'tmp/file.mp4'], 'expected' => 'path/file.png'],
                'thumb' => ['value' => ['tmp' => 'tmp/file.mp4'], 'expected' => 'path/file.png'],
                'banner' => ['value' => ['tmp' => 'tmp/file.mp4'], 'expected' => 'path/file.png'],
                'thumbHalf' => ['value' => ['tmp' => 'tmp/file.mp4'], 'expected' => 'path/file.png'],
            ],
            [
                'video' => ['value' => ['tmp' => 'tmp/file.mp4'], 'expected' => 'path/file.png'],
                'trailer' => ['value' => null, 'expected' => null],
                'thumb' => ['value' => ['tmp' => 'tmp/file.mp4'], 'expected' => 'path/file.png'],
                'banner' => ['value' => ['tmp' => 'tmp/file.mp4'], 'expected' => 'path/file.png'],
                'thumbHalf' => ['value' => null, 'expected' => null],
            ],
            [
                'video' => ['value' => null, 'expected' => null],
                'trailer' => ['value' => null, 'expected' => null],
                'thumb' => ['value' => null, 'expected' => null],
                'banner' => ['value' => null, 'expected' => null],
                'thumbHalf' => ['value' => null, 'expected' => null],
            ],
        ];
    }
    protected function createMockRepository()
    {
        $mock = Mockery::mock(stdClass::class, VideoRepositoryInterface::class);
        $mock->shouldReceive('insert')->andReturn($this->createMockEntity());
        $mock->shouldReceive('updateMedia');
        return $mock;
    }

    protected function createMockRepositoryCategory(array $categoriesResponse = [])
    {
        $mock = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        $mock->shouldReceive('getIdsListIds')->andReturn($categoriesResponse);
        return $mock;
    }

    protected function createMockRepositoryGenre(array $genresResponse = [])
    {
        $mock = Mockery::mock(stdClass::class, GenreRepositoryInterface::class);
        $mock->shouldReceive('getIdsListIds')->andReturn($genresResponse);
        return $mock;
    }

    protected function createMockRepositoryCastMember(array $castMemberResponse = [])
    {
        $mock = Mockery::mock(stdClass::class, CastMemberRepositoryInterface::class);
        $mock->shouldReceive('getIdsListIds')->andReturn($castMemberResponse);
        return $mock;
    }

    protected function createMockTransaction()
    {
        $mock = Mockery::mock(stdClass::class, TransactionInterface::class);
        $mock->shouldReceive('commit');
        $mock->shouldReceive('rollback');

        return $mock;
    }

    protected function createMockFileStorage()
    {
        $mock =  Mockery::mock(stdClass::class, FileStorageInterface::class);
        $mock->shouldReceive('store')->andReturn('path/file.png');
        return $mock;
    }

    protected function createMockEventManager()
    {
        $mock = Mockery::mock(stdClass::class, VideoEventManagerInterface::class);
        $mock->shouldReceive('dispatch');
        return $mock;
    }

    protected function createMockInputDTO(
        array $categoriesIds = [], 
        array $genresIds = [], 
        array $castMembersIds = [],
        ?array $videoFile = null,
        ?array $thumbFile = null,
        ?array $thumbHalf = null,
        ?array $bannerFile = null,
        ?array $trailerFile = null)
    {
        return Mockery::mock(CreateInputVideoDto::class, [
            'title',
            'description',
            2022,
            10,
            true,
            Rating::RATE10,
            $categoriesIds,
            $genresIds,
            $castMembersIds,
            $videoFile,
            $thumbFile,
            $thumbHalf,
            $bannerFile,
            $trailerFile
        ]);
    }

    protected function createMockEntity()
    {
        return Mockery::mock(Video::class, [
            'title',
            'description',
            2022,
            10,
            true,
            Rating::RATE10
        ]);
    }
}

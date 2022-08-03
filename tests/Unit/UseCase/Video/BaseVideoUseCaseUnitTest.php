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
use Core\UseCase\Interfaces\{
    FileStorageInterface,
    TransactionInterface
};
use PHPUnit\Framework\TestCase;
use Core\UseCase\Video\Interfaces\VideoEventManagerInterface;
use Mockery;

abstract class BaseVideoUseCaseUnitTest extends TestCase
{
    protected $useCase;

    abstract protected function createMockInputDTO(
        array $categoriesIds = [], 
        array $genresIds = [], 
        array $castMembersIds = [],
        ?array $videoFile = null,
        ?array $thumbFile = null,
        ?array $thumbHalf = null,
        ?array $bannerFile = null,
        ?array $trailerFile = null
    );

    abstract protected function nameActionRepository(): string;
    abstract protected function getUseCase(): string;

    protected function createUseCase(
        int $timesCallMethodActionRepository = 1,
        int $timesCallMethodUpdateMediaRepository = 1,
        int $timesCallCommit = 1,
        int $timesCallRollBack = 0,
        int $timesCallMethodFileStorage = 0,
        int $timesCallMethodDispatch = 0
    )
    {
        $this->useCase = new ($this->getUseCase())(
            repository: $this->createMockRepository(
                timesCallAction: $timesCallMethodActionRepository,
                timesCallUpdateMedia:$timesCallMethodUpdateMediaRepository
            ),
            transaction: $this->createMockTransaction(
                timesCallCommit: $timesCallCommit,
                timesCallRollBack: $timesCallRollBack,
            ),
            storage: $this->createMockFileStorage(
                timesCall: $timesCallMethodFileStorage
            ),
            eventManager: $this->createMockEventManager(
                times: $timesCallMethodDispatch
            ),
            repositoryCategory: $this->createMockRepositoryCategory(),
            repositoryGenre: $this->createMockRepositoryGenre(),
            repositoryCastMember: $this->createMockRepositoryCastMember(),
        );
    }

    /**
     * @dataProvider dataProviderIds
     */
    public function testExceptionCategoriesIds(
        string $label,
        array $ids,
    )
    {
        $this->createUseCase(
            timesCallMethodActionRepository: 0,
            timesCallMethodUpdateMediaRepository: 0,
            timesCallCommit: 0,
        );
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
        $this->createUseCase(
            timesCallMethodActionRepository: 0,
            timesCallMethodUpdateMediaRepository: 0,
            timesCallCommit: 0,
        );
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
        int $timesStore,
        int $timesDispatch = 0
    )
    {
        $this->createUseCase(
            timesCallMethodFileStorage: $timesStore,
            timesCallMethodDispatch: $timesDispatch
        );

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
                'timesStorage' => 5,
                'timesDispatch' => 2
            ],
            [
                'video' => ['value' => ['tmp' => 'tmp/file.mp4'], 'expected' => 'path/file.png'],
                'trailer' => ['value' => null, 'expected' => null],
                'thumb' => ['value' => ['tmp' => 'tmp/file.mp4'], 'expected' => 'path/file.png'],
                'banner' => ['value' => ['tmp' => 'tmp/file.mp4'], 'expected' => 'path/file.png'],
                'thumbHalf' => ['value' => null, 'expected' => null],
                'timesStorage' => 3,
                'timesDispatch' => 1
            ],
            [
                'video' => ['value' => null, 'expected' => null],
                'trailer' => ['value' => null, 'expected' => null],
                'thumb' => ['value' => null, 'expected' => null],
                'banner' => ['value' => null, 'expected' => null],
                'thumbHalf' => ['value' => null, 'expected' => null],
                'timesStorage' => 0,
                'timesDispatch' => 0
            ],
        ];
    }
    protected function createMockRepository(
        int $timesCallAction,
        int $timesCallUpdateMedia,
    )
    {
        $mock = Mockery::mock(stdClass::class, VideoRepositoryInterface::class);
        $mock->shouldReceive($this->nameActionRepository())
                ->times($timesCallAction)        
                ->andReturn($this->createMockEntity());
        $mock->shouldReceive('updateMedia')->times($timesCallUpdateMedia)        ;
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

    protected function createMockTransaction(
        int $timesCallCommit,
        int $timesCallRollBack,
    )
    {
        $mock = Mockery::mock(stdClass::class, TransactionInterface::class);
        $mock->shouldReceive('commit')->times($timesCallCommit);
        $mock->shouldReceive('rollback')->times($timesCallRollBack);

        return $mock;
    }

    protected function createMockFileStorage(
        int $timesCall,
    )
    {
        $mock =  Mockery::mock(stdClass::class, FileStorageInterface::class);
        $mock->shouldReceive('store')->times($timesCall)->andReturn('path/file.png');
        return $mock;
    }

    protected function createMockEventManager(int $times)
    {
        $mock = Mockery::mock(stdClass::class, VideoEventManagerInterface::class);
        $mock->shouldReceive('dispatch')->times($times);
        return $mock;
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

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}

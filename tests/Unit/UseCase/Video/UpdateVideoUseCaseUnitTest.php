<?php

namespace Tests\Unit\UseCase\Video;

use Core\Domain\ValueObject\Uuid;
use Core\UseCase\DTO\Video\Update\UpdateInputVideoDto;
use Core\UseCase\DTO\Video\Update\UpdateOutputVideoDto;
use Core\UseCase\Video\UpdateVideoUseCase;
use Mockery;

class UpdateVideoUseCaseUnitTest extends BaseVideoUseCaseUnitTest
{
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
        return Mockery::mock(UpdateInputVideoDto::class, [
            Uuid::random(),
            'title',
            'description',
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
	
	function nameActionRepository(): string {
        return "update";
	}
	
	function getUseCase(): string {
        return UpdateVideoUseCase::class;
	}

    public function testExecInputOutput()
    {
        $this->createUseCase();
        $response = $this->useCase->execute(
            input: $this->createMockInputDTO()
        );
        
        $this->assertInstanceOf(UpdateOutputVideoDto::class, $response);
        $this->assertTrue(true);
    }
}

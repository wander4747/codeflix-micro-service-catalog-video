<?php

namespace Tests\Unit\UseCase\Video;

use Core\Domain\Enum\Rating;
use Core\UseCase\DTO\Video\Create\{
    CreateInputVideoDto,
};
use Core\UseCase\Video\CreateVideoUseCase;
use Mockery;

class CreateVideoUseCaseUnitTest extends BaseVideoUseCaseUnitTest
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
	
	function nameActionRepository(): string {
        return "insert";
	}
	
	function getUseCase(): string {
        return CreateVideoUseCase::class;
	}
}

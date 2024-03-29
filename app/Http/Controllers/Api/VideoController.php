<?php

namespace App\Http\Controllers\Api;

use App\Adapters\ApiAdapter;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVideoRequest;
use App\Http\Requests\UpdateVideoRequest;
use Core\Domain\Enum\Rating;
use Core\UseCase\DTO\Video\Create\CreateInputVideoDto;
use Core\UseCase\DTO\Video\Delete\DeleteInputVideoDto;
use Core\UseCase\DTO\Video\List\ListInputVideoDto;
use Core\UseCase\DTO\Video\Paginate\PaginateInputVideoDto;
use Core\UseCase\DTO\Video\Update\UpdateInputVideoDto;
use Core\UseCase\Video\CreateVideoUseCase;
use Core\UseCase\Video\DeleteVideoUseCase;
use Core\UseCase\Video\ListVideosUseCase;
use Core\UseCase\Video\ListVideoUseCase;
use Core\UseCase\Video\UpdateVideoUseCase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VideoController extends Controller
{
    public function index(Request $request, ListVideosUseCase $useCase)
    {
        $response = $useCase->execute(
            input: new PaginateInputVideoDto(
                filter: $request->filter ?? '',
                order: $request->get('order', 'DESC'),
                page: (int) $request->get('page', 1),
                totalPage: (int) $request->get('per_page', 15)
            )
        );

        return (new ApiAdapter($response))->toJson();
    }

    public function show(ListVideoUseCase $useCase, $id)
    {
        $response = $useCase->execute(new ListInputVideoDto($id));

        return ApiAdapter::json($response);
    }

    public function store(CreateVideoUseCase $useCase, StoreVideoRequest $request)
    {
        $response = $useCase->execute(new CreateInputVideoDto(
            title: $request->title,
            description: $request->description,
            yearLaunched: $request->year_launched,
            duration: $request->duration,
            opened: $request->opened,
            rating: Rating::from($request->rating),
            categories: $request->categories,
            genres: $request->genres,
            castMembers: $request->cast_members,
            videoFile: getArrayFile($request->file('video_file')),
            trailerFile: getArrayFile($request->file('trailer_file')),
            bannerFile: getArrayFile($request->file('banner_file')),
            thumbFile: getArrayFile($request->file('thumb_file')),
            thumbHalf: getArrayFile($request->file('thumb_half')),
        ));

        return ApiAdapter::json($response, Response::HTTP_CREATED);
    }

    public function update(UpdateVideoUseCase $useCase, UpdateVideoRequest $request, $id)
    {
        $response = $useCase->execute(new UpdateInputVideoDto(
            id: $id,
            title: $request->title,
            description: $request->description,
            categories: $request->categories,
            genres: $request->genres,
            castMembers: $request->cast_members,
            videoFile: getArrayFile($request->file('video_file')),
            trailerFile: getArrayFile($request->file('trailer_file')),
            bannerFile: getArrayFile($request->file('banner_file')),
            thumbFile: getArrayFile($request->file('thumb_file')),
            thumbHalf: getArrayFile($request->file('thumb_half_file')),
        ));

        return ApiAdapter::json($response);
    }

    public function destroy(DeleteVideoUseCase $useCase, $id)
    {
        $useCase->execute(new DeleteInputVideoDto(id: $id));

        return response()->noContent();
    }
}

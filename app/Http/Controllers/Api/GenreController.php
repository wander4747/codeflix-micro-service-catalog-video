<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGenreRequest;
use App\Http\Requests\UpdateGenreRequest;
use App\Http\Resources\GenreResource;
use Core\UseCase\DTO\Genre\Create\GenreCreateInputDto;
use Core\UseCase\DTO\Genre\GenreInputDto;
use Core\UseCase\DTO\Genre\List\ListGenresInputDto;
use Core\UseCase\DTO\Genre\Update\GenreUpdateInputDto;
use Core\UseCase\Genre\CreateGenreUseCase;
use Core\UseCase\Genre\DeleteGenreUseCase;
use Core\UseCase\Genre\ListGenresUseCase;
use Core\UseCase\Genre\ListGenreUseCase;
use Core\UseCase\Genre\UpdateGenreUseCase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GenreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, ListGenresUseCase $useCase)
    {
        $response = $useCase->execute(
            input: new ListGenresInputDto(
                filter: $request->get('filter', ''),
                order: $request->get('order', 'DESC'),
                page: (int) $request->get('page', 1),
                totalPage: (int) $request->get('total_page', 15),
            )
        );

        return GenreResource::collection(collect($response->items))
                            ->additional([
                                'meta' => [
                                    'total' => $response->total,
                                    'current_page' => $response->current_page,
                                    'last_page' => $response->last_page,
                                    'first_page' => $response->first_page,
                                    'per_page' => $response->per_page,
                                    'to' => $response->to,
                                    'from' => $response->from,
                                ]
                            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCategoryRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreGenreRequest $request, CreateGenreUseCase $useCase)
    {
        $response = $useCase->execute(
            input: new GenreCreateInputDto(
                name: $request->name,
                isActive: (bool) $request->is_active,
                categoriesId: $request->categories_ids
            )
        );

        return (new GenreResource($response))
                    ->response()
                    ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(ListGenreUseCase $useCase, $id)
    {
        $response = $useCase->execute(
            input: new GenreInputDto(
                id: $id
            )
        );

        return new GenreResource($response);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCategoryRequest $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateGenreRequest $request, UpdateGenreUseCase $useCase, $id)
    {
        $response = $useCase->execute(
            input: new GenreUpdateInputDto(
                id: $id,
                name: $request->name,
                categoriesId: $request->categories_ids
            )
        );

        return new GenreResource($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteGenreUseCase $useCase, $id)
    {
        $useCase->execute(new GenreInputDto($id));

        return response()->noContent();
    }
}

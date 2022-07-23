<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\Genre as Model;
use App\Models\Category as ModelCategory;
use App\Models\Genre;
use Illuminate\Http\Response;

class GenreApiTest extends TestCase
{
    protected $endpoint = '/api/genres';

    public function testListEmptyGenres()
    {
        $response = $this->getJson($this->endpoint);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(0, 'data');
    }

    public function testListAllGenre()
    {
        Genre::factory()->count(30)->create();

        $response = $this->getJson($this->endpoint);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'meta' => [
                "total",
                "current_page",
                "last_page",
                "first_page",
                "per_page",
                "to",
                "from"
            ]
        ]);

        $response->assertJsonCount(15, 'data');
    }

    public function testStore()
    {
        $categories = ModelCategory::factory()->count(10)->create();

        $response = $this->postJson($this->endpoint, [
            'name' => 'New genre',
            'is_active' => true,
            'categories_ids' => $categories->pluck('id')->toArray(),
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'is_active'
            ]
        ]);
    }

    public function testValidationsStore()
    {
        $categories = ModelCategory::factory()->count(2)->create();

        $payload = [
            'name' => '',
            'is_active' => true,
            'categories_ids' => $categories->pluck('id')->toArray(),
        ];

        $response = $this->postJson($this->endpoint, $payload);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'name'
            ]
        ]);
    }

    public function testShowNotFound()
    {
        $response = $this->getJson("{$this->endpoint}/fake_id");

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testShow()
    {
        $genre = Model::factory()->create();

        $response = $this->getJson("{$this->endpoint}/{$genre->id}");

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'is_active'
            ]
        ]);
    }

    public function testUpdateNotFound()
    {
        $categories = ModelCategory::factory()->count(10)->create();

        $response = $this->putJson("{$this->endpoint}/fake_id", [
            'name' => 'New Name to Update',
            'categories_ids' => $categories->pluck('id')->toArray()
        ]);

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testValidationsUpdate()
    {
        $response = $this->putJson("{$this->endpoint}/fake_value", [
            'name' => 'New Name to Update',
            'categories_ids' => []
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'categories_ids'
            ]
        ]);
    }

    public function testUpdate()
    {
        $genre = Model::factory()->create();
        $categories = ModelCategory::factory()->count(10)->create();

        $response = $this->putJson("{$this->endpoint}/{$genre->id}", [
            'name' => 'New Name to Update',
            'categories_ids' => $categories->pluck('id')->toArray()
        ]);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'is_active'
            ]
        ]);
    }

    public function testDeleteNotFound()
    {
        $response = $this->deleteJson("{$this->endpoint}/fake_id");

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testDelete()
    {
        $genre = Model::factory()->create();

        $response = $this->deleteJson("{$this->endpoint}/{$genre->id}");

        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }
}

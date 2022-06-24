<?php

namespace Tests\Feature\Api;

use Tests\TestCase;

use App\Models\Category;
use Illuminate\Http\Response;

class CategoryApiTest extends TestCase
{
    protected $endpoint = '/api/categories';

    public function testListEmptyCategories()
    {
        $response = $this->getJson($this->endpoint);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(0, 'data');
    }

    public function testListAllCategories()
    {
        Category::factory()->count(30)->create();

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

    public function testListPaginateCategories()
    {
        Category::factory()->count(25)->create();

        $response = $this->getJson("{$this->endpoint}?page=2");
        $response->assertStatus(Response::HTTP_OK);

        $this->assertEquals(2, $response['meta']['current_page']);
        $this->assertEquals(25, $response['meta']['total']);

        $response->assertJsonCount(10, 'data');
    }

    public function testListCategoryNotFound()
    {
        $response = $this->getJson("{$this->endpoint}/fake_value");
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testListCategoryById()
    {
        $category = Category::factory()->create();
        $response = $this->getJson("{$this->endpoint}/$category->id");
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => [
                "id",
                "name",
                "description",
                "is_active",
                "created_at",
            ]
        ]);

        $this->assertEquals($category->id, $response['data']['id']);
    }

    public function testValidationsStore()
    {
        $data = [];

        $response = $this->postJson($this->endpoint, $data);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'name'
            ]
        ]);
    }

    public function testStore()
    {
        $data = [
            'name' => 'Test Store',
        ];

        $response = $this->postJson($this->endpoint, $data);
        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'description',
                'is_active',
                'created_at',
            ]
        ]);

        $this->assertEquals('Test Store', $response['data']['name']);
        $this->assertDatabaseHas('categories', [
            'id' => $response['data']['id'],
            'name' => $response['data']['name']
        ]);
    }

    public function testNotFoundUpdate()
    {
        $data = [
            'name' => 'Test update',
        ];

        $response = $this->putJson("{$this->endpoint}/{fake_id}", $data);
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testValidationsUpdate()
    {
        $data = [];

        $response = $this->putJson("{$this->endpoint}/{fake_id}", $data);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'name'
            ]
        ]);
    }

    public function testUpdate()
    {
        $category = Category::factory()->create();

        $data = [
            'name' => 'Test update',
        ];

        $response = $this->putJson("{$this->endpoint}/{$category->id}", $data);
        $response->assertStatus(Response::HTTP_OK);
        $this->assertEquals('Test update', $response['data']['name']);
        $this->assertDatabaseHas('categories', [
            'name' => $response['data']['name']
        ]);
    }

    public function testNotFoundDelete()
    {
        $response = $this->deleteJson("{$this->endpoint}/{faker_id}");
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testDelete()
    {
        $category = Category::factory()->create();

        $response = $this->deleteJson("{$this->endpoint}/{$category->id}");
        $response->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertSoftDeleted('categories', [
            'id' => $category->id
        ]);
    }
}

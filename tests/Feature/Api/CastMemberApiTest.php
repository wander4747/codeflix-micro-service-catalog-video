<?php

namespace Tests\Feature\Api;

use App\Models\CastMember;
use Illuminate\Http\Response;
use Tests\TestCase;

class CastMemberApiTest extends TestCase
{
    private $endpoint = '/api/cast_members';

    public function testGetAllEmpty()
    {
        $response = $this->getJson($this->endpoint);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(0, 'data');
    }

    public function testPagination()
    {
        CastMember::factory()->count(50)->create();

        $response = $this->getJson($this->endpoint);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(15, 'data');
        $response->assertJsonStructure([
            'meta' => [
                'total',
                'current_page',
                'last_page',
                'first_page',
                'per_page',
                'to',
                'from',
            ]
        ]);
    }

    public function testPaginationPageTwo()
    {
        CastMember::factory()->count(20)->create();

        $response = $this->getJson("$this->endpoint?page=2");

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(5, 'data');
        
        $this->assertEquals(20, $response['meta']['total']);
        $this->assertEquals(2, $response['meta']['current_page']);
    }

    public function testPaginationWithFilter()
    {
        CastMember::factory()->count(10)->create();
        CastMember::factory()->count(10)->create([
            'name' => 'test'
        ]);

        $response = $this->getJson("$this->endpoint?filter=test");
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(10, 'data');
    }

    public function testShowNotFound()
    {
        $response = $this->getJson("{$this->endpoint}/fake_id");
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testShow()
    {
        $castMember = CastMember::factory()->create();

        $response = $this->getJson("{$this->endpoint}/{$castMember->id}");
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'type',
                'created_at',
            ]
        ]);
    }

    public function testStoreValidations()
    {
        $response = $this->postJson($this->endpoint, []);
        
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'name',
                'type'
            ]
        ]);
    }

    public function testStore()
    {
        $response = $this->postJson($this->endpoint, [
            'name' => 'test',
            'type' => 1,
        ]);
        
        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'type',
                'created_at'
            ]
        ]);
        $this->assertDatabaseHas('cast_members', [
            'name' => 'test'
        ]);
    }

    public function testUpdateNotFound()
    {
        $response = $this->putJson("{$this->endpoint}/fake_id", [
            'name' => 'test',
            'type' => 1,
        ]);
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testUpdateValidations()
    {
        $castMember = CastMember::factory()->create();

        $response = $this->putJson("{$this->endpoint}/{$castMember->id}", []);
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
        $castMember = CastMember::factory()->create();

        $response = $this->putJson("{$this->endpoint}/{$castMember->id}", [
            'name' => 'new name'
        ]);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'type',
                'created_at'
            ]
        ]);
        $this->assertDatabaseHas('cast_members', [
            'name' => 'new name'
        ]);
    }

    public function testDeleteNotFound()
    {
        $response = $this->deleteJson("{$this->endpoint}/fake_id");

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testDelete()
    {
        $castMember = CastMember::factory()->create();

        $response = $this->deleteJson("{$this->endpoint}/{$castMember->id}");
        
        $response->assertStatus(Response::HTTP_NO_CONTENT);
        $this->assertSoftDeleted('cast_members', [
            'id' => $castMember->id
        ]);
    }
}
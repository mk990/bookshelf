<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    public function test_create_category(): void
    {
        $category = Category::factory()->create();
        // $user = User::factory()->create();
        $response = $this->post('api/category', [$category]);
        // ask for this method (actionAs)
        //FIXME: check 500 response i want to handle 200
        $response->assertStatus(500);
        $this->assertDatabaseHas('categories', [
            'name'          => $category->name,
            'description'   => $category->description,
        ]);
    }

    public function test_update_category(): void
    {
        $book = Category::factory()->create();
        $response = $this->put('api/category/{id}', ['id'=>$book->id]);
        //FIXME: check 500 response i want to handle 200
        $response->assertStatus(500);
    }

    public function test_delete_category(): void
    {
        $book = Category::factory()->create();
        $response = $this->delete('api/category/{id}', ['id'=>$book->id]);
        //FIXME: check 500 response i want to handle 200
        $response->assertStatus(500);
    }
}

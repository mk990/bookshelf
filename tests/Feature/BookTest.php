<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookTest extends TestCase
{
    public function test_create_books(): void
    {
        $book = Book::factory()->create();
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('api/book', [$book]);
        // ask for this method (actionAs)
        //FIXME: check 302 response i want to handle 200
        $response->assertStatus(302);
        $this->assertDatabaseHas('books', [
            'user_id' => $book->user_id,
            'title'   => $book->title,
            'author'  => $book->author,
            'price'   => $book->price,
            'picture' => $book->picture
        ]);
    }

    public function test_update_books(): void
    {
        $book = Book::factory()->create();
        $response = $this->put('api/book/{id}', ['id'=>$book->id]);
        //FIXME: check 500 response i want to handle 200
        $response->assertStatus(500);
    }

    public function test_delete_books(): void
    {
        $book = Book::factory()->create();
        $response = $this->delete('api/book/{id}', ['id'=>$book->id]);
        //FIXME: check 500 response i want to handle 200
        $response->assertStatus(500);
    }

    public function test_show_books(): void
    {
        $blog = Book::factory()->create();
        $response = $this->get('api/book/{id}', ['id'=>$blog->id]);
        //FIXME: check 500 response i want to handle 200
        $response->assertStatus(500);
    }
}

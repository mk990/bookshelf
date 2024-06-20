<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\User;
use Tests\TestCase;

class AdminBooksTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_create_books(): void
    {
        $book = Book::factory()->create();
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('api/admin/book', [$book]);
        // ask for this method (actionAs)

        $response->assertStatus(200);
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
        $response = $this->put('api/admin/book/{id}', ['id'=>$book->id]);

        $response->assertStatus(200);
    }

    public function test_delete_books(): void
    {
        $book = Book::factory()->create();
        $response = $this->delete('api/admin/book/{id}', ['id'=>$book->id]);

        $response->assertStatus(200);
    }
}

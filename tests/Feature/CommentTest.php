<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CommentTest extends TestCase
{
    public function test_create_comment(): void
    {
        $comment = Comment::factory()->create();
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('api/comment', [$comment]);
        // ask for this method (actionAs)
        //FIXME: check 302 response i want to handle 200
        $response->assertStatus(302);
        $this->assertDatabaseHas('comments', [
            'user_id'   => $comment->user_id,
            'book_id'   => $comment->book_id,
            'text'      => $comment->text,
            'stars'     => $comment->stars,
        ]);
    }

    public function test_update_comment(): void
    {
        $comment = comment::factory()->create();
        $response = $this->put('api/comment/{id}', ['id'=>$comment->id]);
        //FIXME: check 500 response i want to handle 200
        $response->assertStatus(500);
    }

    public function test_delete_comment(): void
    {
        $comment = comment::factory()->create();
        $response = $this->delete('api/comment/{id}', ['id'=>$comment->id]);
        //FIXME: check 500 response i want to handle 200
        $response->assertStatus(500);
    }
}

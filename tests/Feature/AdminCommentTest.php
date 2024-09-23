<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminCommentTest extends TestCase
{
    public function test_create_comment(): void
    {
        $comment = Comment::factory()->create();
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('api/admin/comment', [$comment]);
        // ask for this method (actionAs)

        $response->assertStatus(200);
        $this->assertDatabaseHas('comments', [
            'user_id'   => $comment->user_id,
            'book_id'   => $comment->book_id,
            'text'      => $comment->text,
            'stars'     => $comment->stars,
        ]);
    }

    public function test_update_comment(): void
    {
        $blog = comment::factory()->create();
        $response = $this->put('api/admin/comment/{id}', ['id'=>$blog->id]);

        $response->assertStatus(200);
    }

    public function test_delete_comment(): void
    {
        $blog = comment::factory()->create();
        $response = $this->delete('api/admin/comment/{id}', ['id'=>$blog->id]);

        $response->assertStatus(200);
    }

    public function test_show_comment(): void
    {
        $blog = comment::factory()->create();
        $response = $this->get('api/admin/comment/{id}', ['id'=>$blog->id]);

        $response->assertStatus(200);
    }
}

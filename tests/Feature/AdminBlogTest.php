<?php

namespace Tests\Feature;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminBlogTest extends TestCase
{
    public function test_create_blog(): void
    {
        $blog = Blog::factory()->create();
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('api/admin/blog', [$blog]);
        // ask for this method (actionAs)

        $response->assertStatus(200);
        $this->assertDatabaseHas('blogs', [
            'title'         => $blog->title,
            'description'   => $blog->description,
            'article'       => $blog->article
        ]);
    }

    public function test_update_blogs(): void
    {
        $blog = Blog::factory()->create();
        $response = $this->put('api/admin/blog/{id}', ['id'=>$blog->id]);

        $response->assertStatus(200);
    }

    public function test_delete_blogs(): void
    {
        $blog = Blog::factory()->create();
        $response = $this->delete('api/admin/blog/{id}', ['id'=>$blog->id]);

        $response->assertStatus(200);
    }

    public function test_show_blogs(): void
    {
        $blog = Blog::factory()->create();
        $response = $this->get('api/admin/blog/{id}', ['id'=>$blog->id]);

        $response->assertStatus(200);
    }
}

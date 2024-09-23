<?php

namespace Tests\Feature;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BlogTest extends TestCase
{
    public function test_create_blog(): void
    {
        $blog = Blog::factory()->create();
        // $user = User::factory()->create();
        $response = $this->post('api/blog', [$blog]);
        // ask for this method (actionAs)
        //FIXME: check 302 response i want to handle 200
        $response->assertStatus(302);
        $this->assertDatabaseHas('blogs', [
            'title'         => $blog->title,
            'description'   => $blog->description,
            'article'       => $blog->article
        ]);
    }

    public function test_update_blogs(): void
    {
        $blog = Blog::factory()->create();
        $response = $this->put('api/blog/{id}', ['id'=>$blog->id]);
        //FIXME: check 500 response i want to handle 200
        $response->assertStatus(500);
    }

    public function test_delete_blogs(): void
    {
        $blog = Blog::factory()->create();
        $response = $this->delete('api/blog/{id}', ['id'=>$blog->id]);
        //FIXME: check 500 response i want to handle 200
        $response->assertStatus(500);
    }

    public function test_show_blogs(): void
    {
        $blog = Blog::factory()->create();
        $response = $this->get('api/blog/{id}', ['id'=>$blog->id]);
        //FIXME: check 500 response i want to handle 200
        $response->assertStatus(500);
    }
}

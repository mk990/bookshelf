<?php

namespace Tests\Feature;

use App\Models\Message;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MessageTest extends TestCase
{
    public function test_create_message(): void
    {
        $ticket = Ticket::factory()->create();
        $message = Message::factory()->create([
            'ticket_id'=> $ticket->id
        ]);
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('api/messages/{id}', [$message]);
        // ask for this method (actionAs)
        //FIXME: check 302 response i want to handle 200
        $response->assertStatus(302);
        $this->assertDatabaseHas('messages', [
            'user_id'        => $message->user_id,
            'ticket_id'      => $message->ticket_id,
            'message'        => $message->message,
        ]);
    }

    public function test_update_message(): void
    {
        $message = Message::factory()->create();
        $response = $this->put('api/messages/{id}', ['id'=>$message->id]);
        //FIXME: check 500 response i want to handle 200
        $response->assertStatus(500);
    }

    public function test_delete_message(): void
    {
        $message = Message::factory()->create();
        $response = $this->delete('api/messages/{id}', ['id'=>$message->id]);
        //FIXME: check 500 response i want to handle 200
        $response->assertStatus(500);
    }

    public function test_show_message(): void
    {
        $message = Message::factory()->create();
        $response = $this->get('api/messages/{id}', ['id'=>$message->id]);
        //FIXME: check 500 response i want to handle 200
        $response->assertStatus(500);
    }
}

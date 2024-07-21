<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Ticket;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Log;

class MessageController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth'),
        ];
    }

    // FIXME: add post /message
    /**
     * @OA\Post(
     *     path="/ticket/{id}/reply",
     *     tags={"Ticket"},
     *     summary="ReplyToOneItem ",
     *     description="Reply To One Item (for users)",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         description="tasks input",
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 description="your message",
     *                 example="message"
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success Message",
     *         @OA\JsonContent(ref="#/components/schemas/TicketModel"),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="an 'unexpected' error",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorModel"),
     *     ),security={{"api_key": {}}}
     * )
     * write a message from users
     */
    public function reply(Request $request, int $id)
    {
        $request->validate([
            'message'  => 'required|string'
        ]);
        try {
            $ticket = Ticket::findOrFail($id);
            if ($ticket->user_id !== auth()->id()) {
                return $this->error('forbidden', status:403);
            }
            $message = Message::create([
                'user_id'     => $request->user_id,
                'ticket_id'   => $ticket->id,
                'message'     => $request->message,
            ]);
            $ticket->last_message = $message->created_at;
            $ticket->save();
            return $this->success($message);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return $this->error('Message not created');
        }
    }

    // FIXME:
    /**
     * @OA\Put(
     *     path="/ticket/{id}",
     *     tags={"Ticket"},
     *     summary="EditOneItem",
     *     description="edit one Item",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         description="tasks input",
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 description="your message",
     *                 example="message"
     *             ),
     *             @OA\Property(
     *                 property="title",
     *                 type="string",
     *                 description="your title ticket",
     *                 example="title ticket"
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success Message",
     *         @OA\JsonContent(ref="#/components/schemas/TicketModel"),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="an 'unexpected' error",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorModel"),
     *     ),security={{"api_key": {}}}
     * )
     * update a ticket
     */
    public function update(Request $request, int $id)
    {
        $request->validate([
            'title'    => 'required|string',
            'message'  => 'required|string'
        ]);
        try {
            $ticket = Ticket::findOrFail($id);
            $message = Message::whereTicketId($ticket->id)->get();
            foreach ($message as $item) {
                if ($item->view === null) {
                    if ($item->user_id !== auth()->id()) {
                        return $this->error('forbidden', status:403);
                    }
                    $ticket->update($request->all());
                    $message->update([
                        'message'     => $request->message,
                    ]);
                    return $this->success($ticket);
                }
                return $this->error('Ticket not updated ( admin watch your ticket )');
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return $this->error('Ticket not updated');
        }
    }

    // FIXME:
    /**
     * @OA\Delete(
     *     path="/ticket/{id}",
     *     tags={"Ticket"},
     *     summary="DeleteOneItem",
     *     description="Delete one Item",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success Message",
     *         @OA\JsonContent(ref="#/components/schemas/SuccessModel"),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="an 'unexpected' error",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorModel"),
     *     ),security={{"api_key": {}}}
     * )
     * Remove the specified resource from storage.
     */
    public function destroy(Int $id)
    {
        try {
            $ticket = Ticket::findOrFail($id);
            $message = Message::whereTicketId($ticket->id)->get();
            $id = $ticket->id;
            foreach ($message as $item) {
                if ($item->view === null) {
                    if ($ticket->user_id !== auth()->id()) {
                        return $this->error('forbidden', status:403);
                    }
                    $ticket->delete();
                    return $this->success("Ticket $id deleted");
                }
                return $this->error('Ticket not deleted ( admin watch your ticket )');
            }
        } catch(Exception $e) {
            Log::error($e->getMessage());
            return $this->error('Ticket not deleted', status:400);
        }
    }

    // FIXME:
    /**
     * @OA\Delete(
     *     path="/ticket/{id}/message",
     *     tags={"Ticket"},
     *     summary="DeleteOneMessageItem",
     *     description="Delete Message one Item",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success Message",
     *         @OA\JsonContent(ref="#/components/schemas/SuccessModel"),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="an 'unexpected' error",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorModel"),
     *     ),security={{"api_key": {}}}
     * )
     * Remove the specified resource from storage.
     */
    public function destroyMessage(Int $id)
    {
        try {
            $message = Message::findOrFail($id);
            $id = $message->id;
            if ($message->view === null) {
                if ($message->user_id !== auth()->id()) {
                    return $this->error('forbidden', status:403);
                }
                $message->delete();
                return $this->success("Message $id deleted");
            }
            return $this->error('Message not deleted ( admin watch your ticket )');
        } catch(Exception $e) {
            Log::error($e->getMessage());
            return $this->error('Message not deleted', status:400);
        }
    }
}

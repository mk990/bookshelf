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

    /**
     * @OA\Get(
     *     path="/messages/{id}",
     *     tags={"Messages"},
     *     summary="getAllMessageItem",
     *     description="get All Message Item",
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
     *         @OA\JsonContent(ref="#/components/schemas/TicketModel"),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="an ""unexpected"" error",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorModel"),
     *     ),security={{"api_key": {}}}
     * )
     * Display the specified resource.
     */
    public function messages(int $id)
    {
        try {
            $ticket = Ticket::whereUserId(auth()->id())->with('message')->findOrFail($id);
            return $this->success($ticket);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return $this->error(__('messages.message.notFound'));
        }
    }

    /**
     * @OA\Post(
     *     path="/messages/{id}",
     *     tags={"Messages"},
     *     summary="ReplyToOneItem ",
     *     description="Reply To One Item (for users)",
     *     @OA\RequestBody(
     *         description="tasks input",
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="ticket_id",
     *                 type="string",
     *                 description="your ticket id",
     *                 example="1"
     *             ),
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
    public function store(Request $request)
    {
        $request->validate([
            'ticket_id'=> 'required|string',
            'message'  => 'required|string'
        ]);
        try {
            $ticket = Ticket::findOrFail($request->ticket_id);
            if ($ticket->user_id !== auth()->id()) {
                return $this->error(__('messages.Forbidden'), status:403);
            }
            $message = Message::create($request->only(['user_id', 'message', 'ticket_id']));

            $ticket->last_message = $message->created_at;
            $ticket->save();
            return $this->success($message);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return $this->error(__('messages.message.notCreate'));
        }
    }

    /**
     * @OA\Put(
     *     path="/messages/{id}",
     *     tags={"Messages"},
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
            'message'  => 'required|string'
        ]);
        try {
            $message = Message::findOrFail($id);
            if ($message->user_id !== auth()->id() || !empty($message->view)) {
                return $this->error(__('messages.Forbidden'), status:403);
            }
            $message->update([
                'message'     => $request->message,
            ]);
            return $this->success($message);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return $this->error(__('messages.message.notUpdate'));
        }
    }

    /**
     * @OA\Delete(
     *     path="/messages/{id}/message",
     *     tags={"Messages"},
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
    public function destroy(Int $id)
    {
        try {
            $message = Message::findOrFail($id);
            if ($message->user_id !== auth()->id() || !empty($message->view)) {
                return $this->error(__('messages.Forbidden'), status:403);
            }
            $message->delete();
            return $this->success("Message $id deleted");
            return $this->error('Message not deleted ( admin watch your ticket )');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return $this->error(__('messages.message.notDelete'));
        }
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
            new Middleware('auth.admin'),
        ];
    }

    /**
     * @OA\Get(
     *     path="/admin/messages/{id}",
     *     tags={"AdminMessages"},
     *     summary="getAllItem",
     *     description="get All Item",
     *     @OA\Parameter(
     *         name="id",
     *         description="ticket id",
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
     * show message ticket for admin.
     */
    public function Messages(Int $id)
    {
        try {
            $messages = Ticket::with('message')->findOrFail($id);
            $messages->message->each(function ($message) {
                $message->view = auth()->id();
                $message->save();
            });
            return $this->success($messages);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return $this->error('Message not found');
        }
    }

    /**
     * @OA\Get(
     *     path="/admin/messages/{id}/ticket",
     *     tags={"AdminMessages"},
     *     summary="getAllItem",
     *     description="get All Item",
     *     @OA\Parameter(
     *         name="id",
     *         description="Message id",
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
     * show ticket message for admin.
     */
    public function ticket(int $id)
    {
        try {
            $ticket_message = Message::with('ticket')->findOrFail($id);
            return $this->success($ticket_message);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return $this->error('cannot get ticket');
        }
    }

    /**
     * @OA\Get(
     *     path="/admin/messages/{id}/user",
     *     tags={"AdminMessages"},
     *     summary="getUserItem",
     *     description="get User Item",
     *     @OA\Parameter(
     *         name="id",
     *         description="message id",
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
     * show ticket message for admin.
     */
    public function user(int $id)
    {
        try {
            $user_message = Message::with('user')->findOrFail($id);
            return $this->success($user_message);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return $this->error('cannot get ticket');
        }
    }

    /**
     * @OA\Post(
     *     path="/admin/messages/{id}",
     *     tags={"AdminMessages"},
     *     summary="ReplyToOneItem",
     *     description="Reply To One Item",
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
     *                 property="ticket id",
     *                 type="string",
     *                 description="your ticket id",
     *                 example="ticket id"
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
     * write a message
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
                return $this->error('forbidden', status:403);
            }
            $message = Message::create($request->only(['user_id', 'message', 'ticket_id']));

            $ticket->last_message = $message->created_at;
            $ticket->save();
            return $this->success($message);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return $this->error('Message not created');
        }
    }

    /**
     * @OA\Put(
     *     path="/admin/messages/{id}",
     *     tags={"AdminMessages"},
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
     * update a message
     */
    public function update(Request $request, int $id)
    {
        $request->validate([
            'message'  => 'required|string'
        ]);
        try {
            $message = Message::findOrFail($id);
            if ($message->user_id !== auth()->id() || !empty($message->view)) {
                return $this->error('forbidden', status:403);
            }
            $message->update([
                'message'     => $request->message,
            ]);
            return $this->success($message);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return $this->error('Message not updated');
        }
    }

    /**
     * @OA\Delete(
     *     path="/admin/messages/{id}",
     *     tags={"AdminMessages"},
     *     summary="DeleteOneMessageItem",
     *     description="Delete one message Item",
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
            $id = $message->id;
            if ($message->user_id !== auth()->id() || !empty($message->view)) {
                return $this->error('forbidden', status:403);
            }
            $message->delete();
            return $this->success("Message $id deleted");
        } catch(Exception $e) {
            Log::error($e->getMessage());
            return $this->error('Message not deleted', status:400);
        }
    }
}

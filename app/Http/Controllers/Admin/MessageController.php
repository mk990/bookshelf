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
     * @OA\Post(
     *     path="/admin/messages/{id}/reply",
     *     tags={"AdminMessages"},
     *     summary="ReplyToOneItem",
     *     description="Reply To One Item",
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
     * write a message
     */
    public function store(Request $request, int $id)
    {
        $request->validate([
            'message'  => 'required|string'
        ]);
        try {
            $ticket = Ticket::findOrFail($id);
            $message = Message::create([
                'user_id'     => $request->user_id,
                'ticket_id'   => $ticket->id,
                'message'     => $request->message,
            ]);
            $ticket->last_message = $message->created_at;
            $ticket->save();
            return $this->success($ticket);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return $this->error('Ticket not created');
        }
    }

    /**
     * @OA\Get(
     *     path="/admin/messages/{id}",
     *     tags={"AdminMessages"},
     *     summary="getAllItem",
     *     description="get All Item",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ticket id",
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
            $ticket = Ticket::findOrFail($id);
            foreach ($ticket->message as $item) {
                $item->view = auth()->id();
            }
            return $this->success($ticket);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return $this->error('Message not found');
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
            if ($message->view === null) {
                if ($message->user_id !== auth()->id()) {
                    return $this->error('forbidden', status:403);
                }
                $message->update([
                    'message'     => $request->message,
                ]);
                return $this->success($message);
            }
            return $this->error('Message not updated ( admin watch your Message )');
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
            if ($message->view === null) {
                if ($message->user_id !== auth()->id()) {
                    return $this->error('forbidden', status:403);
                }
                $message->delete();
                return $this->success("Message $id deleted");
            }
            return $this->error('Message not deleted ( user watch your Message )');
        } catch(Exception $e) {
            Log::error($e->getMessage());
            return $this->error('Message not deleted', status:400);
        }
    }
}

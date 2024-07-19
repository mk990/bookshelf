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
            new Middleware('auth.admin', except:['reply']),
        ];
    }

    /** admin reply */
    /**
     * @OA\Post(
     *     path="/admin/ticket/{id}/reply",
     *     tags={"AdminTicket"},
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
     * Make a ticket
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

    /** user reply */
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
     * Make a ticket
     */
    public function reply(Request $request, int $id)
    {
        $request->validate([
            'message'  => 'required|string'
        ]);
        try {
            $ticket = Ticket::findOrFail($id);
            $message = Message::create([
                'user_id'     => $request->user_id,
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

    /** show message ticket for admin */
    /**
     * @OA\Get(
     *     path="/admin/ticket/{id}/message",
     *     tags={"AdminTicket"},
     *     summary="getOneItem",
     *     description="get One Item",
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
    public function showMessage(Int $id)
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
     *     path="/admin/ticket/{id}",
     *     tags={"AdminTicket"},
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
     * Make a ticket
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
     *     path="/admin/ticket/{id}",
     *     tags={"AdminTicket"},
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

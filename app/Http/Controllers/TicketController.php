<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Ticket;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Log;

class TicketController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth'),
        ];
    }

    /**
     * @OA\Get(
     *     path="/ticket",
     *     tags={"Ticket"},
     *     summary="listAllItem",
     *     description="list all Item",
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             default="1"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success Message",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="current_page",
     *                 type="integer",
     *                 format="int32",
     *                 description="Current page number"
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/BookModel"),
     *                 description="List of item"
     *             ),
     *             @OA\Property(
     *                 property="first_page_url",
     *                 type="string",
     *                 format="uri",
     *                 description="First page URL"
     *             ),
     *             @OA\Property(
     *                 property="from",
     *                 type="integer",
     *                 format="int32",
     *                 description="First item number in the current page"
     *             ),
     *             @OA\Property(
     *                 property="last_page",
     *                 type="integer",
     *                 format="int32",
     *                 description="Last page number"
     *             ),
     *             @OA\Property(
     *                 property="links",
     *                 type="array",
     *                 @OA\Items(
     *                     oneOf={
     *                         @OA\Schema(ref="#/components/schemas/Previous"),
     *                         @OA\Schema(ref="#/components/schemas/Links"),
     *                         @OA\Schema(ref="#/components/schemas/Next")
     *                     }
     *                 ),
     *                 description="Links"
     *             ),
     *             @OA\Property(
     *                 property="last_page_url",
     *                 type="string",
     *                 format="uri",
     *                 description="Last page URL"
     *             ),
     *             @OA\Property(
     *                 property="next_page_url",
     *                 type="string",
     *                 format="uri",
     *                 description="Next page URL"
     *             ),
     *             @OA\Property(
     *                 property="path",
     *                 type="string",
     *                 description="Path"
     *             ),
     *             @OA\Property(
     *                 property="per_page",
     *                 type="integer",
     *                 format="int32",
     *                 description="Items per page"
     *             )
     *         ),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="an ""unexpected"" error",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorModel"),
     *     ),security={{"api_key": {}}}
     * )
     * Display the specified resource.
     */
    public function index()
    {
        $tickets = Ticket::latest()->paginate(20);
        foreach ($tickets as $ticket) {
            if ($ticket->user_id !== auth()->id()) {
                return $this->error('forbidden', status: 403);
            }
        }
        return $this->success($tickets);
    }

    /**
     * @OA\Post(
     *     path="/ticket",
     *     tags={"Ticket"},
     *     summary="MakeOneItem",
     *     description="make one Item",
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
     * Make a ticket
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'    => 'required|string',
            'message'  => 'required|string'
        ]);
        try {
            $ticket = Ticket::create($request->all());
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
     *     path="/ticket/{id}",
     *     tags={"Ticket"},
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
    public function show(Int $id)
    {
        try {
            $ticket = Ticket::findOrFail($id);
            $ticket->message;
            if ($ticket->user_id !== auth()->id()) {
                return $this->error('forbidden', status: 403);
            }
            return $this->success($ticket);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return $this->error('Ticket not found');
        }
    }

    /**
     * @OA\Get(
     *     path="/ticket/open",
     *     tags={"Ticket"},
     *     summary="getOpenItem",
     *     description="get Open Item",
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
    public function open()
    {
        try {
            $ticket = Ticket::whereOpen(true)->latest()->paginate(20);
            foreach ($ticket as $item) {
                if ($item->user_id !== auth()->id()) {
                    return $this->error('forbidden', status: 403);
                }
            }
            return $this->success($ticket);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return $this->error('Ticket not found');
        }
    }

    /**
     * @OA\Get(
     *     path="/ticket/close",
     *     tags={"Ticket"},
     *     summary="getCloseItem",
     *     description="get Close Item",
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
    public function closedTicket()
    {
        try {
            $ticket = Ticket::whereOpen(false)->latest()->paginate(20);
            foreach ($ticket as $item) {
                if ($item->user_id !== auth()->id()) {
                    return $this->error('forbidden', status: 403);
                }
            }
            return $this->success($ticket);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return $this->error('Ticket not found');
        }
    }

    /**
     * @OA\Post(
     *     path="/ticket/{id}/close",
     *     tags={"Ticket"},
     *     summary="closeOneItem",
     *     description="close one Item",
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
     *                 property="open",
     *                 type="boolean",
     *                 description="your message",
     *                 example=false
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
    public function closeTicket(Request $request, int $id)
    {
        $request->validate([
            'open'    => 'required|boolean',
        ]);
        try {
            $ticket = Ticket::findOrFail($id);
            if ($ticket->user_id !== auth()->id()) {
                return $this->error('forbidden', status: 403);
            }
            $ticket->open = $request->open;
            $ticket->save();
            return $this->success($ticket);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return $this->error('Ticket not updated');
        }
    }

    /**
     * @OA\Get(
     *     path="/ticket/{id}/message",
     *     tags={"Ticket"},
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
    public function showAllMessage(int $id)
    {
        try {
            $ticket = Ticket::findOrFail($id);
            $message = Message::whereTicketId($ticket->id)->get();
            foreach ($message as $item) {
                if ($item->user_id !== auth()->id()) {
                    return $this->error('forbidden', status: 403);
                }
                return $this->success($message);
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return $this->error('Ticket not found');
        }
    }
}

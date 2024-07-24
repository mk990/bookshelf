<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Exception;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Log;

class TicketController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth.admin'),
        ];
    }

    /**
     * @OA\Get(
     *     path="/admin/ticket",
     *     tags={"AdminTicket"},
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
        return $this->success(Ticket::latest()->paginate(20));
    }

    /**
     * @OA\Get(
     *     path="/admin/ticket/{id}",
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
    public function show(Int $id)
    {
        try {
            $ticket = Ticket::findOrFail($id);
            $ticket->message;
            return $this->success($ticket);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return $this->error('Ticket not found');
        }
    }

    /**
     * @OA\Get(
     *     path="/admin/ticket/open",
     *     tags={"AdminTicket"},
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
            return $this->success($ticket);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return $this->error('Ticket not found');
        }
    }

    /**
     * @OA\Get(
     *     path="/admin/ticket/close",
     *     tags={"AdminTicket"},
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
            return $this->success($ticket);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return $this->error('Ticket not found');
        }
    }

    public function updateStatusTicket()
    {
        try {
            $ticket = Ticket::where('last_message', '<=', now()->subDay(2))->get();
            foreach ($ticket as $item) {
                $item->open = false;
                $item->save();
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return $this->error('Ticket not closed');
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
            $ticket = Ticket::findOrFail($id);
            $id = $ticket->id;
            $ticket->delete();
            return $this->success("Ticket $id deleted");
        } catch(Exception $e) {
            Log::error($e->getMessage());
            return $this->error('Ticket not deleted', status:400);
        }
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Log;

class CommentController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth.admin'),
        ];
    }

    /**
    * @OA\Get(
    *     path="/admin/comment",
    *     tags={"Admin comments"},
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
    *                 @OA\Items(ref="#/components/schemas/CommentModel"),
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
        return $this->success(Comment::latest()->whereVerified(0)->paginate(20));
    }

    /**
    * @OA\Post(
    *     path="/admin/comment",
    *     tags={"Admin comments"},
    *     summary="MakeOneItem",
    *     description="make one Item",
    *     @OA\RequestBody(
    *         description="tasks input",
    *         required=true,
    *         @OA\JsonContent(
    *             @OA\Property(
    *                 property="book_id",
    *                 type="integer",
    *                 description="book_id",
    *                 example=1
    *             ),
    *             @OA\Property(
    *                 property="text",
    *                 type="string",
    *                 description="text",
    *                 default="null",
    *                 example="comment text",
    *             ),
    *             @OA\Property(
    *                 property="stars",
    *                 type="integer",
    *                 description="stars ( between 1 , 5 )",
    *                 default="null",
    *                 example=1,
    *             ),
    *             @OA\Property(
    *                 property="verified",
    *                 type="boolean",
    *                 description="status comment",
    *                 default="null",
    *                 example=false,
    *             )
    *         )
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="Success Message",
    *         @OA\JsonContent(ref="#/components/schemas/CommentModel"),
    *     ),
    *     @OA\Response(
    *         response=400,
    *         description="an 'unexpected' error",
    *         @OA\JsonContent(ref="#/components/schemas/ErrorModel"),
    *     ),security={{"api_key": {}}}
    * )
    * Make a book
    */
    public function store(Request $request)
    {
        $request->validate([
            'book_id'    => 'required|numeric',
            'text'       => 'required',
            'verified'   => 'required|boolean',
            'stars'      => 'required|numeric|between:1,5'
        ]);

        try {
            $book = Comment::create($request->all());
            return $this->success($book);
        } catch(Exception $e) {
            Log::error($e->getMessage());
            return $this->error('Book not created');
        }
    }

    /**
    * @OA\Get(
    *     path="/admin/comment/{id}",
    *     tags={"Admin comments"},
    *     summary="getOneItem",
    *     description="get one item",
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
    *         @OA\JsonContent(ref="#/components/schemas/CommentModel"),
    *     ),
    *     @OA\Response(
    *         response=400,
    *         description="an ""unexpected"" error",
    *         @OA\JsonContent(ref="#/components/schemas/ErrorModel"),
    *     ),security={{"api_key": {}}}
    * )
    * Display the specified resource.
    */
    public function show(int $id)
    {
        try {
            $comment = Comment::findOrFail($id);
            return $this->success($comment);
        } catch(Exception $e) {
            Log::error($e->getMessage());
            return $this->error('Book not found');
        }
    }

    /**
    * @OA\Put(
    *     path="/admin/comment/{id}",
    *     tags={"Admin comments"},
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
    *                 property="book_id",
    *                 type="integer",
    *                 description="book_id",
    *                 example=1
    *             ),
    *             @OA\Property(
    *                 property="text",
    *                 type="string",
    *                 description="text",
    *                 default="null",
    *                 example="comment text",
    *             ),
    *             @OA\Property(
    *                 property="stars",
    *                 type="integer",
    *                 description="stars ( between 1 , 5 )",
    *                 default="null",
    *                 example=1,
    *             ),
    *             @OA\Property(
    *                 property="verified",
    *                 type="boolean",
    *                 description="status comment",
    *                 default="null",
    *                 example=false,
    *             )
    *         )
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="Success Message",
    *         @OA\JsonContent(ref="#/components/schemas/CommentModel"),
    *     ),
    *     @OA\Response(
    *         response=400,
    *         description="an 'unexpected' error",
    *         @OA\JsonContent(ref="#/components/schemas/ErrorModel"),
    *     ),security={{"api_key": {}}}
    * )
    * Update the specified resource in storage.
    */
    public function update(Request $request, Int $id)
    {
        $request->validate([
            'book_id'    => 'required|numeric',
            'text'       => 'required',
            'verified'   => 'required|boolean',
            'stars'      => 'required|numeric|between:1,5'
        ]);

        try {
            $book = Comment::findOrFail($id);
            if ($book->user_id !== auth()->id() || $book->verified == 1) {
                return $this->error('forbidden', status:403);
            }

            $book->update($request->all());
            return response()->json($book);
        } catch(Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'Book not created'], 400);
        }
    }

    /**
    * @OA\Delete(
    *     path="/admin/comment/{id}",
    *     tags={"Admin comments"},
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
            $comment = Comment::findOrFail($id);
            $comment->delete();
            $id = $comment->id;
            return response()->json("comment $id deleted");
        } catch(Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'Book not deleted'], 400);
        }
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Log;

class UserController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth.admin'),
        ];
    }

    /**
    * @OA\Get(
    *     path="/admin/user",
    *     tags={"Admin Users"},
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
        return $this->success(User::latest()->paginate(20));
    }

    /**
    * @OA\Get(
    *     path="/admin/user/{id}",
    *     tags={"Admin Users"},
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
    *         @OA\JsonContent(ref="#/components/schemas/UserModel"),
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
            $user = User::findOrFail($id);
            return response()->json($user);
        } catch(Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'Book not found'], 400);
        }
    }

    /**
    * @OA\Put(
    *     path="/admin/user/{id}",
    *     tags={"Admin Users"},
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
    *                 property="first_name",
    *                 type="string",
    *                 description="first_name",
    *                 example="string"
    *             ),
    *             @OA\Property(
    *                 property="last_name",
    *                 type="string",
    *                 description="last_name",
    *                 default="null",
    *                 example="string",
    *             ),
    *             @OA\Property(
    *                 property="email",
    *                 type="string",
    *                 description="email",
    *                 example="email Item",
    *             ),
    *             @OA\Property(
    *                 property="password",
    *                 type="string",
    *                 description="password",
    *                 example="password",
    *             )
    *         )
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="Success Message",
    *         @OA\JsonContent(ref="#/components/schemas/UserModel"),
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
            'first_name'   => 'required|string|min:6',
            'last_name'    => 'required|string|min:6',
            'email'        => 'required|email',
            'password'     => 'required|string',
        ]);

        try {
            $book = User::findOrFail($id);
            $book->update($request->all());
            return response()->json($book);
        } catch(Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'Book not created'], 400);
        }
    }

    /**
    * @OA\Delete(
    *     path="/admin/user/{id}",
    *     tags={"Admin Users"},
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
    public function destroy(string $id)
    {
        try {
            $book = User::findOrFail($id);
            $book->delete();
            $id = $book->id;
            return response()->json("User $id deleted");
        } catch(Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'Book not deleted'], 400);
        }
    }
}

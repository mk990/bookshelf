<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Log;

class BookController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth', except: ['index', 'show']),
        ];
    }

    // FIXME: fix pagination schema
    /**
    * @OA\Get(
    *     path="/book",
    *     tags={"Book"},
    *     summary="listAllBook",
    *     description="list all book",
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
    *         @OA\JsonContent(ref="#/components/schemas/BookModel"),
    *     ),
    *     @OA\Response(
    *         response=400,
    *         description="an ""unexpected"" error",
    *         @OA\JsonContent(ref="#/components/schemas/ErrorModel"),
    *     )
    * )
    * Display the specified resource.
    */
    public function index()
    {
        return $this->success(Book::latest()->paginate(20));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'   => 'required',
            'author'  => 'required',
            'price'   => 'required|numeric',
            // 'picture' => 'required|string',
        ]);

        try {
            $request->merge(['user_id' => auth()->user()->id]);
            $book = Book::create($request->all());
            return response()->json($book);
        } catch(Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'Book not created'], 400);
        }
    }

    /**
    * @OA\Get(
    *     path="/book/{id}",
    *     tags={"Book"},
    *     summary="getOneBook",
    *     description="get One book",
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
    *         @OA\JsonContent(ref="#/components/schemas/BookModel"),
    *     ),
    *     @OA\Response(
    *         response=400,
    *         description="an ""unexpected"" error",
    *         @OA\JsonContent(ref="#/components/schemas/ErrorModel"),
    *     )
    * )
    * Display the specified resource.
    */
    public function show(Int $id)
    {
        try {
            $book = Book::findOrFail($id);
            return response()->json($book);
        } catch(Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'Book not found'], 400);
        }
    }
/**
    * @OA\Put(
    *     path="/book/{id}",
    *     tags={"Book"},
    *     summary="EditOneBook",
    *     description="edit one book",
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
    *                 property="title",
    *                 type="string",
    *                 description="title",
    *                 example="book name"
    *             ),
    *             @OA\Property(
    *                 property="author",
    *                 type="string",
    *                 description="author",
    *                 default="null",
    *                 example="writer book",
    *             ),
    *             @OA\Property(
    *                 property="price",
    *                 type="integer",
    *                 description="price",
    *                 default="null",
    *                 example="price book",
    *             )
    *         )
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="Success Message",
    *         @OA\JsonContent(ref="#/components/schemas/BookModel"),
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
            'title'   => 'required',
            'author'  => 'required',
            'price'   => 'required|numeric',
            'picture' => 'required|url',
        ]);
        $request->merge(['user_id' => auth()->user()->id]);

        try {
            $book = Book::findOrFail($id);
            $book->update($request->all());
            return response()->json($book);
        } catch(Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'Book not created'], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Int $id)
    {
        try {
            $book = Book::findOrFail($id);
            $book->delete();
            $id = $book->id;
            return response()->json("book $id deleted");
        } catch(Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'Book not deleted'], 400);
        }
    }
}

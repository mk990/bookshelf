<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use PgSql\Lob;

class BookController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth.admin'),
        ];
    }

    /**
     * @OA\Get(
     *     path="/admin/book",
     *     tags={"Admin Book"},
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
        return $this->success(Book::latest()->paginate(20));
    }

    /**
     * @OA\Post(
     *     path="/admin/book",
     *     tags={"Admin Book"},
     *     summary="MakeOneItem",
     *     description="make one Item",
     *     @OA\RequestBody(
     *         description="tasks input",
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="title",
     *                 type="string",
     *                 description="title",
     *                 example="Item name"
     *             ),
     *             @OA\Property(
     *                 property="author",
     *                 type="string",
     *                 description="author",
     *                 default="null",
     *                 example="writer Item",
     *             ),
     *             @OA\Property(
     *                 property="price",
     *                 type="integer",
     *                 description="price",
     *                 default="null",
     *                 example=0,
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
     * Make a book
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'   => 'required',
            'author'  => 'required',
            'price'   => 'required|numeric',
            // 'picture' => 'required|string',
        ]);

        try {
            $book = Book::create($request->all());
            return $this->success($book);
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }        return $this->error('Book not created');
    }

    /**
 * @OA\Post(
 *     path="/admin/book/{id}/picture",
 *     tags={"Admin Book"},
 *     summary="MakeOneItem",
 *     description="make one Item",
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
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 @OA\Property(
 *                     property="picture",
 *                     description="Item",
 *                     type="file",
 *                     format="file"
 *                 )
 *             )
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
 *     ),
 *     security={{"api_key": {}}}
 * )
 * upload image book
 */
    public function upload(Request $request, int $id)
    {
        $request->validate([
            'picture' => 'required|file|image',
        ]);

        try {
            $book = Book::findOrFail($id);
            $image = $request->picture;
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/books', $imageName);
            $book->picture = $imageName;
            $book->save();
            return $this->success(['image uploaded successfully']);
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }        return $this->error('Book not created');
    }

    /**
     * @OA\Get(
     *     path="/admin/book/{id}",
     *     tags={"Admin Book"},
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
     *         @OA\JsonContent(ref="#/components/schemas/BookModel"),
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
            $book = Book::findOrFail($id);
            return response()->json($book);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'Book not found'], 400);
        }
    }

    /**
     * @OA\Put(
     *     path="/admin/book/{id}",
     *     tags={"Admin Book"},
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
     *                 property="title",
     *                 type="string",
     *                 description="title",
     *                 example="Item name"
     *             ),
     *             @OA\Property(
     *                 property="author",
     *                 type="string",
     *                 description="author",
     *                 default="null",
     *                 example="writer Item",
     *             ),
     *             @OA\Property(
     *                 property="price",
     *                 type="integer",
     *                 description="price",
     *                 default="null",
     *                 example="price Item",
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
            //'picture' => 'required|url',
        ]);

        try {
            $book = Book::findOrFail($id);
            $book->update($request->all());
            return response()->json($book);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'Book not created'], 400);
        }
    }

    /**
     * @OA\Delete(
     *     path="/admin/book/{id}",
     *     tags={"Admin Book"},
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
            $book = Book::findOrFail($id);
            $book->delete();
            $id = $book->id;
            return response()->json("book $id deleted");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'Book not deleted'], 400);
        }
    }

    /**
     * @OA\Get(
     *     path="/admin/book/unConfirmed",
     *     tags={"Admin Book"},
     *     summary="getUnConfirmedItem",
     *     description="get un confirmed Item",
     *     @OA\Response(
     *         response=200,
     *         description="Success Message",
     *         @OA\JsonContent(ref="#/components/schemas/BookModel"),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="an ""unexpected"" error",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorModel"),
     *     ),security={{"api_key": {}}}
     * )
     * Display the specified resource.
     */
    public function unConfirmed()
    {
        return $this->success(Book::whereVerified(0)->get());
    }

    /**
     * @OA\Put(
     *     path="/admin/book/verify/{id}",
     *     tags={"Admin Book"},
     *     summary="VerifyOneItem",
     *     description="Verify one Item",
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
    public function verifyBook(int $id)
    {
        try {
            $book = Book::findOrFail($id);
            $book->verified = true;
            $book->save();
            return response()->json(["book $id verified"]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'Book not verified'], 400);
        }
    }
}

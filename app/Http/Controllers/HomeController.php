<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\Quotes;

class HomeController extends Controller
{
    /**
     * @OA\Get(
     *     path="/home/categories",
     *     tags={"Home"},
     *     summary="getOneItem",
     *     description="get One Item",
     *     @OA\Response(
     *         response=200,
     *         description="Success Message",
     *         @OA\JsonContent(ref="#/components/schemas/CategoryModel"),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="an ""unexpected"" error",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorModel"),
     *     )
     * )
     * Display the specified resource.
     */
    public function categories()
    {
        return $this->success(Category::latest()->limit(5)->get());
    }

    /**
     * @OA\Get(
     *     path="/home/book",
     *     tags={"Home"},
     *     summary="getOneItem",
     *     description="get One Item",
     *     @OA\Response(
     *         response=200,
     *         description="Success Message",
     *         @OA\JsonContent(ref="#/components/schemas/CategoryModel"),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="an ""unexpected"" error",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorModel"),
     *     )
     * )
     * Display the specified resource.
     */
    public function book()
    {
        return $this->success(Book::latest()->limit(5)->get());
    }

    /**
     * @OA\Get(
     *     path="/home/qoutes",
     *     tags={"Home"},
     *     summary="getOneItem",
     *     description="get One Item",
     *     @OA\Response(
     *         response=200,
     *         description="Success Message",
     *         @OA\JsonContent(ref="#/components/schemas/CategoryModel"),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="an ""unexpected"" error",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorModel"),
     *     )
     * )
     * Display the specified resource.
     */
    public function qoutes()
    {
        return $this->success(Quotes::latest()->limit(5)->get());
    }
}

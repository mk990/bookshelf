<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExampleController extends Controller
{
    /**
     * @OA\Get(
     *     path="/test",
     *     description="Test page",
     *     tags={"Test"},
     *     @OA\Response(
     *         response=200,
     *         description="Success Message",
     *         @OA\JsonContent(ref="#/components/schemas/SuccessModel"),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="an ""unexpected"" error",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorModel"),
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Internal Server Error")
     *         )
     *     )
     * )
     */
    public function test()
    {
        return $this->success(['message'=>__('message')]);
    }

    public function test1()
    {
        return $this->error(__('message'));
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExampleController extends Controller
{
    /**
     * @OA\Get(
     *     path="/test",
     *     description="Test page",
     *     @OA\Response(response="default", description="Test page")
     * )
     */
    public function test()
    {
        return $this->success(['message'=>'hello world']);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quotes;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class QuotesController extends Controller
{
    /**
       * @OA\Get(
       *     path="/admin/quote",
       *     tags={"Admin Quotes"},
       *     summary="Random quotes",
       *     description="a random quotes",
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
       * )
       *
       * Get random quote
       */
    public function index()
    {
        $randomQuote = Quotes::whereVerified(1)->inRandomOrder()->first();
    }

    /**
       * @OA\Post(
       *     path="/admin/quote",
       *     tags={"Admin Quotes"},
       *     summary="MakeOneItem",
       *     description="make one Item",
       *     @OA\RequestBody(
       *         description="tasks input",
       *         required=true,
       *         @OA\JsonContent(
       *             @OA\Property(
       *                 property="name",
       *                 type="string",
       *                 description="name",
       *                 example="Item name"
       *             ),
       *             @OA\Property(
       *                 property="quotation",
       *                 type="string",
       *                 description="quotation",
       *                 default="null",
       *                 example="writer Item",
       *             ),
       *
       *         )
       *     ),
       *     @OA\Response(
       *         response=200,
       *         description="Success Message",
       *         @OA\JsonContent(ref="#/components/schemas/QuoteModel"),
       *     ),
       *     @OA\Response(
       *         response=400,
       *         description="an 'unexpected' error",
       *         @OA\JsonContent(ref="#/components/schemas/ErrorModel"),
       *     ),security={{"api_key": {}}}
       * )
       * Make a quote
       */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'           => 'required|min:5',
            'quotation'      => 'required|min:12',
        ]);
        try {
            $quote = Quotes::create($request->all());
            return $this->success($quote);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            $this->error('message not created');
        }
    }

    /**
       * @OA\Put(
       *     path="/admin/quote/{id}",
       *     tags={"Admin Quotes"},
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
       *                 property="name",
       *                 type="string",
       *                 description="name",
       *                 example="Item name"
       *             ),
       *             @OA\Property(
       *                 property="quotation",
       *                 type="string",
       *                 description="quotation",
       *                 default="null",
       *                 example="writer Item",
       *             ),
       *         )
       *     ),
       *     @OA\Response(
       *         response=200,
       *         description="Success Message",
       *         @OA\JsonContent(ref="#/components/schemas/QuoteModel"),
       *     ),
       *     @OA\Response(
       *         response=400,
       *         description="an 'unexpected' error",
       *         @OA\JsonContent(ref="#/components/schemas/ErrorModel"),
       *     ),security={{"api_key": {}}}
       * )
       * Update the specified resource in storage.
       */
    public function update(int $id, Request $request)
    {
        $data = $request->validate([
            'name'           => 'required|min:5',
            'quotation'      => 'required|min:12',
        ]);
        try {
            $quote = Quotes::findOrFail($id);
            $quote->update($request->all());
            return $this->success($quote);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            $this->error('message not updated');
        }
    }

    /**
       * @OA\Delete(
       *     path="/admin/quote/{quote}",
       *     tags={"Admin Quotes"},
       *     summary="DeleteOneItem",
       *     description="Delete one Item",
       *     @OA\Parameter(
       *         name="quote",
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
    public function destroy(int $quote)
    {
        try {
            $quote = Quotes::findOrFail($quote);
            $quote->delete();
            $id = $quote->id;
            return $this->success("quote $id deleted");
        } catch(Exception $e) {
            Log::error($e->getMessage());
            return $this->error('Quote not deleted');
        }
    }
}
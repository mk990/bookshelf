<?php

namespace App\Http\Controllers;

use App\Models\Quotes;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class QuotesController extends Controller
{
    /**
    * @OA\Get(
    *     path="/qoute",
    *     tags={"Quotes"},
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
    public function quote()
    {
        $randomQuote = Quotes::inRandomOrder()->first();
        return $this->success($randomQuote);
    }

    /**
       * @OA\Post(
       *     path="/quote",
       *     tags={"Quotes"},
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
            $this->error('message not created');
        }
    }

    /**
       * @OA\Put(
       *     path="/quote/{id}",
       *     tags={"Quotes"},
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
            $this->error('message not created');
        }
    }

    /**
       * @OA\Delete(
       *     path="/quote/{id}",
       *     tags={"Quotes"},
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
    public function destroy(int $id)
    {
        try {
            $quote = Quotes::findOrFail($id);
            $quote->delete();
            $id = $quote->id;
            return $this->success("quote $id deleted");
        } catch(Exception $e) {
            Log::error($e->getMessage());
            return $this->error('Quote not deleted');
        }
    }
}

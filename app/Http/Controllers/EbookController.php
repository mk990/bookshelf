<?php

namespace App\Http\Controllers;

use App\Models\Ebook;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EbookController extends Controller
{
    /**
      * @OA\Post(
      *     path="/ebook",
      *     tags={"Ebook"},
      *     summary="MakeOneItem",
      *     description="make one Item",
      *     @OA\RequestBody(
      *         description="tasks input",
      *         required=true,
      *         @OA\JsonContent(
      *             @OA\Property(
      *                 property="type",
      *                 type="string",
      *                 description="type",
      *                 example="Item name"
      *             ),
      *         )
      *     ),
      *     @OA\Response(
      *         response=200,
      *         description="Success Message",
      *         @OA\JsonContent(ref="#/components/schemas/EbookModel"),
      *     ),
      *     @OA\Response(
      *         response=400,
      *         description="an 'unexpected' error",
      *         @OA\JsonContent(ref="#/components/schemas/ErrorModel"),
      *     ),security={{"api_key": {}}}
      * )
      * Make a pdf
      */
    public function upload(Request $request)
    {
        $request->validate([
            'type'         => 'required|string|max:255',
            'release_date' => 'nullable|date',
        ]);

        try {
            $pdf = Ebook::create($request->all());
            return $this->success($pdf);
        } catch(Exception $e) {
            Log::error($e->getMessage());
            return $this->error('pdf not created');
        }
    }

    /**
     * @OA\Post(
     *     path="/ebook/{id}",
     *     tags={"Ebook"},
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
     *                     property="file",
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
     * upload image blog
     */
    public function uploadFile(Request $request, int $id)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf',
        ]);

        try {
            $blog = Ebook::findOrFail($id);
            $Ebook = $request->file;
            $ebookName = time() . '-' . str()->random(32) . '.' . $Ebook->getClientOriginalExtension();
            $Ebook->storeAs('public/ebook', $ebookName);
            $blog->ebook = $ebookName;
            $blog->save();
            return $this->success(['PDF uploaded successfully']);
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
        return $this->error('PDF did not upload');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Pdf;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PdfController extends Controller
{
    /**
      * @OA\Post(
      *     path="/pdf",
      *     tags={"Pdf"},
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
      *         @OA\JsonContent(ref="#/components/schemas/PdfModel"),
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
            'type'   => 'required|string|max:255',
        ]);

        try {
            $pdf = Pdf::create($request->all());
            return $this->success($pdf);
        } catch(Exception $e) {
            Log::error($e->getMessage());
            return $this->error('pdf not created');
        }
    }

    /**
     * @OA\Post(
     *     path="/pdf/{id}",
     *     tags={"Pdf"},
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
     *                     property="pdf",
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
    public function upload_file(Request $request, int $id)
    {
        $request->validate([
            'pdf' => 'required|file|mimes:pdf',
        ]);

        try {
            $blog = Pdf::findOrFail($id);
            $pdf = $request->pdf;
            $pdfName = time() . '-' . str()->random(32) . '.' . $pdf->getClientOriginalExtension();
            $pdf->storeAs('public/pdf', $pdfName);
            $blog->pdf = $pdfName;
            $blog->save();
            return $this->success(['PDF uploaded successfully']);
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
        return $this->error('PDF did not upload');
    }
}

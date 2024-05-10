<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

/**
* @OA\Info(
*     title="Admin OpenApi",
*     version="1.0.0",
*     description="this is a api documentation",
*     @OA\Contact(
*         name="mohammad hemmati",
*         email="m.kemeshki@gmail.com"
*     ),
*     @OA\License(
*         name="GPL-v3.0",
*         url="https://www.gnu.org/licenses/gpl-3.0.en.html"
*     )
* )
* @OA\Server(url=L5_SWAGGER_CONST_HOST)
* @OA\SecurityScheme(
*     type="http",
*     description="Login with username and password to get the authentication token <div>Example: Bearer token</div>",
*     name="Authorization",
*     in="header",
*     scheme="Bearer",
*     bearerFormat="JWT",
*     securityScheme="api_key",
* )
*/
abstract class Controller
{
    /**
     * @OA\Schema(
     *     schema="SuccessModel",
     *     title="Success Model",
     *     type="object",
     *     description="Success Model",
     *     @OA\Property(
     *         property="message",
     *         description="Success message",
     *         type="string",
     *         format="",
     *         example="my message",
     *     ),
     * )
     */
    public function success(mixed $data = [], int $status = 200): JsonResponse
    {
        return response()->json($data, $status);
    }

    /**
    * @OA\Schema(
    *     schema="ErrorModel",
    *     title="Error Model",
    *     type="object",
    *     description="Error Model",
    *     @OA\Property(
    *         property="message",
    *         description="error message",
    *         type="string",
    *         format="",
    *         example="my error message",
    *     ),
    *     @OA\Property(
    *         property="errors",
    *         description="errors",
    *         type="array",
    *         @OA\Items(type="string"),
    *         example={"error1", "error2"},
    *     ),
    * )
    */
    public function error(string $message = '', array $errors = [], int $status = 400): JsonResponse
    {
        $data = ['message' => $message, 'errors' => $errors ?: []];
        return response()->json($data, $status);
    }

    /**
     * @OA\Schema(
     *     schema="Previous",
     *     title="Previous",
     *     description="Represents a link",
     *     @OA\Property(
     *         property="url",
     *         type="string",
     *         description="Link URL",
     *         example=null
     *     ),
     *     @OA\Property(
     *         property="label",
     *         type="string",
     *         description="Link label",
     *         example="&laquo; Previous"
     *     ),
     *     @OA\Property(
     *         property="active",
     *         type="boolean",
     *         description="Indicates whether the link is active"
     *     )
     * )
     *
     * @OA\Schema(
     *     schema="Links",
     *     title="Links ",
     *     description="Represents an active link",
     *     @OA\Property(
     *         property="url",
     *         type="string",
     *         description="Link URL",
     *         example="http://your-url/api/category?page=0"
     *     ),
     *     @OA\Property(
     *         property="label",
     *         type="string",
     *         description="Link label",
     *         example="1"
     *     ),
     *     @OA\Property(
     *         property="active",
     *         type="boolean",
     *         description="Indicates whether the link is active",
     *         example=true
     *     )
     * )
     * @OA\Schema(
     *     schema="Next",
     *     title="Next",
     *     description="Represents an active link",
     *     @OA\Property(
     *         property="url",
     *         type="string",
     *         description="Link URL",
     *         example=null
     *     ),
     *     @OA\Property(
     *         property="label",
     *         type="string",
     *         description="Link label",
     *         example="Next &raquo;"
     *     ),
     *     @OA\Property(
     *         property="active",
     *         type="boolean",
     *         description="Indicates whether the link is active",
     *         example=false
     *     )
     * )
     */
    private function schema()
    {
    }
}

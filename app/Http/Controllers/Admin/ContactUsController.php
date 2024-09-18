<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactUs;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ContactUsController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth.admin'),
        ];
    }

    /**
    * @OA\Post(
    *     path="/admin/contact-us",
    *     summary="Submit a contact form",
    *     tags={"ContactUs"},
    *     @OA\RequestBody(
    *         required=true,
    *         @OA\JsonContent(
    *             @OA\Property(
    *                 property="subject",
    *                 type="string",
    *                 description="Subject of the message (min: 5 characters)"
    *             ),
    *             @OA\Property(
    *                 property="email",
    *                 type="string",
    *                 format="email",
    *                 description="Email address of the sender"
    *             ),
    *             @OA\Property(
    *                 property="message",
    *                 type="string",
    *                 description="Message content (min: 20 characters)"
    *             )
    *         )
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="Contact message successfully created",
    *         @OA\JsonContent(
    *             @OA\Property(
    *                 property="id",
    *                 type="integer",
    *                 description="ID of the created contact message"
    *             ),
    *             @OA\Property(
    *                 property="subject",
    *                 type="string",
    *                 description="Subject of the message"
    *             ),
    *             @OA\Property(
    *                 property="email",
    *                 type="string",
    *                 format="email",
    *                 description="Email address of the sender"
    *             ),
    *             @OA\Property(
    *                 property="message",
    *                 type="string",
    *                 description="Message content"
    *             )
    *         )
    *     ),
    *     @OA\Response(
    *         response=400,
    *         description="Validation error",
    *         @OA\JsonContent(
    *             @OA\Property(
    *                 property="error",
    *                 type="string",
    *                 description="Error message"
    *             )
    *         )
    *     ),security={{"api_key": {}}}
    * )
    */
    public function contact(Request $request)
    {
        $data = $request->validate([
            'subject'    => 'required|min:5',
            'email'      => 'required|email',
            'message'    => 'required|min:20',
        ]);
        try {
            $contact = ContactUs::create($request->all());
            return $this->success($contact);
        } catch (Exception $e) {
            $this->error('message not created');
        }
    }
}

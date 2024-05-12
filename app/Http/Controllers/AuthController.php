<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Rules\CheckOldPassword;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller implements HasMiddleware
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public static function middleware(): array
    {
        return [
            new Middleware('auth', except: ['login', 'register']),
        ];
    }

    /**
     * @OA\Post(
     *     path="/auth/register",
     *     tags={"Login & Register"},
     *     summary="register",
     *     description="register",
     *     operationId="register",
     *     @OA\Response(
     *         response="200",
     *         description="Success",
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Error",
     *     ),
     *     @OA\RequestBody(
     *         description="tasks input",
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="first_name",
     *                 type="string",
     *                 description="first_name",
     *                 example="mohammad"
     *             ),
     *             @OA\Property(
     *                 property="last_name",
     *                 type="string",
     *                 description="last_name",
     *                 default="null",
     *                 example="ahmadi"
     *             ),
     *             @OA\Property(
     *                 property="email",
     *                 type="string",
     *                 description="email",
     *                 example="test@example.com"
     *             ),
     *             @OA\Property(
     *                 property="password",
     *                 type="string",
     *                 description="password",
     *                 example="password"
     *             ),
     *             @OA\Property(
     *                 property="password_confirmation",
     *                 type="string",
     *                 description="password_confirmation",
     *                 example="password"
     *             )
     *
     *         )
     *     )
     * )
     *
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required|max:255',
            'last_name'  => 'required|max:255',
            'email'      => 'required|email|unique:users',
            'password'   => 'required|confirmed|min:6',
        ]);

        $user = User::create($request->all());
        return $this->success($user);
    }

    /**
     * @OA\Post(
     *     path="/auth/login",
     *     tags={"Login & Register"},
     *     summary="login",
     *     description="login",
     *     operationId="login",
     *     @OA\Response(
     *         response="200",
     *         description="Success",
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Error",
     *     ),
     *     @OA\RequestBody(
     *         description="tasks input",
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="email",
     *                 type="string",
     *                 description="email",
     *                 example="test@example.com"
     *             ),
     *             @OA\Property(
     *                 property="password",
     *                 type="string",
     *                 description="password",
     *                 default="null",
     *                 example="password2",
     *             )
     *         )
     *     )
     * )
     *
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $credentials = request(['email', 'password']);
        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->respondWithToken($token);
    }

    /**
     * @OA\Get(
     *     path="/auth/me",
     *     tags={"Login & Register"},
     *     summary="my info",
     *     description="my info",
     *     @OA\Response(
     *         response=200,
     *         description="Success Message"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="an ""unexpected"" error"
     *     ),security={{"api_key": {}}}
     * )
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return $this->success(auth()->user());
    }

    /**
    * @OA\Post(
    *     path="/auth/logout",
    *     tags={"Login & Register"},
    *     summary="logout",
    *     description="logout",
    *     operationId="logout",
    *     @OA\Response(
    *         response="200",
    *         description="Success",
    *     ),
    *     @OA\Response(
    *         response="400",
    *         description="Error",
    *     ),security={{"api_key": {}}}
    * )
    *
    * Log the user out (Invalidate the token).
    *
    * @return \Illuminate\Http\JsonResponse
    */
    public function logout()
    {
        auth()->logout();
        return $this->success(['message' => 'Successfully logged out']);
    }

    /**
    * @OA\Get(
    *     path="/auth/refresh",
    *     tags={"Login & Register"},
    *     summary="refresh",
    *     description="refresh a token",
    *     operationId="refresh",
    *     @OA\Response(
    *         response="200",
    *         description="Success",
    *     ),
    *     @OA\Response(
    *         response="400",
    *         description="Error",
    *     ),security={{"api_key": {}}}
    * )
    *
    * Refresh a token.
    *
    * @return \Illuminate\Http\JsonResponse
    */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        $auth = auth();
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => $auth->factory()->getTTL() * 60
        ]);
    }

    /**
    * @OA\Post(
    *     path="/auth/change-password",
    *     tags={"Login & Register"},
    *     summary="Change user password",
    *     description="Change user password",
    *     @OA\RequestBody(
    *         description="tasks input",
    *         required=true,
    *         @OA\JsonContent(
    *             @OA\Property(
    *                 property="current_password",
    *                 type="string",
    *                 description="current password",
    *                 example="******"
    *             ),
    *             @OA\Property(
    *                 property="new_password",
    *                 type="string",
    *                 description="new password",
    *                 example="******",
    *             ),
    *             @OA\Property(
    *                 property="new_password_confirmation",
    *                 type="string",
    *                 description="confirmation your password",
    *                 example="******",
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
    *     ),security={{"api_key": {}}}
    * )
    * change password
    */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password'     => 'required|confirmed',
        ]);

        /** @var \App\Models\User $user */
        $user = auth()->user();
        try {
            if (!Hash::check($request->current_password, $user->password)) {
                return $this->error('The current password is incorrect.');
            }
            $user->update(['password' => Hash::make($request->new_password)]);
            return $this->success(['message'=>'Password changed successfully']);
        } catch (Exception $e) {
            return $this->error('An error occurred while changing the password.');
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use RuntimeException;
use Tymon\JWTAuth\JWTGuard;

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
     *                 example="mehdi"
     *             ),
     *             @OA\Property(
     *                 property="last_name",
     *                 type="string",
     *                 description="last_name",
     *                 default="null",
     *                 example="abedi"
     *             ),
     *             @OA\Property(
     *                 property="email",
     *                 type="string",
     *                 description="email",
     *                 example="ali23@example.com"
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
        return response()->json($user);
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
     *                 example="password",
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
        return response()->json(auth()->user());
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

        return response()->json(['message' => 'Successfully logged out']);
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
        $guard = auth('api');
        if (!$guard instanceof JWTGuard) {
            throw new RuntimeException('Wrong guard returned.');
        }
        return $this->respondWithToken($guard->refresh());
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
        $guard = auth('api');
        if (!$guard instanceof JWTGuard) {
            throw new RuntimeException('Wrong guard returned.');
        }
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => $guard->factory()->getTTL() * 60
        ]);
    }

    public function getResource()
    {
        // ...
    }
}

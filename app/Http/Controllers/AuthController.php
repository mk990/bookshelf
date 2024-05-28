<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\Forgot;
use App\Models\ForgotPassword;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

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
            new Middleware('auth', except: ['login', 'register', 'verifyEmailAddress', 'forgotPassword', 'getForgotPassword', 'setForgotPassword']),
            new Middleware('signed', only: ['verifyEmailAddress']),
        ];
    }

    /**
    * @OA\Post(
    *     path="/auth/register",
    *     tags={"Authentication"},
    *     summary="register",
    *     description="register",
    *     operationId="register",
    *     @OA\Response(
    *         response=200,
    *         description="Success Message",
    *         @OA\JsonContent(ref="#/components/schemas/UserModel"),
    *     ),
    *     @OA\Response(
    *         response=400,
    *         description="an 'unexpected' error",
    *         @OA\JsonContent(ref="#/components/schemas/ErrorModel"),
    *     ),
    *     @OA\RequestBody(
    *         description="tasks input",
    *         required=true,
    *         @OA\JsonContent(
    *             @OA\Property(
    *                 property="first_name",
    *                 type="string",
    *                 description="first_name",
    *                 example="string"
    *             ),
    *             @OA\Property(
    *                 property="last_name",
    *                 type="string",
    *                 description="last_name",
    *                 default="null",
    *                 example="string"
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
    * @OA\Get(
    *     path="/auth/verify-email",
    *     tags={"Authentication"},
    *     summary="verify email",
    *     description="verify email",
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
    * Get the authenticated User.
    *
    * @return \Illuminate\Http\JsonResponse
    */
    public function verifyEmail()
    {
        /** @var User $user */
        $user = Auth::user();
        if ($user->hasVerifiedEmail()) {
            return $this->success('Email already verified.');
        }
        $user->sendEmailVerificationNotification();
        return $this->success($user);
    }

    public function verifyEmailAddress(Request $request)
    {
        $user = User::find($request->route('id'));

        if ($user->hasVerifiedEmail()) {
            return $this->success('Email already verified.');
        }

        if ($user->markEmailAsVerified()) {
            return $this->success($user);
        }

        return $this->error("$user->email could not be verified.");
    }

    /**
    * @OA\Post(
    *     path="/auth/login",
    *     tags={"Authentication"},
    *     summary="login",
    *     description="login",
    *     operationId="login",
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
    *     tags={"Authentication"},
    *     summary="my info",
    *     description="my info",
    *     @OA\Response(
    *         response=200,
    *         description="Success Message",
    *         @OA\JsonContent(ref="#/components/schemas/UserModel"),
    *     ),
    *     @OA\Response(
    *         response=400,
    *         description="an 'unexpected' error",
    *         @OA\JsonContent(ref="#/components/schemas/ErrorModel"),
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
    *     tags={"Authentication"},
    *     summary="logout",
    *     description="logout",
    *     operationId="logout",
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
    *     tags={"Authentication"},
    *     summary="refresh",
    *     description="refresh a token",
    *     operationId="refresh",
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
    *
    * Refresh a token.
    *
    * @return \Illuminate\Http\JsonResponse
    */
    public function refresh()
    {
        return $this->respondWithToken(Auth::refresh());
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
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => Auth::factory()->getTTL() * 60
        ]);
    }

    /**
    * @OA\Post(
    *     path="/auth/change-password",
    *     tags={"Authentication"},
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

        /** @var User $user */
        $user = Auth::user();
        try {
            if (!Hash::check($request->current_password, $user->password)) {
                return $this->error('The current password is incorrect.');
            }
            $user->update(['password' => Hash::make($request->new_password)]);
            return $this->success(['message'=>'Password changed successfully']);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return $this->error('An error occurred while changing the password.');
        }
    }

    /**
    * @OA\Post(
    *     path="/auth/forgotPassword",
    *     tags={"Authentication"},
    *     summary="Forgot user password",
    *     description="Forgot user password",
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
    *     )
    * )
    * forgot password
    */
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        try {
            $user = User::whereEmail($request->email)->first();
            if (!$user) {
                sleep(2);
                return $this->success(['message' => 'Password reset link sent to your email']);
            }
            ForgotPassword::where('email', $user->email)->delete();
            $token = Str::random(60);
            ForgotPassword::create(
                ['email' => $user->email, 'token' => $token, ]
            );

            if (!RateLimiter::tooManyAttempts('mail', 1, 300)) {
                Mail::to($user->email)->send(new Forgot($user, $token), 60);
                return $this->success(['message' => 'Password reset link sent to your email']);
            }
            return $this->error('Too many password reset requests. Please try again later.', status: 429);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return $this->error('Something went wrong');
        }
    }

    public function getForgotPassword(string $token)
    {
        return view('forgot_password', ['token' => $token]);
    }

    public function setForgotPassword(string $token)
    {
        try {
            $userToken = ForgotPassword::where('token', $token)->latest()->firstOrFail();
            if (!$userToken) {
                return $this->error('invalid token');
            }
            $user = User::whereEmail($userToken->email)->firstOrFail();
            if (!$user) {
                return $this->error('user not found');
            }
            $user->update(['password' => bcrypt(request('password'))]);
            return $this->success($user);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return $this->error('Password change failed');
        }
    }
}

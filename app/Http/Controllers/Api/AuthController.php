<?php

namespace App\Http\Controllers\Api;

use App\Events\Registered;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;

class AuthController extends Controller
{

    /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Register a new user",
     *     description="Creates a new user account and sends a verification email",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","password","password_confirmation"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User registered successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User registered successfully! Please check your email for verification.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation failed",
     *         @OA\JsonContent(
     *             @OA\Property(property="errors", type="object", example={"email": {"The email has already been taken."}})
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Something went wrong")
     *         )
     *     )
     * )
     */

    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            event(new Registered($user));


            return response()->json([
                'message' => 'User registered successfully! Please check your email for verification.',

            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }


    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Login a user",
     *     description="Authenticates a user and returns a Bearer token. Email must be verified to login.",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Login successful"),
     *             @OA\Property(property="token", type="string", example="1|Xdfge91HJKL..."),
     *             @OA\Property(
     *                 property="user",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", example="john@example.com")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - Incorrect email or password",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Incorrect email or password. Please try again.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Email not verified",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Your email is not verified. Please verify your email first.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="errors", type="object", example={
     *                 "email": {"The selected email is invalid or does not exist."},
     *                 "password": {"The password field is required."}
     *             })
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Something went wrong")
     *         )
     *     )
     * )
     */

    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email|max:255|exists:users',
                'password' => 'required|string|min:8',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $credentials = $request->only('email', 'password');

            if (auth()->attempt($credentials)) {
                $user = auth()->user();

                // Check if email is verified
                if (!$user->hasVerifiedEmail()) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Your email is not verified. Please verify your email first.'

                    ], 403);
                }

                $token = $user->createToken('auth_token')->plainTextToken;
                return response()->json([
                    'status' => true,
                    'message' => 'Login successful',
                    'token' => $token,
                    'user' => $user
                ], 200);
            }

            return response()->json(['error' => 'Incorrect email or password. Please try again.'], 401);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }
    /**
     * @OA\Post(
     *     path="/api/logout",
     *     summary="Logout the authenticated user",
     *     description="Revokes the current access token used by the authenticated user.",
     *     tags={"Auth"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="User logged out successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Logged out successfully.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated - Invalid or missing token",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     )
     * )
     */

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully.']);
    }
}

<?php

namespace App\Http\Controllers;

use App\Constants\AppConstants;
use App\Http\Requests\SignupRequest;
use App\Http\Responses\Response;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as StatusCode;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * @OA\Tag(
 *     name="Authentication",
 *     description="Endpoints for user authentication"
 * )
 */
class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/v1/signup",
     *     summary="Create a new user",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             required={"phone", "password"},
     *             @OA\Property(property="phone", type="string", example="1234567890"),
     *             @OA\Property(property="password", type="string", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User created successfully"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function signup(SignupRequest $request)
    {
            // Create User
            $user = User::create([
                'phone' => $request->phone,
                'password' => bcrypt($request->password),
            ]);

            // Generate Token
            $token = JWTAuth::claims(['userId' => $user->id])->attempt($request->only('phone', 'password'));

            return Response::create()
                ->setMessage(__(AppConstants::RESPONSE_CODES_MESSAGES[AppConstants::APP_1008]))
                ->setStatusCode(StatusCode::HTTP_CREATED)
                ->setData(['token' => $token])
                ->setResponseCode(AppConstants::APP_1008)
                ->success();
        
    }

    /**
     * @OA\Post(
     *     path="/api/v1/login",
     *     summary="Login user",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             required={"phone", "password"},
     *             @OA\Property(property="phone", type="string", example="1234567890"),
     *             @OA\Property(property="password", type="string", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login successful"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function login(Request $request)
    {
        // Attempt to Authenticate
        if (!$token = JWTAuth::attempt($request->only('phone', 'password'))) {
            return Response::create()
                ->setResponseCode(AppConstants::APP_1007)
                ->setMessage(__(AppConstants::RESPONSE_CODES_MESSAGES[AppConstants::APP_1007]))
                ->setStatusCode(StatusCode::HTTP_UNAUTHORIZED)
                ->failure();
        }
        // Retrieve user based on phone number
        $user = User::where('phone', $request->phone)->first();

        // Generate Token with additional claims
        $token = JWTAuth::claims(['userId' => $user->id])->attempt($request->only('phone', 'password'));
        return Response::create()
            ->setMessage(__(AppConstants::RESPONSE_CODES_MESSAGES[AppConstants::APP_1009]))
            ->setStatusCode(StatusCode::HTTP_OK)
            ->setData(['token' => $token])
            ->setResponseCode(AppConstants::APP_1009)
            ->success();
    }

    /**
     * @OA\Post(
     *     path="/api/v1/logout",
     *     summary="Logout user",
     *     tags={"Authentication"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Logout successful"
     *     )
     * )
     */
    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return Response::create()
            ->setMessage(__(AppConstants::RESPONSE_CODES_MESSAGES[AppConstants::APP_1010]))
            ->setStatusCode(StatusCode::HTTP_OK)
            ->setResponseCode(AppConstants::APP_1010)
            ->success();
    }
}

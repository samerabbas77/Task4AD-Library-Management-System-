<?php

namespace App\Http\Controllers\Ath;

use App\Models\User;
use App\Service\Auth\AuthServices;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\loginRequest;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Auth\registerRequest;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthServices $authService)
    {
        
        $this->authService = $authService;
    }
    /**
     * Summary of register
     * @param \App\Http\Requests\Auth\registerRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function register(registerRequest $request) {
        $validator =  $request->validated();

        $user =  $this->authService->registerService($validator);

        return response()->json($user, 201);
    }
    //.............................................................................................
    //.............................................................................................
    /**
     * Summary of login
     * @param \App\Http\Requests\Auth\loginRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function login(loginRequest $request)
    {
        $credentials = $request->validated();

        $token = $this->authService->loginService($credentials);

        return $this->respondWithToken($token);
    }
    //.............................................................................................
    //.............................................................................................
    /**
     *  return the authenticated user's information as a JSON response
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth('api')->user());
    }
    //.............................................................................................
    //.............................................................................................

    /**
     * Summary of logout
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $this->authService->logoutService();

        return response()->json(['message' => 'Successfully logged out']);
    }
    //.............................................................................................
    //.............................................................................................
    /**
     *  refresh the token
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(JWTAuth::refresh());
    }
    //.............................................................................................
    //.............................................................................................

    /**
     * generates a JSON response that includes the token and some additional information about it
     * The (expires_in )key provides the token's lifespan in seconds.
     *JWTAuth::factory()->getTTL() returns the Time-To-Live (TTL) of the token in minutes.
     *The code multiplies this TTL by 60 to convert it to seconds, which is a standard way of expressing token expiration time in many APIs
     * @param mixed $token
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60
        ]);
    }
}
<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;

class AuthController extends Controller
{
    /**
     * Регистрация пользователя
     *
     * @param UserRegisterRequest $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function register(UserRegisterRequest $request) {
        $validated = $request->validated();

        $user = User::forceCreate([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $token = $this->createToken($user);
        Auth::login($user);

        return response()->json(['api_token' => $token], 200);
    }

    /**
     * Аутентификация пользователя
     *
     * @param UserLoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function login(UserLoginRequest $request) {
        $validated = $request->validated();

        $user = User::firstWhere('email',$validated['email']);

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'message' => 'incorrect email or password'
            ], 401);
        }

        $token = $this->createToken($user);
        Auth::login($user);

        return response()->json(['api_token' => $token], 200);
    }

    /**
     * Выход пользователя из системы
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function logout(\Illuminate\Http\Request $request) {
        $user = User::where('api_token', $request->bearerToken())->first();
        $user->tokens()->delete();

        return response()->json([], 204);
    }

    /**
     * Генерирует и сохраняет токен Laravel Sanctum
     *
     * @param User $user
     * @return \Laravel\Sanctum\string|string
     */

    private function createToken(User $user) {

        $token = $user->createToken('api_token')->plainTextToken;
        $user->api_token = $token;
        $user->save();

        return $token;
    }
}

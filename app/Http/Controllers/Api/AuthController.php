<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * Autenticación de la API privada (tokens de Laravel Sanctum).
 *
 * login  -> público, entrega el token
 * me     -> privado (middleware auth:sanctum)
 * logout -> privado (middleware auth:sanctum)
 */
class AuthController extends Controller
{
    /**
     * POST /api/login
     * Entrega un token personal para consumir la API privada.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'       => 'required|email',
            'password'    => 'required|string',
            'device_name' => 'nullable|string|max:255',
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales no son correctas.'],
            ]);
        }

        $token = $user->createToken(
            $credentials['device_name'] ?? 'api-token'
        )->plainTextToken;

        return response()->json([
            'message'    => 'Sesión iniciada correctamente.',
            'token_type' => 'Bearer',
            'token'      => $token,
            'user'       => [
                'id'            => $user->id,
                'name'          => $user->name,
                'email'         => $user->email,
                'rol'           => $user->rol,
                'department_id' => $user->department_id,
            ],
        ], 200);
    }

    /**
     * GET /api/me
     * Devuelve el usuario dueño del token enviado.
     */
    public function me(Request $request)
    {
        return response()->json(
            $request->user()->load('department')
        );
    }

    /**
     * POST /api/logout
     * Revoca únicamente el token con el que se hizo la petición.
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Sesión cerrada, token revocado.',
        ], 200);
    }
}

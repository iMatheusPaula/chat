<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function login(Request $request): Response
    {
        $loginData = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if(!auth()->attempt($loginData)){
            return response()->json(['error' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }
        $request->session()->regenerate();
        return response()->json('Authorized', Response::HTTP_OK);
    }

    public function logout(Request $request): Response
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return response()->json('Logged out', Response::HTTP_OK);
    }

}

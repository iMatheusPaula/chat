<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $registerData = $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required|confirmed',
            ]);
            $user = User::create([
                'name' => $registerData['name'],
                'email' => $registerData['email'],
                'password' => Hash::make($registerData['password']),
            ]);
            return response()->json($user, Response::HTTP_CREATED);
        } catch (\Exception $e){
            return response()->json($e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request): JsonResponse
    {
        try{
            return response()->json($request->user(), Response::HTTP_OK);
        } catch (\Exception $e){
            return response()->json($e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}

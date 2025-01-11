<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
     * Display the specified user.
     */
    public function show(int $id): JsonResponse
    {
        try{
            $authUser = Auth::user()->id;
            $response = User::query()
                ->where('id', '=', $id)
                ->firstOrFail();
            return response()->json($response, Response::HTTP_OK);
        } catch (\Exception $e){
            return response()->json($e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Search for users by name
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        try{
            $request->validate(['name' => 'string']);
            $response = User::query()
                ->where('name', 'like', '%'.$request->input('name').'%')
                ->get();

            return response()->json($response, Response::HTTP_OK);
        } catch (\Exception $e){
            return response()->json($e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove user
     */
    public function destroy(int $id): JsonResponse
    {
        try{
            $authUser = Auth::user()->id;

            if($authUser !== $id)
                return response()->json('Unauthorized', Response::HTTP_UNAUTHORIZED);

            $user = User::query()
                ->where('id', '=', $authUser)
                ->findOrFail($id);

            if(!$user)
                return response()->json('User not found', Response::HTTP_NOT_FOUND);

            $user->delete();

            return response()->json('', Response::HTTP_NO_CONTENT);
        } catch (\Exception $e){
            return response()->json($e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}

<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try{
            $authUser = Auth::user()->id;
            $response = Contact::where('user_id', '=', $authUser)->get();
            return response()->json($response, Response::HTTP_OK);
        } catch (\Exception $e){
            return response()->json($e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a new contact
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate(['id' => 'required|int|exists:users,id']);
            $authUserId = Auth::user()->id;

            $contact = new Contact();
            //TODO: ver como vai ficar logica de criação de contato
            $contact->save();
            return response()->json('success', Response::HTTP_CREATED);
        } catch (\Exception $e){
            return response()->json($e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try{
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'nullable|string|email|max:255',
                'phone' => 'nullable|string|max:255',
                'image' => 'nullable|image|mimes:jpeg,png,jpg',
            ]);
            $authUser = Auth::user()->id;
            $contact = Contact::where('user_id', '=', $authUser)->findOrFail($id);
            if($contact){
                $contact->name = $request->input('name');
                $contact->phone = $request->input('phone');
                $contact->email = $request->input('email');
                if ($request->hasFile('image')) {
                    if ($contact->image) Storage::disk('public')->delete($contact->image);
                    $path = $request->file('image')->store('images', 'public');
                    $contact->image = $path;
                }
                $contact->save();
                return response()->json('success', Response::HTTP_OK);
            }
        }
        catch (\Exception $e){
            return response()->json($e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        try{
            $authUser = Auth::user()->id;
            $contact = Contact::where('user_id', $authUser)->findOrFail($id);
            if($contact->image) Storage::disk('public')->delete($contact->image);
            $contact->delete();
            return response()->json('', Response::HTTP_NO_CONTENT);
        }
        catch (\Exception $e){
            return response()->json($e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }
}

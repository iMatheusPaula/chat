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
     * TODO rever quando tiver a feature de adicionar amigo
     */
    public function index(Request $request): JsonResponse
    {
        try{
            $response = Contact::with(['user:name,email,phone,image'])->get();
            return response()->json($response, Response::HTTP_OK);
        } catch (\Exception $e){
            return response()->json($e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specific user.
     */
    public function show(int $id): JsonResponse
    {
        try {
            $response = Contact::query()
                ->where('id', '=', $id)
                ->firstOrFail();
            return response()->json($response, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * Store a new contact
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'id' => 'required|exists:users,id', // user_id deve existir na tabela users
            ]);

            $contact = Contact::create([
                'user_id' => $validated['id'],
            ]);

            return response()->json(['message' => 'success', 'contact' => $contact], Response::HTTP_CREATED);
        } catch (\Exception $e){
            return response()->json($e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        try{
            $authUser = Auth::user()->id;

            $contact = Contact::query()
                ->where('user_id', $authUser)
                ->findOrFail($id);

            if(!$contact)
                return response()->json('Contact not found', Response::HTTP_NOT_FOUND);

            if($contact->image) Storage::disk('public')->delete($contact->image);
            $contact->delete();

            return response()->json('', Response::HTTP_NO_CONTENT);
        }
        catch (\Exception $e){
            return response()->json($e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }
}

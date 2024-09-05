<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    public function index()
    {
        return Contact::with(['author'])->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'content' => 'required|string',
        ]);

        // Get the authenticated user's ID
        $authorId = Auth::id();

        // Create a new contact with the authenticated user's ID as author_id
        $contact = Contact::create([
            'name' => $request->input('name'),
            'content' => $request->input('content'),
            'author_id' => $authorId, // Set the author_id to the authenticated user's ID
        ]);

        return response()->json($contact, 201);
    }

    public function show($id)
    {
        return Contact::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $contact = Contact::findOrFail($id);
        $contact->update($request->all());

        return response()->json($contact);
    }

    public function destroy($id)
    {
        Contact::findOrFail($id)->delete();

        return response()->json(['message' => 'Contact deleted successfully']);
    }
}


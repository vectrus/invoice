<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;



class ContactController extends Controller
{
    public function index()
    {

        $paginatedContacts = Contact::with('clients')->latest()->paginate(10);
        return view('contacts.index', [
            'paginatedContacts' => $paginatedContacts,
            'contacts' => $paginatedContacts->items()
        ]);
    }

    /**
     * Show the form for creating a new contact.
     */
    public function create()
    {
        $clients = \App\Models\Client::orderBy('companyname')->get();
        return view('contacts.create', compact('clients'));
    }

    /**
     * Store a newly created contact.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|unique:contacts,email',
            'phonenumber' => 'required|string|max:20',
            'contactinfo' => 'nullable|string|max:1000',
            'client_id' => 'nullable|exists:clients,id'
        ]);

        $contact = Contact::create($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Contact created successfully',
                'contact' => $contact
            ], 201);
        }

        return redirect()->route('contacts.index')
            ->with('success', 'Contact created successfully.');
    }

    /**
     * Display the specified contact.
     */
    public function show(Contact $contact)
    {
        $contact->load('clients');
        return view('contacts.show', compact('contact'));
    }

    /**
     * Show the form for editing the specified contact.
     */
    public function edit(Contact $contact)
    {
        $clients = \App\Models\Client::orderBy('companyname')->get();
        return view('contacts.edit', compact('contact', 'clients'));
    }

    /**
     * Update the specified contact.
     */
    public function update(Request $request, Contact $contact)
    {
        $validated = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('contacts')->ignore($contact->id),
            ],
            'phonenumber' => 'required|string|max:20',
            'contactinfo' => 'nullable|string|max:1000',
            'client_id' => 'nullable|exists:clients,id'
        ]);

        $contact->update($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Contact updated successfully',
                'contact' => $contact
            ]);
        }

        return redirect()->route('contacts.index')
            ->with('success', 'Contact updated successfully.');
    }


    /**
     * Remove the specified contact.
     */
    public function destroy(Contact $contact)
    {
        $contact->delete();

        if (request()->wantsJson()) {
            return response()->json([
                'message' => 'Contact deleted successfully'
            ]);
        }

        return redirect()->route('contacts.index')
            ->with('success', 'Contact deleted successfully.');
    }

    /**
     * Search for contacts.
     */
    public function search(Request $request)
    {
        $search = $request->get('search');

        $contacts = Contact::where('firstname', 'like', "%{$search}%")
            ->orWhere('lastname', 'like', "%{$search}%")
            ->orWhere('email', 'like', "%{$search}%")
            ->limit(10)
            ->get();

        return response()->json($contacts);
    }

    /**
     * Get a specific contact by ID.
     */
    public function getContact($id)
    {
        $contact = Contact::findOrFail($id);
        return response()->json($contact);
    }
}


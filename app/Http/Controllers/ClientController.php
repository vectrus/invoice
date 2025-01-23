<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Contact;
use App\Models\Client;
use App\Models\ClientFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class ClientController extends Controller
{
    public function __construct()
    {
        if (Auth::check()) {
            // The user is logged in...
           // exit();
        }
        else {
            die();
        }


    }

    public function index(Request $request)
    {
        //
        $clientsearch = '';
        if ($request->has('clientsearch')) {
            //$posts = Post::search($request->get('search'))->get();
            //$clients = Client::search($request->get('clientsearch'))->paginate(20);
            $clients = CLient::sortable()->where('companyname', 'LIKE', $request->clientsearch)->orderBy('companyname', 'ASC')->paginate(20);
//            /$clients = Client::search(['roepnaam', 'achternaam'], $request->clientsearch)->paginate(20);
            $clientsearch = $request->clientsearch;
            //$assets = Asset::orderBy('id', 'desc')->paginate(20);
        } else {
            //$assets = Client::with('assetlogs')->orderBy('clients.id', 'desc')->paginate(20);
            // AssetLog::with('assetlogs.asset_id')->where('asset_id', $asset->id)->latest()->get();
            $clients = Client::sortable()->orderBy('companyname', 'asc')->paginate(10);
        }


        return view('clients.index', compact('clients', 'clientsearch'));
    }

    public function search(Request $request)
    {
        //
        $clientsearch = '';
        if ($request->has('clientsearch')) {
            //$posts = Post::search($request->get('search'))->get();
            //$clients = Client::search($request->get('clientsearch'))->paginate(20);
            // $clients = CLient::sortable()->whereRaw("LOWER"('{$companyname}'), 'LIKE', $request->clientsearch.'%')->paginate(20);
            $clients = CLient::sortable()->whereRaw('LOWER(`companyname`) LIKE ? ', $request->clientsearch . '%')->orderBy('companyname', 'ASC')->paginate(20);
//            /$clients = Client::search(['roepnaam', 'achternaam'], $request->clientsearch)->paginate(20);
            $clientsearch = $request->clientsearch;
            //$assets = Asset::orderBy('id', 'desc')->paginate(20);
        } else {
            //$assets = Client::with('assetlogs')->orderBy('clients.id', 'desc')->paginate(20);
            // AssetLog::with('assetlogs.asset_id')->where('asset_id', $asset->id)->latest()->get();
            $clients = Client::sortable()->orderBy('companyname', 'asc')->paginate(20);
        }


        return view('clients.index', compact('clients', 'clientsearch'));
    }

    public function create()
    {
        $contacts = Contact::orderBy('firstname')->get();
        return view('clients.create', compact('contacts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'companyname' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'postalcode' => 'required|string|max:10',
            'city' => 'required|string|max:255',
            'phonenumber' => 'required|string|max:20',
            'mobile' => 'nullable|string|max:20',
            'email' => 'required|email|max:255',
            'invoiceaddress' => 'nullable|string|max:255',
            'invoicepostalcode' => 'nullable|string|max:10',
            'invoicecity' => 'nullable|string|max:255',
            'memo' => 'nullable|string',
            'contact_ids' => 'nullable|array',
            'contact_ids.*' => 'exists:contacts,id',
            'primary_contact_id' => 'nullable|exists:contacts,id'
        ]);

        return DB::transaction(function () use ($validated, $request) {
            // Create the client
            $client = Client::create($validated);

            // Attach selected contacts
            if ($request->has('contact_ids')) {
                $client->contacts()->attach($request->contact_ids);
            }

            // Set primary contact if selected
            if ($request->filled('primary_contact_id')) {
                $client->update(['primary_contact_id' => $request->primary_contact_id]);
            }

            return redirect()->route('client.index')
                ->with('success', 'Client successfully created.');
        });
    }

    public function quickStore(Request $request)
    {
       // Log::info($request);

        $validated = $request->validate([
            'companyname' => 'required',
            'phonenumber' => 'required',
            'email' => 'required',
            'address' => 'nullable',




        ]);



        $newClient = Client::create($validated);
            return response()->json($newClient, 201);
        //return redirect()->route('client.index')->with('success', 'Klant succesvol aangemaakt');

        //]);
        //return redirect()->route('client.index')->with('success', 'Klant succesvol aangemaakt');
    }

    public function edit($id)
    {


            $client = CLient::whereId($id)->with('contacts')->first();
            $primary_contact = Contact::whereId($client->primary_contact_id)->first();


        return view('clients.edit', compact('client', 'primary_contact'));
    }

    public function update(Request $request, $id)
    {


        $validated = $request->validate([
            'companyname' => 'required',
            'address' => 'required',
            'postalcode' => 'required',
            'city' => 'required',
            'phonenumber' => 'required',
            'email' => 'required|email',
            'mobile' => 'present|nullable',
            'invoiceaddress' => 'present|nullable',
            'invoicepostalcode' => 'present|nullable',
            'invoicecity' => 'present|nullable',
            'memo' => 'present|nullable',
           /* 'primary_contact_id' => 'present|nullable',*/


        ]);


        $client = Client::whereId($id)->update($validated);


        //return view('clients.edit', compact('client'));
        return \Redirect::back()->with('success', 'Klant geupdate');
    }

    public function updatePrimaryContact(Request $request, $clientId)
    {

        $request->validate([
            'contact_id' => 'required|exists:contacts,id'
        ]);

        try {
            $client = Client::findOrFail($clientId);
           // Log::error($request->contact_id);
            DB::table('clients')->where( 'id', $clientId)->update([
                'primary_contact_id' => $request->contact_id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Primary contact updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating primary contact'
            ], 500);
        }
    }

    public function destroy(Client $client)
    {
        try {
            DB::beginTransaction();


            // Delete the invoice
            $client->delete();

            DB::commit();

            return redirect()
                ->route('client.index')
                ->with('success', 'Client verwijderd');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Client verwijder Error:', [
                'client_id' => $$client->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()
                ->withErrors(['error' => 'Error deleting client: ' . $e->getMessage()]);
        }
    }

    public function uploadFiles(Request $request, $clientId)
    {
        $request->validate([
            'files.*' => 'required|file|max:10240'
        ]);

        foreach ($request->file('files') as $file) {
            $path = $file->store('client-files/' . $clientId, 'public');

            ClientFile::create([
                'client_id' => $clientId,
                'name' => $file->getClientOriginalName(),
                'path' => $path,
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize()
            ]);
        }

        return back()->with('success', 'Bestanden geüpload');
    }

    public function deleteFile(Request $request, $clientId, $fileId)
    {
        $file = ClientFile::findOrFail($fileId);
        Storage::disk('public')->delete($file->path);
        $file->delete();

        return back()->with('success', 'Bestand verwijderd');
    }
}

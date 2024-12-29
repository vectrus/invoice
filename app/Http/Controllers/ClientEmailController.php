<?php

namespace App\Http\Controllers;

use App\Mail\ClientMail;
use App\Models\Client;
use App\Models\ClientEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ClientEmailController extends Controller
{
    public function index(Client $client)
    {
        $emails = $client->emails()
            ->orderBy('sent_at', 'desc')
            ->paginate(10);

        return view('clients.emails.index', compact('client', 'emails'));
    }

    public function store(Request $request, Client $client)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'recipient_email' => 'required|email'
        ]);

        $email = $client->emails()->create([
            'subject' => $validated['subject'],
            'body' => $validated['body'],
            'recipient_email' => $validated['recipient_email'],
            'sender_email' => config('settings.email'),
            'sent_at' => now(),
            'status' => 'sent'
        ]);

        // Here you would typically integrate with your email service
         Mail::to($validated['recipient_email'])->send(new ClientMail($email, $client));

        return redirect()
            ->route('client.emails.index', $client)
            ->with('success', 'Email sent successfully');
    }

    public function show(Client $client, ClientEmail $email)
    {
        return view('clients.emails.show', compact('client', 'email'));
    }
}

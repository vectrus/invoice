<?php

namespace App\Mail;

use App\Models\ClientEmail;
use App\Models\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ClientMail extends Mailable
{
    use Queueable, SerializesModels;

    public $clientEmail;
    public $client;


    public function __construct(ClientEmail $clientEmail, Client $client)
    {
        $this->clientEmail = $clientEmail;
        $this->client = $client;

    }

    public function build()
    {
        return $this->subject($this->clientEmail->subject)
            ->view('emails.clientmail');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->clientEmail->subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.clientmail',
            with: [
                'client' => $this->client,
                'email' => $this->clientEmail,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

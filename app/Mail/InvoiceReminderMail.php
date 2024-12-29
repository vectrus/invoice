<?php

namespace App\Mail;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvoiceReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $invoice;
    public $pdfPath;

    public function __construct(Invoice $invoice, $pdfPath)
    {
        $this->invoice = $invoice;
        $this->pdfPath = $pdfPath;
    }

    public function build()
    {
        return $this->subject('Herinnering: factuur ' . $this->invoice->invoice_number . ' betaaldatum overschreden')
            ->view('emails.invoice-reminder')
            ->attach($this->pdfPath, [
                'as' => 'factuur-' . $this->invoice->invoice_number . '.pdf',
                'mime' => 'application/pdf',
            ]);
    }
}

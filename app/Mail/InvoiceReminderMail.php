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
        return $this->subject('Reminder: Invoice ' . $this->invoice->invoice_number . ' Payment Due')
            ->view('emails.invoice-reminder')
            ->attach($this->pdfPath, [
                'as' => 'invoice-' . $this->invoice->invoice_number . '.pdf',
                'mime' => 'application/pdf',
            ]);
    }
}

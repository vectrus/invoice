<x-mail::message>
    # Invoice Reminder

    Dear {{ $invoice->client->companyname }},

    This is a friendly reminder that invoice #{{ $invoice->invoice_number }}
    for {{ number_format($invoice->amount_incl, 2) }} EUR is due on {{ $invoice->due_date->format('d/m/Y') }}.

    Please ensure timely payment to avoid any late fees. You can find the invoice attached to this email.

    For your convenience, you can also pay directly using the following payment details:
    IBAN: {{ $settings['iban'] ?? 'N/A' }}
    Amount: {{ number_format($invoice->amount_incl, 2) }} EUR
    Reference: {{ $invoice->invoice_number }}

    If you have already made the payment, please disregard this reminder and accept our thanks.

    Best regards,
    {{ $settings['company_name'] ?? 'Your Company Name' }}

</x-mail::message>

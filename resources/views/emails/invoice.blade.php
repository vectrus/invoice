<!DOCTYPE html>
<html>
<head>
    <title>Factuur Vectrus Internet</title>
</head>
<body>
<h2>Beste {{ $invoice->client->companyname }},</h2>

<p>In de bijlage vindt u uw factuur #{{ $invoice->invoice_number }}.</p>

<p>Factuur informatie:</p>
<ul>
    <li>Factuur nummer: {{ $invoice->invoice_number }}</li>
    <li>Datum: {{ $invoice->issue_date->format('d/m/Y') }}</li>
    <li>Te betalen voor: {{ $invoice->due_date->format('d/m/Y') }}</li>
    <li>Bedrag: {{ number_format($invoice->amount_incl, 2) }}</li>
</ul>

<p>Bedankt voor uw vertrouwen!</p>

<p>Vriendelijke groet,<br>
    Vectrus Internet <br>

    </br>julius keijzer</p>
</body>
</html>

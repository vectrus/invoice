<!DOCTYPE html>
<html>
<head>
    <title>Bericht San Reklame</title>
</head>
<body>
<p>Beste {{ $client->companyname }},</p>



{{--<p>Factuur informatie:</p>
<ul>
    <li>Factuur nummer: {{ $invoice->invoice_number }}</li>
    <li>Datum: {{ $invoice->issue_date->format('d/m/Y') }}</li>
    <li>Te betalen voor: {{ $invoice->due_date->format('d/m/Y') }}</li>
    <li>Bedrag: {{ number_format($invoice->amount_incl, 2) }}</li>
</ul>--}}
<p>{!! $email->body !!}</p>


<p>Vriendelijke groet,<br>
    San Reklame <br>

    </br>Dennis</p>
</body>
</html>

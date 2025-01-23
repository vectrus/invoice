<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArchivedInvoice extends Model
{
    protected $fillable = [
        'invoice_date',
        'amount_incl',
        'amount_excl',
        'tax_amount',
        'description',
        'client_id',
        'client_email',
        'client_name',
        'invoice_number'
    ];

    protected $casts = [
        'invoice_date' => 'date'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}

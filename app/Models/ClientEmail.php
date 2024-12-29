<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClientEmail extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'subject',
        'body',
        'sent_at',
        'sender_email',
        'recipient_email',
        'status'
    ];

    protected $casts = [
        'sent_at' => 'datetime'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;
    protected $fillable = ['firstname', 'lastname', 'email', 'phonenumber', 'contactinfo', 'client_id'];

    public function clients()
    {
        //return $this->hasMany(AssetLog::class);
        return $this->belongsTo(\App\Models\Client::class);
    }
}

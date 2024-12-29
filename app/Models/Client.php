<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Client extends Model
{
    use HasFactory, Sortable;

    protected $fillable = ['companyname',
        'address',
        'postalcode',
        'city',
        'phonenumber',
        'email',
        'mobile',
        'invoiceaddress',
        'invoicepostalcode',
        'invoicecity',
        'memo',
        'fax',
        'contact_id'];

    public $sortable = ['id',
        'companyname',
        'email',
        'created_at',
        'updated_at'];

    public function contacts()
    {
        //return $this->hasMany(AssetLog::class);
        return $this->hasMany(\App\Models\Contact::class);
    }


    public function invoices()
    {
        //return $this->hasMany(AssetLog::class);
        return $this->hasMany(\App\Models\Invoice::class);
    }

    public function emails()
    {
        return $this->hasMany(ClientEmail::class);
    }
}

<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Invoice extends Model
{
    use HasFactory, sortable;

    protected $fillable = [
        'client_id',
        'invoice_number',
        'issue_date',
        'due_date',
        'status',
        'notes',
        'amount_excl',
        'amount_incl'
    ];
    public $sortable = ['id',

        'client_id',
        'invoice_number',
        'issue_date',
        'due_date',
        'status',
        'notes',
        'amount_excl',
        'amount_incl'
    ];



    protected $casts = [
        'issue_date' => 'date',
        'due_date' => 'date',
        'amount_excl' => 'decimal:2',
        'amount_incl' => 'decimal:2',
    ];

    // Add this if you want to be able to access invoice_number without it being in the database yet
    protected $attributes = [
        'invoice_number' => null,
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function getTotalExclAttribute()
    {
        return $this->items->sum('subtotal');
    }

    public function getTotalTaxAttribute()
    {
        return $this->items->sum('tax_amount');
    }

    /*public function getTotalTaxAttribute()
    {
        return $this->items->sum('tax_amount');
    }*/

    public function getTotalAttribute()
    {
        return $this->items->sum('total');
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(InvoiceTemplate::class);
    }



    public function calculateTotal(): float
    {
        return $this->items->sum(function ($item) {
            $subtotal = $item->price * $item->quantity;
            $tax = $subtotal * ($item->tax_percentage / 100);
            return $subtotal + $tax;
        });
    }

    public function generatePaymentUrl()
    {
        $settings = [];
        $rawsettings = Setting::where('group', '=', 'company')->get();
        foreach ($rawsettings as $key => $value) {
            //dd($value['key']);
            //$settings->$value['key'] = $value['value'];
            $settings[$value['key']] =
                $value['value'];
        }

        //dd($this->client->companyname);

        return "bank://payment?iban={$settings['iban']}"
            . "&amount={$this->amount_incl}"
            . "&reference={$this->invoice_number}"
            . "&name=" . urlencode($this->client->companyname);
    }


}

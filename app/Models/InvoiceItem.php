<?php

namespace App\Models;

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceItem extends Model
{
    protected $fillable = [
        'invoice_id',
        'name',
        'description',
        'quantity',
        'price',
        'tax_percentage'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
        'tax_percentage' => 'decimal:2'
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function calculateSubtotal(): float
    {
        return $this->price * $this->quantity;
    }

    public function calculateTax(): float
    {
        return $this->calculateSubtotal() * ($this->tax_percentage / 100);
    }

    public function calculateTotal(): float
    {
        return $this->calculateSubtotal() + $this->calculateTax();
    }

    public function getTaxAmountAttribute()
    {
        return $this->subtotal * ($this->tax_percentage / 100);
    }
}

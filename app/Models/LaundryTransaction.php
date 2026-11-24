<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaundryTransaction extends Model
{
    protected $fillable = [
        'transaction_code', 'customer_id', 'laundry_service_id', 'weight',
        'price_per_kg', 'total_amount', 'entry_date', 'estimated_completion',
        'status', 'payment_status', 'unit_type', 'quantity', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'weight' => 'decimal:2',
            'quantity' => 'decimal:2',
            'price_per_kg' => 'integer',
            'total_amount' => 'integer',
            'entry_date' => 'date',
            'estimated_completion' => 'date',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function laundryService(): BelongsTo
    {
        return $this->belongsTo(LaundryService::class);
    }
}

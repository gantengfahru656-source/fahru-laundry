<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LaundryService extends Model
{
    protected $fillable = ['name', 'price_per_kg', 'price_per_unit', 'unit_type', 'estimated_time', 'description'];

    protected function casts(): array
    {
        return ['price_per_kg' => 'integer', 'price_per_unit' => 'integer'];
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(LaundryTransaction::class);
    }
}

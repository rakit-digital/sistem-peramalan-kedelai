<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SoybeanStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'purchase_kg',
        'usage_kg',
        'closing_stock_kg',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'purchase_kg' => 'decimal:2',
            'usage_kg' => 'decimal:2',
            'closing_stock_kg' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

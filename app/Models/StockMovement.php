<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class StockMovement extends Model
{
    protected $fillable = [
        'product_id',
        'user_id',
        'type',
        'quantity',
        'stock_before',
        'stock_after',
        'reference_type',
        'reference_id',
        'reason',
        'notes',
    ];

    protected $casts = [
        'quantity'     => 'integer',
        'stock_before' => 'integer',
        'stock_after'  => 'integer',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reference(): MorphTo
    {
        return $this->morphTo();
    }

    // Label untuk tampilan
    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'in'         => 'Stok Masuk',
            'out'        => 'Stok Keluar',
            'adjustment' => 'Penyesuaian',
            default      => $this->type,
        };
    }

    public function getTypeColorAttribute(): string
    {
        return match ($this->type) {
            'in'         => 'success',
            'out'        => 'danger',
            'adjustment' => 'warning',
            default      => 'gray',
        };
    }
}
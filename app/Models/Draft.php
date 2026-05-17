<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Draft extends Model
{
    protected $fillable = [
        'user_id',
        'label',
        'cart',
        'student_id',
        'student_name',
        'student_class',
        'student_balance',
        'is_guest',
        'payment_method', 
        'total',
        'item_count',
    ];

    protected $casts = [
        'cart'            => 'array',
        'is_guest'        => 'boolean',
        'student_balance' => 'float',
        'total'           => 'float',
        'item_count'      => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** Ringkasan item untuk ditampilkan di list */
    public function getSummaryAttribute(): string
    {
        $items = collect($this->cart);
        $count = $items->sum('quantity');
        $total = $items->sum(fn($i) => $i['price'] * $i['quantity']);

        return "{$count} item — Rp " . number_format($total, 0, ',', '.');
    }

    /** Label default jika tidak diisi */
    public function getDisplayLabelAttribute(): string
    {
        return $this->label ?: 'Draft #' . $this->id;
    }
}
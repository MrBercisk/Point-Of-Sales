<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WalletTransaction extends Model
{
    protected $fillable = [
        'student_id',
        'user_id',
        'type',
        'amount',
        'balance_before',
        'balance_after',
        'reference',
        'note',
    ];

    protected $casts = [
        'amount'         => 'decimal:2',
        'balance_before' => 'decimal:2',
        'balance_after'  => 'decimal:2',
    ];

    /* ------------------------------------------------------------------ */
    /* Relasi                                                               */
    /* ------------------------------------------------------------------ */

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /* ------------------------------------------------------------------ */
    /* Accessor                                                             */
    /* ------------------------------------------------------------------ */

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'top_up'     => 'Top Up',
            'purchase'   => 'Belanja',
            'refund'     => 'Refund',
            'adjustment' => 'Penyesuaian',
            default      => $this->type,
        };
    }

    public function getFormattedAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }

    /* ------------------------------------------------------------------ */
    /* Scopes                                                               */
    /* ------------------------------------------------------------------ */

    public function scopeTopUps($query)
    {
        return $query->where('type', 'top_up');
    }

    public function scopePurchases($query)
    {
        return $query->where('type', 'purchase');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }
}
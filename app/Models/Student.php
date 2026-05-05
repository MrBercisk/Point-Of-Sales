<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    protected $fillable = [
        'name',
        'nisn',
        'class',
        'gender',
        'parent_name',
        'parent_phone',
        'photo',
        'barcode',
        'balance',
        'is_active',
    ];

    protected $casts = [
        'balance'   => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /* ------------------------------------------------------------------ */
    /* Relasi                                                               */
    /* ------------------------------------------------------------------ */

    public function walletTransactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class);
    }

    /* ------------------------------------------------------------------ */
    /* Helper Methods                                                       */
    /* ------------------------------------------------------------------ */

    /** Cek apakah saldo cukup untuk transaksi */
    public function hasSufficientBalance(float $amount): bool
    {
        return $this->balance >= $amount;
    }

    /** Top up saldo siswa — catat ke wallet_transactions */
    public function topUp(float $amount, ?int $userId = null, ?string $note = null): WalletTransaction
    {
        $balanceBefore = $this->balance;
        $this->increment('balance', $amount);

        return $this->walletTransactions()->create([
            'user_id'        => $userId,
            'type'           => 'top_up',
            'amount'         => $amount,
            'balance_before' => $balanceBefore,
            'balance_after'  => $this->fresh()->balance,
            'note'           => $note,
        ]);
    }

    /** Potong saldo saat belanja */
    public function deduct(float $amount, ?string $reference = null, ?string $note = null): WalletTransaction
    {
        $balanceBefore = $this->balance;
        $this->decrement('balance', $amount);

        return $this->walletTransactions()->create([
            'type'           => 'purchase',
            'amount'         => $amount,
            'balance_before' => $balanceBefore,
            'balance_after'  => $this->fresh()->balance,
            'reference'      => $reference,
            'note'           => $note,
        ]);
    }

    /** Refund saldo */
    public function refund(float $amount, ?string $reference = null, ?string $note = null): WalletTransaction
    {
        $balanceBefore = $this->balance;
        $this->increment('balance', $amount);

        return $this->walletTransactions()->create([
            'type'           => 'refund',
            'amount'         => $amount,
            'balance_before' => $balanceBefore,
            'balance_after'  => $this->fresh()->balance,
            'reference'      => $reference,
            'note'           => $note,
        ]);
    }

    /* ------------------------------------------------------------------ */
    /* Accessor                                                             */
    /* ------------------------------------------------------------------ */

    public function getFormattedBalanceAttribute(): string
    {
        return 'Rp ' . number_format($this->balance, 0, ',', '.');
    }

    /* ------------------------------------------------------------------ */
    /* Scopes                                                               */
    /* ------------------------------------------------------------------ */

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByClass($query, string $class)
    {
        return $query->where('class', $class);
    }

    public function scopeLowBalance($query, float $threshold = 5000)
    {
        return $query->where('balance', '<=', $threshold);
    }
}
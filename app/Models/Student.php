<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

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


    public function walletTransactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class);
    }


    /** Cek apakah saldo cukup untuk transaksi */
    public function hasSufficientBalance(float $amount): bool
    {
        return $this->balance >= $amount;
    }

    /** Top up saldo siswa catat ke wallet_transactions */
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

    public function getFormattedBalanceAttribute(): string
    {
        return 'Rp ' . number_format($this->balance, 0, ',', '.');
    }


    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeByClass(Builder $query, string $class): Builder
    {
        return $query->where('class', $class);
    }

    public function scopeLowBalance(Builder $query, float $threshold = 5000): Builder
    {
        return $query->where('balance', '<=', $threshold);
    }
}
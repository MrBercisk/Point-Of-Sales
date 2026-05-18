<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class KitchenOrder extends Model
{
    protected $fillable = [
        'order_id',
        'invoice_number',
        'customer_name',
        'customer_class',
        'status',
        'items',
        'called_at',
        'done_at',
    ];

    protected $casts = [
        'items'     => 'array',
        'called_at' => 'datetime',
        'done_at'   => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    // ── Scopes ──

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    public function scopeProcessing(Builder $query): Builder
    {
        return $query->where('status', 'processing');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->whereIn('status', ['pending', 'processing']);
    }

    public function scopeDone(Builder $query): Builder
    {
        return $query->where('status', 'done');
    }

    // Helpers

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending'    => 'Antri',
            'processing' => 'Diproses',
            'done'       => 'Selesai',
            default      => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending'    => '#f59e0b',
            'processing' => '#3b82f6',
            'done'       => '#16a34a',
            default      => '#6b7280',
        };
    }

    public function getWaitingMinutesAttribute(): int
    {
        return (int) $this->created_at->diffInMinutes(now());
    }

    public function advanceStatus(): void
    {
        match ($this->status) {
            'pending'    => $this->update(['status' => 'processing', 'called_at' => now()]),
            'processing' => $this->update(['status' => 'done', 'done_at' => now()]),
            default      => null,
        };
    }

    public function revertStatus(): void
    {
        match ($this->status) {
            'processing' => $this->update(['status' => 'pending', 'called_at' => null]),
            'done'       => $this->update(['status' => 'processing', 'done_at' => null]),
            default      => null,
        };
    }
}
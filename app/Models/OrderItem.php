<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        'subtotal',
    ];

    protected $casts = [
        'price'    => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    /* boot method buat auto calculate dan stock reduction */
    protected static function boot()
    {
        parent::boot();

        // Auto-calculate subtotal before creating
        // HANYA jika belum di-set secara eksplisit dari luar (misal PosCashier sudah
        // hitung (base + modifier) × qty). Kalau di-override di sini, modifier hilang dari subtotal.
        static::creating(function ($item) {
            if (empty($item->subtotal)) {
                $item->subtotal = $item->price * $item->quantity;
            }
        });

        // Auto-calculate subtotal before updating
        // Skip kalau subtotal sudah di-set dirty dari luar (misal recalculateSubtotal)
        static::updating(function ($item) {
            if (! $item->isDirty('subtotal')) {
                $item->subtotal = $item->price * $item->quantity;
            }
        });

        // Reduce product stock after item created
        static::created(function ($item) {
            if ($item->product) {
                $item->product->reduceStock($item->quantity);
            }
        });

        // Update order total after item created
        static::saved(function ($item) {
            if ($item->order) {
                $item->order->calculateTotal();
            }
        });

        // Restore stock and recalculate total when item deleted
        static::deleting(function ($item) {
            if ($item->product && $item->order->status !== 'cancelled') {
                $item->product->increaseStock($item->quantity);
            }
            // Restore stock bahan tambahan (telur, keju, dll)
            foreach ($item->modifiers as $orderItemModifier) {
                $modifier = $orderItemModifier->modifier;
                if ($modifier?->product_id && $modifier->product) {
                    $modifier->product->increaseStock(1);
                }
            }
        });

        static::deleted(function ($item) {
            if ($item->order) {
                $item->order->calculateTotal();
            }
        });
    }

    /* relasi orderitem ke order */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /* relasi orderitem ke Product */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /* relasi orderitem ke modifier tambahan */
    public function modifiers(): HasMany
    {
        return $this->hasMany(OrderItemModifier::class);
    }

    /* hitung total harga modifier yang dipilih */
    public function getModifiersTotalAttribute(): float
    {
        return $this->modifiers->sum('price_snapshot');
    }

    /* recalculate subtotal termasuk modifier setelah modifier disimpan
     * Pakai fresh query () supaya tidak pakai cache modifier yang stale.
     * saveQuietly supaya tidak trigger boot saved lagi. */
    public function recalculateSubtotal(): void
    {
        $modifiersTotal = $this->modifiers()->sum('price_snapshot'); // fresh query
        $this->subtotal = ($this->price + $modifiersTotal) * $this->quantity;
        $this->saveQuietly(); // saveQuietly supaya tidak trigger boot saved lagi
        $this->order->calculateTotal();
    }

    /* get format subtotal rupiah */
    public function getFormattedSubtotalAttribute(): string
    {
        return 'Rp ' . number_format($this->subtotal, 0, ',', '.');
    }
}
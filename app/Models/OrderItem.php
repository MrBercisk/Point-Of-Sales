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
        static::creating(function ($item) {
            if (empty($item->subtotal)) {
                $item->subtotal = $item->price * $item->quantity;
            }
        });
        static::updating(function ($item) {
            if (! $item->isDirty('subtotal')) {
                $item->subtotal = $item->price * $item->quantity;
            }
        });

        // kurangi stok dan buat di stock movement
        static::created(function ($item) {
            if ($item->product) {
                $invoice = $item->order?->invoice_number ?? "ORD-{$item->order_id}";
                $item->product->recordStockMovement(
                    type:      'out',
                    quantity:  $item->quantity,
                    reason:    'Penjualan POS',
                    notes:     "Invoice: {$invoice}",
                    reference: $item->order,
                );
                $item->product->reduceStock($item->quantity);
            }
        });

        // Update order total after item created
        static::saved(function ($item) {
            if ($item->order) {
                $item->order->calculateTotal();
            }
        });

        // restore stok jika item dihapus, tapi jika order belum dibatalkan dan tambahkan ke stok mocvement
        static::deleting(function ($item) {
           if ($item->product && $item->order->status !== 'cancelled') {
                $invoice = $item->order?->invoice_number ?? "ORD-{$item->order_id}";
                $item->product->recordStockMovement(
                    type:      'in',
                    quantity:  $item->quantity,
                    reason:    'Item dihapus dari order',
                    notes:     "Invoice: {$invoice}",
                    reference: $item->order,
                );
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


    public function recalculateSubtotal(): void
    {
        $modifiersTotal = $this->modifiers()->sum('price_snapshot'); 
        $this->subtotal = ($this->price + $modifiersTotal) * $this->quantity;
        $this->saveQuietly();
        $this->order->calculateTotal();
    }

    /* get format subtotal rupiah */
    public function getFormattedSubtotalAttribute(): string
    {
        return 'Rp ' . number_format($this->subtotal, 0, ',', '.');
    }
}
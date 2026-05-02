<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'invoice_number',
        'customer_name',
        'total_amount',
        'status',
        'notes',
        'customer_phone',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
    ];

    /* auto generate invoice number saat create */
     protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->invoice_number)) {
                $order->invoice_number = self::generateInvoiceNumber();
            }
        });
    }

    /* generate invoice number unique */
     public static function generateInvoiceNumber(): string
    {
        $today = now()->format('Ymd');
        $count = self::whereDate('created_at', today())->count() + 1;

        return 'INV-' . $today . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    /* relasi order punya banyak orderitems */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /* hitung dan update total dari semua items */
     public function calculateTotal(): void
    {
        $this->total_amount = $this->items->sum('subtotal');
        $this->save();
    }

    /* get format total rupiah */

     public function getFormattedTotalAttribute(): string
    {
        return 'Rp ' . number_format($this->total_amount, 0, ',', '.');
    }

    /* tandai order yang completed */
    public function markAsCompleted(): void
    {
        $this->update(['status' => 'completed']);
    }

    /* cancel order dan restore stock */
    public function markAsCancelled(): void
    {
        // Restore stock for each item
        foreach ($this->items as $item) {
            $item->product->increaseStock($item->quantity);
        }

        $this->update(['status' => 'cancelled']);
    }

    /* filter filter */


    /* scope yang ordeeran completd */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /* scope orderan yang pending */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /* scope orderan hari ini */
     public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    /* scope orderan bulan ini */
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
                     ->whereYear('created_at', now()->year);
    }
}

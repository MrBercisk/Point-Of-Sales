<?php

namespace App\Models;



use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'stock',
        'image',
        'is_active',
    ];
      protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /* auto generate slug saat create produk */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    /* relasi product ke category */
     public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /* relasi productt punya banyak order items (history penjualan)*/
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /* cek jika product ada stock */
     public function inStock(): bool
    {
        return $this->stock > 0;
    }

    /* kurangi stock setelah order */
    public function reduceStock(int $quantity): void
    {
        $this->decrement('stock', $quantity);
    }

    /* tambah stock (untuk restocking atau order dibatalin) */
    public function increaseStock(int $quantity): void
    {
        $this->increment('stock', $quantity);
    }

    /* format rupiah */
     public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    /* filter filter scope */
    
    /* scope cuma produk aktif */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /* scope hanya product punya stock */
    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    /* scope produk dengan stok rendah (stock <= 10) */
    public function scopeLowStock($query)
    {
        return $query->where('stock', '<=', 10)->where('stock', '>', 0);
    }
}

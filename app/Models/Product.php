<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        // Basic Info
        'category_id',
        'brand_id',
        'name',
        'slug',
        'description',

        // Barcode
        'barcode_symbology',
        'barcode',

        // Pricing
        'price',
        'cost',
        'order_tax',
        'tax_type',

        // Units & Stock
        'product_unit_id',
        'sale_unit_id',
        'purchase_unit_id',
        'stock',
        'stock_alert',

        // Image & Status
        'image',
        'type',
        'is_active',
        'has_imei_serial',
        'not_for_selling',
    ];

    protected $casts = [
        'price'          => 'decimal:2',
        'cost'           => 'decimal:2',
        'order_tax'      => 'decimal:2',
        'is_active'      => 'boolean',
        'has_imei_serial'=> 'boolean',
        'not_for_selling'=> 'boolean',
    ];

    /* ------------------------------------------------------------------ */
    /* Boot                                                                 */
    /* ------------------------------------------------------------------ */

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    /* ------------------------------------------------------------------ */
    /* Relasi                                                               */
    /* ------------------------------------------------------------------ */

    /** Produk milik satu kategori */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /** Produk milik satu brand */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /** Satuan utama produk */
    public function productUnit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'product_unit_id');
    }

    /** Satuan jual */
    public function saleUnit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'sale_unit_id');
    }

    /** Satuan beli */
    public function purchaseUnit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'purchase_unit_id');
    }

    /** Riwayat penjualan */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /* ------------------------------------------------------------------ */
    /* Helper Methods                                                       */
    /* ------------------------------------------------------------------ */

    /** Cek apakah produk masih ada stok */
    public function inStock(): bool
    {
        return $this->stock > 0;
    }

    /** Cek apakah stok di bawah batas alert */
    public function isLowStock(): bool
    {
        return $this->stock > 0 && $this->stock <= $this->stock_alert;
    }

    /** Kurangi stok setelah order */
    public function reduceStock(int $quantity): void
    {
        $this->decrement('stock', $quantity);
    }

    /** Tambah stok (restocking atau order dibatalkan) */
    public function increaseStock(int $quantity): void
    {
        $this->increment('stock', $quantity);
    }

    /* ------------------------------------------------------------------ */
    /* Accessors                                                            */
    /* ------------------------------------------------------------------ */

    /** Harga jual dalam format Rupiah */
    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    /** Harga beli/cost dalam format Rupiah */
    public function getFormattedCostAttribute(): string
    {
        return 'Rp ' . number_format($this->cost, 0, ',', '.');
    }

    /**
     * Hitung harga akhir setelah pajak.
     * - Inclusive: pajak sudah termasuk dalam harga
     * - Exclusive: pajak ditambahkan ke harga
     */
    public function getPriceAfterTaxAttribute(): float
    {
        if ($this->tax_type === 'Exclusive') {
            return $this->price * (1 + $this->order_tax / 100);
        }

        // Inclusive: harga sudah termasuk pajak, tidak berubah
        return $this->price;
    }

    /* ------------------------------------------------------------------ */
    /* Scopes                                                               */
    /* ------------------------------------------------------------------ */

    /** Hanya produk aktif */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /** Hanya produk yang bisa dijual */
    public function scopeForSelling($query)
    {
        return $query->where('not_for_selling', false)->where('is_active', true);
    }

    /** Hanya produk yang ada stoknya */
    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    /** Produk dengan stok rendah (di bawah atau sama dengan stock_alert) */
    public function scopeLowStock($query)
    {
        return $query->whereColumn('stock', '<=', 'stock_alert')->where('stock', '>', 0);
    }

    /** Filter berdasarkan tipe produk */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }
}
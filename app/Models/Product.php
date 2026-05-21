<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    // use SoftDeletes;
    protected $fillable = [
        // Basic Info
        'category_id',
        'brand_id',
        'name',
        'slug',
        'description',
        'sku',

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
        'needs_preparation',
        'expired_at',
    ];

    protected $casts = [
        'price'          => 'decimal:2',
        'cost'           => 'decimal:2',
        'order_tax'      => 'decimal:2',
        'is_active'      => 'boolean',
        'has_imei_serial'=> 'boolean',
        'not_for_selling'=> 'boolean',
        'expired_at' => 'date',
    ];


    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }


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

 

    /** Hanya produk aktif */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /** Hanya produk yang bisa dijual */
    public function scopeForSelling(Builder $query): Builder
    {
        return $query->where('not_for_selling', false)->where('is_active', true);
    }

    /** Hanya produk yang ada stoknya */
    public function scopeInStock(Builder $query): Builder
    {
        return $query->where('stock', '>', 0);
    }

    /** Produk dengan stok rendah (di bawah atau sama dengan stock_alert) */
    public function scopeLowStock(Builder $query): Builder
    {
        return $query->whereColumn('stock', '<=', 'stock_alert')->where('stock', '>', 0);
    }

    /** Filter berdasarkan tipe produk */
   public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    /* buat tambahan misal nambah telur dll */
    public function modifierGroups(): BelongsToMany
    {
        return $this->belongsToMany(
            ModifierGroup::class,
            'product_modifier_groups'
        );
    }

    public function hasModifiers(): bool
    {
        return $this->modifierGroups()->where('is_active', true)->exists();
    }

    /** Cek apakah produk sudah expired */
    public function isExpired(): bool
    {
        return $this->expired_at && $this->expired_at->isPast();
    }

    /** Cek apakah produk akan expired dalam X hari */
    public function isExpiringSoon(int $days = 7): bool
    {
        return $this->expired_at
            && ! $this->isExpired()
            && $this->expired_at->diffInDays(now()) <= $days;
    }

    /** Scope hanya produk yang belum expired */
    public function scopeNotExpired(Builder $query): Builder
    {
        return $query->where(function ($q) {
            $q->whereNull('expired_at')
            ->orWhere('expired_at', '>=', now());
        });
    }

    /** Scope produk yang akan expired dalam X hari */
    public function scopeExpiringSoon(Builder $query, int $days = 7): Builder
    {
        return $query->whereBetween('expired_at', [now(), now()->addDays($days)]);
    }

    /* stock movement */
    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class)->latest();
    }

    // Helper: catat movement otomatis
    public function recordStockMovement(
        string  $type,
        int     $quantity,
        ?string $reason = null,
        ?string $notes = null,
        ?Model  $reference = null,
    ): StockMovement {
        $before = $this->stock;

        $after = match ($type) {
            'in'         => $before + $quantity,
            'out'        => $before - $quantity,
            'adjustment' => $quantity,
            default      => $before,
        };

        // Update stok produk
        if ($type === 'adjustment') {
            $quantity = abs($after - $before); // selisihnya
            $this->update(['stock' => $after]);
        }

        return $this->stockMovements()->create([
            'user_id'        => auth()->id(),
            'type'           => $type,
            'quantity'       => $quantity,
            'stock_before'   => $before,
            'stock_after'    => $after,
            'reference_type' => $reference ? get_class($reference) : null,
            'reference_id'   => $reference?->id,
            'reason'         => $reason,
            'notes'          => $notes,
        ]);
    }
}

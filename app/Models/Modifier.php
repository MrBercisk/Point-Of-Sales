<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Modifier extends Model
{
    protected $fillable = [
        'modifier_group_id',
        'product_id',
        'name',
        'price',
        'is_active',
    ];

    protected $casts = [
        'price'     => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(ModifierGroup::class, 'modifier_group_id');
    }

    // produk bahan baku yang terhubung (telur, keju, dll)
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function hasStock(): bool
    {
        if (!$this->product_id) return true; // tidak terhubung stok, selalu tersedia
        return $this->product?->inStock() ?? true;
    }
}
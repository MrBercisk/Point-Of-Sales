<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Unit extends Model
{
    protected $fillable = [
        'name',
        'short_name',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'product_unit_id');
    }

    public function saleProducts(): HasMany
    {
        return $this->hasMany(Product::class, 'sale_unit_id');
    }

    public function purchaseProducts(): HasMany
    {
        return $this->hasMany(Product::class, 'purchase_unit_id');
    }
}
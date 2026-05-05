<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Settings extends Model
{
    use SoftDeletes;

    protected $table = 'settings';

    protected $fillable = [
        'email',
        'currency_id',
        'CompanyName',
        'CompanyPhone',
        'CompanyAdress',
        'logo',
        'is_invoice_footer',
        'invoice_footer',
        'footer',
        'developed_by',
        'client_id',
        'warehouse_id',
        'default_language',
        'sms_gateway',
        'show_language',
        'quotation_with_stock',
        // POS & WA 
        'whatsapp_token',
        'whatsapp_base_url',
        'whatsapp_enabled',
        'receipt_store_name',
        'receipt_store_address',
        'receipt_store_phone',
        'receipt_footer',
    ];

    protected $casts = [
        'is_invoice_footer'    => 'boolean',
        'show_language'        => 'boolean',
        'quotation_with_stock' => 'boolean',
        'whatsapp_enabled'     => 'boolean',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::saved(function () {
            static::clearCache();
        });
    }

    
    // ─── Helper: ambil row pertama (singleton pattern) ──────────────────────

    public static function current(): static
    {
        return Cache::remember('app_settings', 3600, fn () => static::first() ?? new static());
    }

    public static function clearCache(): void
    {
        Cache::forget('app_settings');
    }
    
}
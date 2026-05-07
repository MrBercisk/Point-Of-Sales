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
        'email', 'currency_id', 'CompanyName', 'CompanyPhone', 'CompanyAdress',
        'logo', 'is_invoice_footer', 'invoice_footer', 'footer', 'developed_by',
        'client_id', 'warehouse_id', 'default_language', 'sms_gateway',
        'show_language', 'quotation_with_stock',
        'whatsapp_token', 'whatsapp_base_url', 'whatsapp_enabled',
        'receipt_store_name', 'receipt_store_address', 'receipt_store_phone', 'receipt_footer',
        'receipt_layout',
        'receipt_show_logo', 'receipt_show_store_name', 'receipt_show_address',
        'receipt_show_phone', 'receipt_show_invoice_number', 'receipt_show_date',
        'receipt_show_student', 'receipt_show_payment_method', 'receipt_show_item_price',
        'receipt_show_subtotal_per_item', 'receipt_show_cashier', 'receipt_show_change',
        'receipt_show_balance_after', 'receipt_show_footer', 'receipt_show_barcode',
        'receipt_paper_size',
    ];

    protected $casts = [
        'is_invoice_footer'              => 'boolean',
        'show_language'                  => 'boolean',
        'quotation_with_stock'           => 'boolean',
        'whatsapp_enabled'               => 'boolean',
        'receipt_show_logo'              => 'boolean',
        'receipt_show_store_name'        => 'boolean',
        'receipt_show_address'           => 'boolean',
        'receipt_show_phone'             => 'boolean',
        'receipt_show_invoice_number'    => 'boolean',
        'receipt_show_date'              => 'boolean',
        'receipt_show_student'           => 'boolean',
        'receipt_show_payment_method'    => 'boolean',
        'receipt_show_item_price'        => 'boolean',
        'receipt_show_subtotal_per_item' => 'boolean',
        'receipt_show_cashier'           => 'boolean',
        'receipt_show_change'            => 'boolean',
        'receipt_show_balance_after'     => 'boolean',
        'receipt_show_footer'            => 'boolean',
        'receipt_show_barcode'           => 'boolean',
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::saved(fn () => static::clearCache());
    }

    public static function current(): static
    {
        return Cache::remember('app_settings', 3600, fn () => static::first() ?? new static());
    }

    public static function clearCache(): void
    {
        Cache::forget('app_settings');
    }

    public function receiptPaperWidth(): float
    {
        return $this->receipt_paper_size === '58mm' ? 164.41 : 226.77;
    }
}
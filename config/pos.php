<?php

/**
 * config/pos.php
 *
 * Konfigurasi POS dibaca dari tabel settings (singleton).
 * Gunakan helper: pos_setting('receipt_store_name')
 * atau langsung: Settings::current()->receipt_store_name
 */

use App\Models\Settings;

// Guard jika dipanggil sebelum DB tersedia (misal artisan config:cache)
$settings = null;
try {
    if (app()->runningInConsole() === false || app()->isBooted()) {
        $settings = Settings::current();
    }
} catch (\Throwable) {
    // DB belum siap, fallback ke null — akan pakai default di bawah
}

return [
    // ── POS / Struk ──────────────────────────────────────────────────────
    'store_name'    => $settings?->receipt_store_name    ?: env('POS_STORE_NAME',    'Kantin Sekolah'),
    'store_address' => $settings?->receipt_store_address ?: env('POS_STORE_ADDRESS', ''),
    'store_phone'   => $settings?->receipt_store_phone   ?: env('POS_STORE_PHONE',   ''),
    'receipt_footer'=> $settings?->receipt_footer        ?: 'Terima kasih telah berbelanja!',

    // ── WhatsApp (Fonnte) ─────────────────────────────────────────────────
    'whatsapp_enabled'  => (bool) ($settings?->whatsapp_enabled  ?? false),
    'whatsapp_token'    => $settings?->whatsapp_token    ?: env('WHATSAPP_TOKEN',    ''),
    'whatsapp_base_url' => $settings?->whatsapp_base_url ?: env('WHATSAPP_BASE_URL', 'https://api.fonnte.com'),
];
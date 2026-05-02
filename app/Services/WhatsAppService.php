<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;

/**
 * WhatsAppService
 *
 * Menggunakan Fonnte (https://fonnte.com) sebagai gateway WhatsApp.
 * Anda bisa mengganti implementasi send() sesuai provider yang dipakai
 * (Wablas, Whacenter, MyWA, dll.) – strukturnya tetap sama.
 *
 * Konfigurasi di .env:
 *   WHATSAPP_DEVICE_TOKEN=xxxxxxxxxxxx   ← token device Fonnte
 *   WHATSAPP_BASE_URL=https://api.fonnte.com
 */
class WhatsAppService
{
    private string $token;
    private string $baseUrl;

    public function __construct()
    {
        $this->token   = config('services.whatsapp.token', '');
        $this->baseUrl = config('services.whatsapp.base_url', 'https://api.fonnte.com');
    }

    // ─── Public API ────────────────────────────────────────────────────────────

    /**
     * Kirim pesan teks + PDF struk ke nomor pelanggan.
     *
     * @param string $phone   Format: 08xxx atau 628xxx
     * @param Order  $order   Order model (sudah di-load relasi)
     * @param string $pdfPath Path absolut file PDF
     */
    public function sendReceipt(string $phone, Order $order, string $pdfPath): void
    {
        $phone   = $this->normalizePhone($phone);
        $caption = $this->buildCaption($order);

        // 1. Kirim pesan teks terlebih dahulu
        $this->sendText($phone, $caption);

        // 2. Kirim file PDF sebagai dokumen
        $this->sendDocument($phone, $pdfPath, "Struk-{$order->invoice_number}.pdf");
    }

    // ─── Internal helpers ──────────────────────────────────────────────────────

    private function sendText(string $phone, string $message): void
    {
        Http::withToken($this->token)
            ->post("{$this->baseUrl}/send", [
                'target'  => $phone,
                'message' => $message,
            ])
            ->throw();
    }

    private function sendDocument(string $phone, string $filePath, string $filename): void
    {
        Http::withToken($this->token)
            ->attach('file', file_get_contents($filePath), $filename)
            ->post("{$this->baseUrl}/send", [
                'target'  => $phone,
                'message' => $filename,
            ])
            ->throw();
    }

    /**
     * Normalisasi nomor: 08xxx → 628xxx
     */
    private function normalizePhone(string $phone): string
    {
        $phone = preg_replace('/\D/', '', $phone);

        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        return $phone;
    }

    private function buildCaption(Order $order): string
    {
        $storeName = config('pos.store_name', 'Toko Saya');
        $items     = $order->orderItems->map(function ($item) {
            $subtotal = number_format($item->subtotal, 0, ',', '.');
            return "- {$item->product->name} x{$item->quantity} = Rp {$subtotal}";
        })->implode("\n");

        $total = number_format($order->total_amount, 0, ',', '.');
        $date  = $order->created_at->format('d/m/Y H:i');

        return <<<MSG
        🧾 *Struk Pembelian – {$storeName}*

        📋 Invoice : {$order->invoice_number}
        📅 Tanggal : {$date}
        👤 Pelanggan : {$order->customer_name}

        *Rincian Pesanan:*
        {$items}

        ─────────────────────
        💰 *Total : Rp {$total}*
        ─────────────────────

        Terima kasih telah berbelanja di {$storeName}! 🙏
        MSG;
    }
}
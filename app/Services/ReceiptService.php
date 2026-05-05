<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Settings;
use Barryvdh\DomPDF\Facade\Pdf;

class ReceiptService
{
    // ── Ambil data toko dari Settings DB (di-cache otomatis 1 jam) ──────────
    private function storeData(): array
    {
        $s = Settings::current();

        return [
            'storeName'    => $s->receipt_store_name    ?: 'Kantin Sekolah',
            'storeAddress' => $s->receipt_store_address ?: '',
            'storePhone'   => $s->receipt_store_phone   ?: '',
            'storeFooter'  => $s->receipt_footer        ?: 'Terima kasih telah berbelanja!',
        ];
    }

    /**
     * Generate PDF struk & simpan ke storage/app/receipts/{invoice_number}.pdf
     *
     * @return string  Path absolut file PDF
     */
    public function generatePdf(Order $order): string
    {
        $data = array_merge($this->storeData(), ['order' => $order]);

        $pdf = Pdf::loadView('receipts.pdf', $data)
            ->setPaper([0, 0, 226.77, 800], 'portrait')   // 80 mm thermal
            ->setOption('dpi', 150)
            ->setOption('margin_top', 4)
            ->setOption('margin_bottom', 4)
            ->setOption('margin_left', 4)
            ->setOption('margin_right', 4);

        $directory = storage_path('app/receipts');
        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $path = $directory . '/' . $order->invoice_number . '.pdf';
        $pdf->save($path);

        return $path;
    }

    /**
     * Kembalikan konten PDF sebagai string (untuk response download langsung)
     */
    public function getPdfContent(Order $order): string
    {
        $data = array_merge($this->storeData(), ['order' => $order]);

        return Pdf::loadView('receipts.pdf', $data)
            ->setPaper([0, 0, 226.77, 800], 'portrait')
            ->setOption('dpi', 150)
            ->output();
    }
}
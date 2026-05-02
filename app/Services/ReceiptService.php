<?php

namespace App\Services;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class ReceiptService
{
    /**
     * Generate PDF struk untuk order tertentu.
     * PDF disimpan ke storage/app/receipts/{invoice_number}.pdf
     *
     * @return string  Path absolut file PDF
     */
    public function generatePdf(Order $order): string
    {
        $storeName    = config('pos.store_name', 'Toko Saya');
        $storeAddress = config('pos.store_address', 'Jl. Contoh No. 1');
        $storePhone   = config('pos.store_phone', '08123456789');

        $pdf = Pdf::loadView('receipts.pdf', compact('order', 'storeName', 'storeAddress', 'storePhone'))
            ->setPaper([0, 0, 226.77, 800], 'portrait')   // 80mm thermal width
            ->setOption('dpi', 150)
            ->setOption('margin_top', 4)
            ->setOption('margin_bottom', 4)
            ->setOption('margin_left', 4)
            ->setOption('margin_right', 4);

        $directory = storage_path('app/receipts');
        if (!is_dir($directory)) {
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
        $storeName    = config('pos.store_name', 'Toko Saya');
        $storeAddress = config('pos.store_address', 'Jl. Contoh No. 1');
        $storePhone   = config('pos.store_phone', '08123456789');

        return Pdf::loadView('receipts.pdf', compact('order', 'storeName', 'storeAddress', 'storePhone'))
            ->setPaper([0, 0, 226.77, 800], 'portrait')
            ->setOption('dpi', 150)
            ->output();
    }
}
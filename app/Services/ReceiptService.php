<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Settings;
use Barryvdh\DomPDF\Facade\Pdf;

class ReceiptService
{
    /**
     * Ambil semua data receipt dari Settings — termasuk layout & toggle visibilitas
     */
    private function storeData(): array
    {
        $s = Settings::current();

        return [
            // Info toko
            'storeName'    => $s->receipt_store_name    ?: 'Kantin Sekolah',
            'storeAddress' => $s->receipt_store_address ?: '',
            'storePhone'   => $s->receipt_store_phone   ?: '',
            'storeFooter'  => $s->receipt_footer        ?: 'Terima kasih! Selamat belajar.',

            // Layout & ukuran kertas
            'layout'       => $s->receipt_layout        ?: 'standard', // standard|compact|detailed
            'paperSize'    => $s->receipt_paper_size    ?: '80mm',     // 58mm|80mm

            // Toggle visibilitas
            'showStoreName'       => (bool) ($s->receipt_show_store_name        ?? true),
            'showAddress'         => (bool) ($s->receipt_show_address           ?? true),
            'showPhone'           => (bool) ($s->receipt_show_phone             ?? true),
            'showInvoiceNumber'   => (bool) ($s->receipt_show_invoice_number    ?? true),
            'showDate'            => (bool) ($s->receipt_show_date              ?? true),
            'showStudent'         => (bool) ($s->receipt_show_student           ?? true),
            'showPaymentMethod'   => (bool) ($s->receipt_show_payment_method    ?? true),
            'showCashier'         => (bool) ($s->receipt_show_cashier           ?? false),
            'showItemPrice'       => (bool) ($s->receipt_show_item_price        ?? true),
            'showSubtotalPerItem' => (bool) ($s->receipt_show_subtotal_per_item ?? true),
            'showChange'          => (bool) ($s->receipt_show_change            ?? true),
            'showBalanceAfter'    => (bool) ($s->receipt_show_balance_after     ?? true),
            'showFooter'          => (bool) ($s->receipt_show_footer            ?? true),
            'showBarcode'         => (bool) ($s->receipt_show_barcode           ?? false),
        ];
    }

    /**
     * Generate PDF struk & simpan ke storage/app/receipts/{invoice_number}.pdf
     */
    public function generatePdf(Order $order): string
    {
        $data = array_merge($this->storeData(), ['order' => $order]);

        // Ukuran kertas: 58mm = 164.41pt, 80mm = 226.77pt
        $paperWidth = ($data['paperSize'] === '58mm') ? 164.41 : 226.77;

        $pdf = Pdf::loadView('receipts.pdf', $data)
            ->setPaper([0, 0, $paperWidth, 800], 'portrait')
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

        $paperWidth = ($data['paperSize'] === '58mm') ? 164.41 : 226.77;

        return Pdf::loadView('receipts.pdf', $data)
            ->setPaper([0, 0, $paperWidth, 800], 'portrait')
            ->setOption('dpi', 150)
            ->output();
    }
}
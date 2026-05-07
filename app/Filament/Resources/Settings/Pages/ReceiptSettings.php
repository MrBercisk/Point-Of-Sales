<?php

namespace App\Filament\Resources\Settings\Pages;

use App\Models\Settings;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use BackedEnum;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class ReceiptSettings extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedReceiptPercent;
    protected static ?string $navigationLabel = 'Receipt Settings';
    protected static ?string $title           = 'Pengaturan Struk Kasir';
    protected static string|UnitEnum|null $navigationGroup = 'Settings';
    protected static ?int $navigationSort     = 10;
    protected string $view = 'filament.pages.receipt-settings';

    /* ------------------------------------------------------------------ */
    /* State — mirror kolom di tabel settings                               */
    /* ------------------------------------------------------------------ */

    // Info toko
    public string $receipt_store_name    = '';
    public string $receipt_store_address = '';
    public string $receipt_store_phone   = '';
    public string $receipt_footer        = '';

    // Layout
    public string $receipt_layout     = 'standard'; // standard | compact | detailed
    public string $receipt_paper_size = '80mm';     // 58mm | 80mm

    // Toggles visibilitas
    public bool $receipt_show_logo              = false;
    public bool $receipt_show_store_name        = true;
    public bool $receipt_show_address           = true;
    public bool $receipt_show_phone             = true;
    public bool $receipt_show_invoice_number    = true;
    public bool $receipt_show_date              = true;
    public bool $receipt_show_student           = true;
    public bool $receipt_show_payment_method    = true;
    public bool $receipt_show_item_price        = true;
    public bool $receipt_show_subtotal_per_item = true;
    public bool $receipt_show_cashier           = false;
    public bool $receipt_show_change            = true;
    public bool $receipt_show_balance_after     = true;
    public bool $receipt_show_footer            = true;
    public bool $receipt_show_barcode           = false;

    /* ------------------------------------------------------------------ */
    /* Mount — load dari DB                                                 */
    /* ------------------------------------------------------------------ */

    public function mount(): void
    {
        $s = Settings::current();

        $fields = [
            'receipt_store_name', 'receipt_store_address', 'receipt_store_phone',
            'receipt_footer', 'receipt_layout', 'receipt_paper_size',
            'receipt_show_logo', 'receipt_show_store_name', 'receipt_show_address',
            'receipt_show_phone', 'receipt_show_invoice_number', 'receipt_show_date',
            'receipt_show_student', 'receipt_show_payment_method', 'receipt_show_item_price',
            'receipt_show_subtotal_per_item', 'receipt_show_cashier', 'receipt_show_change',
            'receipt_show_balance_after', 'receipt_show_footer', 'receipt_show_barcode',
        ];

        foreach ($fields as $field) {
            if ($s->$field !== null) {
                $this->$field = $s->$field;
            }
        }
    }

    /* ------------------------------------------------------------------ */
    /* Save                                                                 */
    /* ------------------------------------------------------------------ */

    public function save(): void
    {
        $settings = Settings::first();

        if (! $settings) {
            Notification::make()->title('Settings belum ada di database!')->danger()->send();
            return;
        }

        $settings->update([
            'receipt_store_name'           => $this->receipt_store_name,
            'receipt_store_address'        => $this->receipt_store_address,
            'receipt_store_phone'          => $this->receipt_store_phone,
            'receipt_footer'               => $this->receipt_footer,
            'receipt_layout'               => $this->receipt_layout,
            'receipt_paper_size'           => $this->receipt_paper_size,
            'receipt_show_logo'            => $this->receipt_show_logo,
            'receipt_show_store_name'      => $this->receipt_show_store_name,
            'receipt_show_address'         => $this->receipt_show_address,
            'receipt_show_phone'           => $this->receipt_show_phone,
            'receipt_show_invoice_number'  => $this->receipt_show_invoice_number,
            'receipt_show_date'            => $this->receipt_show_date,
            'receipt_show_student'         => $this->receipt_show_student,
            'receipt_show_payment_method'  => $this->receipt_show_payment_method,
            'receipt_show_item_price'      => $this->receipt_show_item_price,
            'receipt_show_subtotal_per_item' => $this->receipt_show_subtotal_per_item,
            'receipt_show_cashier'         => $this->receipt_show_cashier,
            'receipt_show_change'          => $this->receipt_show_change,
            'receipt_show_balance_after'   => $this->receipt_show_balance_after,
            'receipt_show_footer'          => $this->receipt_show_footer,
            'receipt_show_barcode'         => $this->receipt_show_barcode,
        ]);

        Settings::clearCache();

        Notification::make()
            ->title('Pengaturan struk berhasil disimpan!')
            ->success()
            ->send();
    }

    /* ------------------------------------------------------------------ */
    /* Preview data dummy untuk render live preview                        */
    /* ------------------------------------------------------------------ */

    public function getPreviewData(): array
    {
        return [
            'invoice_number' => 'INV-20250101-001',
            'created_at'     => now()->format('d/m/Y H:i'),
            'student_name'   => 'Ahmad Fauzan',
            'student_class'  => '5A',
            'payment_method' => 'wallet',
            'total_amount'   => 25000,
            'cash_amount'    => 30000,
            'change_amount'  => 5000,
            'balance_after'  => 47500,
            'items' => [
                ['name' => 'Nasi Goreng', 'price' => 10000, 'quantity' => 1],
                ['name' => 'Es Teh Manis', 'price' => 5000,  'quantity' => 2],
                ['name' => 'Bakwan',       'price' => 2500,  'quantity' => 2],
            ],
        ];
    }
}
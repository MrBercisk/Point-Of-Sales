# Setup Struk POS – WhatsApp + PDF + Bluetooth Print

## 1. Install Dependencies

```bash
composer require barryvdh/laravel-dompdf
```

## 2. Letakkan File

| File Output | Lokasi di Project |
|---|---|
| `PosCashier.php` | `app/Filament/Pages/PosCashier.php` |
| `ReceiptService.php` | `app/Services/ReceiptService.php` |
| `WhatsAppService.php` | `app/Services/WhatsAppService.php` |
| `receipt-pdf.blade.php` | `resources/views/receipts/pdf.blade.php` |
| `pos-cashier.blade.php` | `resources/views/filament/pages/pos-cashier.blade.php` |
| `ReceiptController.php` | `app/Http/Controllers/ReceiptController.php` |

> Untuk `receipt-route-and-controller.php`: salin isi route ke `routes/web.php`,
> dan class controller ke `app/Http/Controllers/ReceiptController.php`.

---

## 3. Tambahkan Kolom ke Tabel orders

Buat migration baru:

```bash
php artisan make:migration add_customer_phone_to_orders_table
```

Isi migration:

```php
public function up(): void
{
    Schema::table('orders', function (Blueprint $table) {
        $table->string('customer_phone')->nullable()->after('customer_name');
    });
}
```

```bash
php artisan migrate
```

---

## 4. Update Model Order

Tambahkan `customer_phone` ke `$fillable`:

```php
// app/Models/Order.php
protected $fillable = [
    'customer_name',
    'customer_phone',   // ← tambahkan ini
    'total_amount',
    'status',
    'invoice_number',
];
```

---

## 5. Konfigurasi .env

```env
# Informasi Toko (untuk struk)
POS_STORE_NAME="Nama Toko Anda"
POS_STORE_ADDRESS="Jl. Alamat Toko No. 1"
POS_STORE_PHONE="08123456789"

# WhatsApp via Fonnte (https://fonnte.com)
WHATSAPP_DEVICE_TOKEN=your_fonnte_token_here
WHATSAPP_BASE_URL=https://api.fonnte.com
```

---

## 6. Tambahkan Config POS

Buat file `config/pos.php`:

```php
<?php
return [
    'store_name'    => env('POS_STORE_NAME', 'Toko Saya'),
    'store_address' => env('POS_STORE_ADDRESS', 'Jl. Contoh No. 1'),
    'store_phone'   => env('POS_STORE_PHONE', '08123456789'),
];
```

---

## 7. Tambahkan ke config/services.php

```php
'whatsapp' => [
    'token'    => env('WHATSAPP_DEVICE_TOKEN'),
    'base_url' => env('WHATSAPP_BASE_URL', 'https://api.fonnte.com'),
],
```

---

## 8. Register Service di AppServiceProvider (opsional)

```php
// app/Providers/AppServiceProvider.php
use App\Services\ReceiptService;
use App\Services\WhatsAppService;

public function register(): void
{
    $this->app->singleton(ReceiptService::class);
    $this->app->singleton(WhatsAppService::class);
}
```

---

## 9. Storage Link (untuk foto produk)

```bash
php artisan storage:link
```

---

## Cara Kerja Bluetooth Print

- Menggunakan **Web Bluetooth API** (Chrome/Edge – Android & Desktop)
- **Tidak bisa di Safari/Firefox** (belum support Web Bluetooth)
- Printer thermal Bluetooth ESC/POS yang didukung: RPP02, MTP-II, Goojprt, Munbyn, dll.
- Struk dikirim dalam format **ESC/POS binary** langsung dari browser ke printer via BLE

### Tips Bluetooth:
1. Pastikan printer sudah **pairing** di pengaturan Bluetooth device Anda
2. Klik tombol **"Konek Printer"** di modal struk
3. Pilih printer dari daftar yang muncul
4. Klik **"Cetak Bluetooth"** untuk mencetak

---

## Flow Lengkap Setelah Bayar

```
Kasir klik Bayar
       │
       ▼
  Order dibuat di DB
       │
       ▼
  PDF struk di-generate (DomPDF)
  → disimpan di storage/app/receipts/
       │
       ├──► Jika ada nomor HP pelanggan:
       │         Kirim pesan WA (teks + PDF via Fonnte)
       │
       ▼
  Modal struk muncul (preview struk)
       │
       ├──► Download PDF → buka di tab baru
       │
       └──► Cetak Bluetooth → ESC/POS ke printer thermal
```

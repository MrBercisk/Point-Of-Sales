<?php

namespace App\Filament\Pages;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Category;
use App\Models\Student;
use App\Services\ReceiptService;
use App\Services\WhatsAppService;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use BackedEnum;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class PosCashier_wa extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon  = Heroicon::ComputerDesktop;
    protected static ?string $navigationLabel = 'POS Cashier';
    protected static ?string $title           = 'Point of Sale';
    protected static string|UnitEnum|null $navigationGroup   = 'Sales';
    protected static ?int $navigationSort     = 2;
    protected string $view                    = 'filament.pages.pos-cashier';

    /* ------------------------------------------------------------------ */
    /* State                                                                */
    /* ------------------------------------------------------------------ */

    public Collection $cart;

    // Siswa yang sedang bertransaksi
    public ?int    $selectedStudentId   = null;
    public ?string $selectedStudentName = null;
    public ?string $selectedStudentClass = null;
    public float   $selectedStudentBalance = 0;

    // Search siswa di kasir
    public string $searchStudent = '';
    public bool   $showStudentDropdown = false;

    // Produk
    public string $searchProduct    = '';
    public ?int   $selectedCategory = null;

    // Pembayaran
    public string $paymentMethod = 'wallet'; // wallet | cash
    public float  $cashAmount    = 0;        // jika bayar tunai

    // Modal struk
    public bool   $showReceiptModal = false;
    public ?array $lastOrder        = null;
    // public bool   $isSendingWhatsApp = false;

    /* ------------------------------------------------------------------ */
    /* Mount                                                                */
    /* ------------------------------------------------------------------ */

    public function mount(): void
    {
        $this->cart = collect();
    }

    /* ------------------------------------------------------------------ */
    /* Computed                                                             */
    /* ------------------------------------------------------------------ */

    // #[Computed] → method ini otomatis di-cache & reaktif (Livewire 3).
    // Dipanggil seperti property: $this->products (tanpa kurung).
    // Query DB hanya dijalankan 1x per render, meski dipanggil berkali-kali.
    #[Computed]
    public function categories(): Collection
    {
        return Category::where('is_active', true)
            ->withCount('products')
            ->orderBy('name')
            ->get();
    }

    #[Computed]
    public function products(): Collection
    {
        return Product::query()
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->when($this->searchProduct, fn ($q) =>
                $q->where('name', 'like', '%' . $this->searchProduct . '%')
            )
            ->when($this->selectedCategory, fn ($q) =>
                $q->where('category_id', $this->selectedCategory)
            )
            ->with('category')
            ->orderBy('name')
            ->get();
    }

    #[Computed]
    public function studentSearchResults(): Collection
    {
        if (strlen($this->searchStudent) < 1) return collect();

        return Student::active()
            ->where(function ($q) {
                $q->where('name', 'like', '%' . $this->searchStudent . '%')
                  ->orWhere('class', 'like', '%' . $this->searchStudent . '%')
                  ->orWhere('nisn', 'like', '%' . $this->searchStudent . '%');
            })
            ->orderBy('class')
            ->orderBy('name')
            ->limit(8)
            ->get();
    }

    /* ------------------------------------------------------------------ */
    /* Cart helpers                                                         */
    /* ------------------------------------------------------------------ */

    public function cartTotal(): float
    {
        return $this->cart->sum(fn ($item) => $item['price'] * $item['quantity']);
    }

    public function cartItemsCount(): int
    {
        return $this->cart->sum('quantity');
    }

    public function kembalian(): float
    {
        return max(0, $this->cashAmount - $this->cartTotal());
    }

    public function saldoSetelahBayar(): float
    {
        return max(0, $this->selectedStudentBalance - $this->cartTotal());
    }

    public function saldoCukup(): bool
    {
        return $this->selectedStudentBalance >= $this->cartTotal();
    }

    /* ------------------------------------------------------------------ */
    /* Pilih siswa                                                          */
    /* ------------------------------------------------------------------ */

    public function selectStudent(int $studentId): void
    {
        $student = Student::find($studentId);
        if (! $student) return;

        $this->selectedStudentId      = $student->id;
        $this->selectedStudentName    = $student->name;
        $this->selectedStudentClass   = $student->class;
        $this->selectedStudentBalance = (float) $student->balance;
        $this->searchStudent          = $student->name . ' — Kelas ' . $student->class;
        $this->showStudentDropdown    = false;

        // Default ke dompet
        $this->paymentMethod = 'wallet';
    }

    public function clearStudent(): void
    {
        $this->selectedStudentId      = null;
        $this->selectedStudentName    = null;
        $this->selectedStudentClass   = null;
        $this->selectedStudentBalance = 0;
        $this->searchStudent          = '';
        $this->showStudentDropdown    = false;
        $this->paymentMethod          = 'cash';
    }

    public function updatedSearchStudent(): void
    {
        $this->showStudentDropdown = strlen($this->searchStudent) >= 1;

        // Jika field dikosongkan, reset siswa terpilih
        if (empty($this->searchStudent)) {
            $this->clearStudent();
        }
    }

    /* ------------------------------------------------------------------ */
    /* Kategori & Produk                                                    */
    /* ------------------------------------------------------------------ */

    public function selectCategory(?int $categoryId): void
    {
        $this->selectedCategory = $categoryId === $this->selectedCategory ? null : $categoryId;
    }

    public function addToCart(int $productId): void
    {
        $product = Product::find($productId);

        if (! $product || $product->stock <= 0) {
            Notification::make()->title('Stok habis!')->danger()->send();
            return;
        }

        $existingIndex = $this->cart->search(fn ($item) => $item['product_id'] === $productId);

        if ($existingIndex !== false) {
            $currentQty = $this->cart[$existingIndex]['quantity'];
            if ($currentQty >= $product->stock) {
                Notification::make()
                    ->title('Stok tidak cukup!')
                    ->body("Stok tersedia: {$product->stock}")
                    ->danger()
                    ->send();
                return;
            }
            $cart = $this->cart->toArray();
            $cart[$existingIndex]['quantity']++;
            $this->cart = collect($cart);
        } else {
            $this->cart->push([
                'product_id' => $product->id,
                'name'       => $product->name,
                'price'      => (float) $product->price,
                'quantity'   => 1,
                'stock'      => $product->stock,
                'image'      => $product->image,
            ]);
        }

        Notification::make()->title('Ditambahkan')->success()->duration(800)->send();
    }

    public function removeFromCart(int $index): void
    {
        $cart = $this->cart->toArray();
        unset($cart[$index]);
        $this->cart = collect(array_values($cart));
    }

    public function updateQuantity(int $index, int $quantity): void
    {
        if ($quantity <= 0) {
            $this->removeFromCart($index);
            return;
        }

        $cart = $this->cart->toArray();
        $item = $cart[$index];

        if ($quantity > $item['stock']) {
            Notification::make()
                ->title('Stok tidak cukup!')
                ->body("Stok tersedia: {$item['stock']}")
                ->danger()
                ->send();
            return;
        }

        $cart[$index]['quantity'] = $quantity;
        $this->cart = collect($cart);
    }

    public function incrementQty(int $index): void
    {
        $cart   = $this->cart->toArray();
        $newQty = $cart[$index]['quantity'] + 1;
        $this->updateQuantity($index, $newQty);
    }

    public function decrementQty(int $index): void
    {
        $cart   = $this->cart->toArray();
        $newQty = $cart[$index]['quantity'] - 1;
        $this->updateQuantity($index, $newQty);
    }

    /* ------------------------------------------------------------------ */
    /* Checkout                                                             */
    /* ------------------------------------------------------------------ */

    public function checkout(): void
    {
        if ($this->cart->isEmpty()) {
            Notification::make()->title('Keranjang kosong!')->danger()->send();
            return;
        }

        $total = $this->cartTotal();

        // ── Validasi per metode bayar ──────────────────────────────────
        if ($this->paymentMethod === 'wallet') {
            if (! $this->selectedStudentId) {
                Notification::make()
                    ->title('Pilih siswa terlebih dahulu!')
                    ->body('Pembayaran dompet memerlukan data siswa.')
                    ->danger()
                    ->send();
                return;
            }

            // Refresh saldo dari DB (hindari race condition)
            $student = Student::findOrFail($this->selectedStudentId);
            if (! $student->hasSufficientBalance($total)) {
                Notification::make()
                    ->title('Saldo tidak cukup!')
                    ->body(
                        "Saldo {$student->name}: Rp " . number_format($student->balance, 0, ',', '.') .
                        " | Total: Rp " . number_format($total, 0, ',', '.')
                    )
                    ->danger()
                    ->send();
                return;
            }
        }

        if ($this->paymentMethod === 'cash' && $this->cashAmount < $total) {
            Notification::make()
                ->title('Uang tunai kurang!')
                ->body('Masukkan jumlah uang yang dibayarkan.')
                ->danger()
                ->send();
            return;
        }

        // ── Buat order ─────────────────────────────────────────────────
        $order = Order::create([
            'student_id'     => $this->selectedStudentId,
            'customer_name'  => $this->selectedStudentName,
            'customer_phone' => $this->selectedStudentId
                ? Student::find($this->selectedStudentId)?->parent_phone
                : null,
            'total_amount'   => $total,
            'payment_method' => $this->paymentMethod,
            'cash_amount'    => $this->paymentMethod === 'cash' ? $this->cashAmount : null,
            'change_amount'  => $this->paymentMethod === 'cash' ? $this->kembalian() : null,
            'status'         => 'completed',
        ]);

        // ── Buat order items & kurangi stok ────────────────────────────
        foreach ($this->cart as $item) {
            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $item['product_id'],
                'quantity'   => $item['quantity'],
                'price'      => $item['price'],
                'subtotal'   => $item['price'] * $item['quantity'],
            ]);

            // Kurangi stok produk
            Product::find($item['product_id'])?->reduceStock($item['quantity']);
        }

        // ── Potong saldo dompet siswa jika bayar via dompet ────────────
        $balanceAfter = null;
        if ($this->paymentMethod === 'wallet' && $this->selectedStudentId) {
            $student = Student::findOrFail($this->selectedStudentId);
            $student->deduct(
                amount:    $total,
                reference: $order->invoice_number ?? "ORD-{$order->id}",
                note:      "Belanja di kantin — Order #{$order->id}",
            );
            $balanceAfter = $student->fresh()->balance;
        }

        // ── Generate PDF struk ─────────────────────────────────────────
        $pdfPath = null;
        try {
            $receiptService = app(ReceiptService::class);
            $pdfPath        = $receiptService->generatePdf($order->load('items.product'));
        } catch (\Throwable) {
            // PDF gagal generate tidak menghentikan proses
        }

        // ── Kirim WA ke orang tua siswa ────────────────────────────────
        // $parentPhone = $order->customer_phone;
        // if ($parentPhone && $pdfPath) {
        //     try {
        //         app(WhatsAppService::class)->sendReceipt(
        //             $parentPhone,
        //             $order->load('items.product'),
        //             $pdfPath
        //         );
        //     } catch (\Throwable $e) {
        //         Notification::make()
        //             ->title('WhatsApp gagal dikirim')
        //             ->body($e->getMessage())
        //             ->warning()
        //             ->send();
        //     }
        // }

        // ── Simpan data untuk modal struk ──────────────────────────────
        $this->lastOrder = [
            'id'              => $order->id,
            'invoice_number'  => $order->invoice_number ?? "ORD-{$order->id}",
            'student_name'    => $this->selectedStudentName,
            'student_class'   => $this->selectedStudentClass,
            'payment_method'  => $this->paymentMethod,
            'total_amount'    => $total,
            'cash_amount'     => $this->cashAmount,
            'change_amount'   => $this->kembalian(),
            'balance_after'   => $balanceAfter,
            'created_at'      => $order->created_at->format('d/m/Y H:i'),
            'items'           => $this->cart->toArray(),
            'pdf_url'         => $pdfPath ? route('receipt.download', $order->id) : null,
        ];

        // ── Reset state ────────────────────────────────────────────────
        $this->cart             = collect();
        $this->cashAmount       = 0;
        $this->showReceiptModal = true;

        // Reset siswa agar siap untuk transaksi berikutnya
        $this->clearStudent();

        Notification::make()
            ->title('Transaksi berhasil!')
            ->body("Invoice: " . ($order->invoice_number ?? "ORD-{$order->id}"))
            ->success()
            ->send();
    }

    /* ------------------------------------------------------------------ */
    /* Modal & Print                                                        */
    /* ------------------------------------------------------------------ */

    public function printReceipt(): void
    {
        $this->dispatch('print-receipt', receiptData: $this->lastOrder);
    }

    public function closeReceiptModal(): void
    {
        $this->showReceiptModal = false;
        $this->lastOrder        = null;
    }

    public function clearCart(): void
    {
        $this->cart       = collect();
        $this->cashAmount = 0;
        $this->clearStudent();

        Notification::make()->title('Keranjang dikosongkan')->send();
    }
}
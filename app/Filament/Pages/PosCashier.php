<?php

namespace App\Filament\Pages;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Category;
use App\Models\Student;
use App\Models\Settings;
use App\Services\ReceiptService;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use BackedEnum;
use Filament\Facades\Filament;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Cache;
use UnitEnum;

// permission filament
use App\Filament\Traits\HasFilamentPermission;
use App\Services\OrderItemModifierService;

class PosCashier extends Page implements HasForms
{
    use InteractsWithForms;
    use HasFilamentPermission;
    protected static string $permissionPrefix = 'pos-cashier';

    protected static string|BackedEnum|null $navigationIcon  = Heroicon::ComputerDesktop;
    protected static ?string $navigationLabel = 'POS Cashier';
    protected static ?string $title           = 'Point of Sale';
    protected static ?string $slug = 'pos-cashier';
    protected static string|UnitEnum|null $navigationGroup   = 'Sales';
    protected static ?int $navigationSort     = 2;
    protected string $view                    = 'filament.pages.pos-cashier';

    // mode tamu
    public bool $isGuestMode = false;

    public Collection $cart;

    public ?int    $selectedStudentId     = null;
    public ?string $selectedStudentName   = null;
    public ?string $selectedStudentClass  = null;
    public float   $selectedStudentBalance = 0;

    public string $searchStudent       = '';
    public bool   $showStudentDropdown = false;

    public string $searchProduct    = '';
    public ?int   $selectedCategory = null;
    public ?int   $selectedBrand    = null;

    public string $paymentMethod = 'wallet';
    public float  $cashAmount    = 0;

    public bool   $showReceiptModal = false;
    public ?array $lastOrder        = null;

    
    public bool $showCheckoutModal = false;

    protected bool $lazyLoad = false;


    // modal pilihan tambahan
    public bool $showModifierModal = false;
    public ?int $pendingProductId = null;
    public array $selectedModifiers = [];
    public array $pendingModifierGroups = [];

    public static function canAccess(): bool
    {
        $user = request()->user();

        if (! $user) {
            return false;
        }

        if ($user->hasRole('super_admin')) {
            return true;
        }

        return $user->can('pos-cashier.view');
    }

    
    // fullscreen tanpa sidebar/navbar
    public function getLayout(): string
    {
       return 'filament.layouts.pos'; 
    }
    public function getLayoutData(): array
    {
        return [
            'title' => 'POS Cashier',
        ];
    }


    public function mount(): void
    {
        $this->cart = collect();
    }

    /* computed */

    #[Computed]
    public function categories(): Collection
    {
        return Cache::remember('pos_categories', 60, fn() =>
            Category::where('is_active', true)
                ->withCount('products')
                ->orderBy('name')
                ->get()
        );
    }

    #[Computed]
    public function products(): Collection
    {
        return Product::query()
            ->select('id', 'name', 'price', 'stock', 'image', 'barcode', 'category_id')
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->when($this->searchProduct, fn($q) =>
                $q->where('name', 'like', '%' . $this->searchProduct . '%')
                ->orWhere('barcode', $this->searchProduct)
            )
            ->when($this->selectedCategory, fn($q) =>
                $q->where('category_id', $this->selectedCategory)
            )
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


    /* cart helper */

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


    /* siswa */

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
        $this->paymentMethod          = 'wallet';
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
        if (empty($this->searchStudent)) {
            $this->clearStudent();
        }
    }

    /* kategori produk */

    public function selectCategory(?int $categoryId): void
    {
        $this->selectedCategory = $categoryId === $this->selectedCategory ? null : $categoryId;
    }

    public function addToCart(int $productId): void
    {
        $product = Product::find($productId);

        if (! $product || $product->stock <= 0) {
            $this->dispatch('notify', type: 'error', message: 'Stok habis!');
            return;
        }

        // cek apakah produk punya modifier group aktif
        $modifierGroups = $product->modifierGroups()
            ->where('is_active', true)
            ->with(['modifiers' => fn($q) => $q->where('is_active', true)])
            ->get();

        if ($modifierGroups->isNotEmpty()) {
            // tampilkan modal pilihan tambahan dulu
            $this->pendingProductId = $productId;
            $this->pendingModifierGroups = $modifierGroups->toArray();
            $this->selectedModifiers = [];
            $this->showModifierModal = true;
            return;
        }

        // tidak ada tambahan modifier, langsung masuk keranjang
        $this->pushToCart($product, []);
    }

    private function pushToCart(Product $product, array $modifiers): void
    {
        // hitung total harga modifier yang dipilih
        $modifiersTotal = collect($modifiers)->sum('price');

        $existingIndex = $this->cart->search(
            fn ($item) =>
                $item['product_id'] === $product->id &&
                // kalau ada modifier, tidak digabung — buat item baru
                empty($item['modifiers'])  && empty($modifiers)
        );

        if ($existingIndex !== false && empty($modifiers)) {
            $currentQty = $this->cart[$existingIndex]['quantity'];
            if ($currentQty >= $product->stock) {
                $this->dispatch('notify', type: 'error', message: "Stok tidak cukup! Tersedia: {$product->stock}");
                return;
            }
            $cart = $this->cart->toArray();
            $cart[$existingIndex]['quantity']++;
            $this->cart = collect($cart);
        } else {
            $this->cart->push([
                'product_id'      => $product->id,
                'name'            => $product->name,
                'price'           => (float) $product->price + $modifiersTotal,
                'base_price'      => (float) $product->price,
                'quantity'        => 1,
                'stock'           => $product->stock,
                'image'           => $product->image,
                'modifiers'       => $modifiers,
            ]);
        }

        $this->dispatch('product-added');
    }
    public function confirmModifiers(): void
    {
        $product = Product::find($this->pendingProductId);
        if (! $product) return;

        // validasi modifier wajib
        foreach ($this->pendingModifierGroups as $group) {
            if ($group['is_required']) {
                $hasSelected = collect($this->selectedModifiers)
                    ->contains(fn($id) =>
                        collect($group['modifiers'])->contains('id', $id)
                    );
                if (! $hasSelected) {
                    $this->dispatch('notify', type: 'error', message: "Pilihan \"{$group['name']}\" wajib dipilih!");
                    return;
                }
            }
        }

        // ambil data modifier yang dipilih
        $selectedModifierData = [];
        foreach ($this->selectedModifiers as $modifierId) {
            foreach ($this->pendingModifierGroups as $group) {
                $modifier = collect($group['modifiers'])->firstWhere('id', $modifierId);
                if ($modifier) {
                    $selectedModifierData[] = [
                        'id'    => $modifier['id'],
                        'name'  => $modifier['name'],
                        'price' => (float) $modifier['price'],
                    ];
                }
            }
        }

        $this->pushToCart($product, $selectedModifierData);
        $this->closeModifierModal();
    }

    public function closeModifierModal(): void
    {
        $this->showModifierModal = false;
        $this->pendingProductId = null;
        $this->selectedModifiers = [];
        $this->pendingModifierGroups = [];
    }

    public function toggleModifier(int $modifierId, int $groupId, int $maxSelect): void
    {
        // cek berapa yang sudah dipilih dari grup ini
        $groupModifierIds = collect($this->pendingModifierGroups)
            ->firstWhere('id', $groupId)['modifiers'] ?? [];
        $groupModifierIds = collect($groupModifierIds)->pluck('id')->toArray();

        $selectedInGroup = collect($this->selectedModifiers)
            ->filter(fn($id) => in_array($id, $groupModifierIds))
            ->values();

        if (in_array($modifierId, $this->selectedModifiers)) {
            // deselect
            $this->selectedModifiers = collect($this->selectedModifiers)
                ->filter(fn($id) => $id !== $modifierId)
                ->values()
                ->toArray();
        } else {
            // cek max select
            if ($selectedInGroup->count() >= $maxSelect) {
                if ($maxSelect === 1) {
                    // kalau max 1, replace pilihan sebelumnya
                    $this->selectedModifiers = collect($this->selectedModifiers)
                        ->filter(fn($id) => ! in_array($id, $groupModifierIds))
                        ->values()
                        ->toArray();
                } else {
                    $this->dispatch('notify', type: 'error', message: "Maksimal {$maxSelect} pilihan untuk grup ini!");
                    return;
                }
            }
            $this->selectedModifiers[] = $modifierId;
        }
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

    
    /* checkout */

    public function checkout(): void
    {
        abort_unless(
            request()->user()?->can('pos-cashier.view'),
            403
        );
        if ($this->cart->isEmpty()) {
            Notification::make()->title('Keranjang kosong!')->danger()->send();
            return;
        }

        $total = $this->cartTotal();

        if ($this->paymentMethod === 'wallet') {
            if ($this->isGuestMode) {
                // tamu cuma pakai cash
                $this->paymentMethod = 'cash';
            } elseif (! $this->selectedStudentId) {
                Notification::make()
                    ->title('Pilih siswa terlebih dahulu!')
                    ->body('Pembayaran dompet memerlukan data siswa.')
                    ->danger()
                    ->send();
                return;
            } else {
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
        }

        if ($this->paymentMethod === 'cash' && $this->cashAmount < $total) {
            Notification::make()
                ->title('Uang tunai kurang!')
                ->body('Masukkan jumlah uang yang dibayarkan.')
                ->danger()
                ->send();
            return;
        }

        $order = Order::create([
            'student_id'     => $this->selectedStudentId,
            'customer_name'  => $this->selectedStudentName ?? ($this->isGuestMode ? 'Tamu' : null),
            'customer_phone' => $this->selectedStudentId
                ? Student::find($this->selectedStudentId)?->parent_phone
                : null,
            'total_amount'   => $total,
            'payment_method' => $this->paymentMethod,
            'cash_amount'    => $this->paymentMethod === 'cash' ? $this->cashAmount : null,
            'change_amount'  => $this->paymentMethod === 'cash' ? $this->kembalian() : null,
            'status'         => 'completed',
        ]);

        foreach ($this->cart as $item) {
            $orderItem = OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $item['product_id'],
                'quantity'   => $item['quantity'],
                'price'      => $item['base_price'] ?? $item['price'],
                'subtotal'   => $item['price'] * $item['quantity'],
            ]);
            if (! empty($item['modifiers'])) {
                $modifierService = app(OrderItemModifierService::class);
                $modifierIds = collect($item['modifiers'])->pluck('id')->toArray();
                $modifierService->attachModifiers($orderItem, $modifierIds);
            }
        }

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

        $pdfPath = null;
        try {
            $receiptService = app(ReceiptService::class);
            $pdfPath        = $receiptService->generatePdf($order->load('items.product'));
        } catch (\Throwable) {}

        $settings = Settings::current();

        $this->lastOrder = [
            'id'             => $order->id,
            'invoice_number' => $order->invoice_number ?? "ORD-{$order->id}",
            'student_name'   => $this->selectedStudentName,
            'student_class'  => $this->selectedStudentClass,
            'payment_method' => $this->paymentMethod,
            'total_amount'   => $total,
            'cash_amount'    => $this->cashAmount,
            'change_amount'  => $this->kembalian(),
            'balance_after'  => $balanceAfter,
            'created_at'     => $order->created_at->format('d/m/Y H:i'),
            'items'          => $this->cart->toArray(),
            'pdf_url'        => $pdfPath ? route('receipt.download', $order->id) : null,
            'cashier_name'   => Filament::auth()->user()?->name ?? 'Kasir',

            'store_name'    => $settings->receipt_store_name    ?: 'Kantin Sekolah',
            'store_address' => $settings->receipt_store_address ?: '',
            'store_phone'   => $settings->receipt_store_phone   ?: '',
            'store_footer'  => $settings->receipt_footer        ?: 'Terima kasih! Selamat belajar.',
            'paper_size'    => $settings->receipt_paper_size    ?: '80mm',
            'layout'        => $settings->receipt_layout        ?: 'standard',

            'show_store_name'        => (bool) ($settings->receipt_show_store_name        ?? true),
            'show_address'           => (bool) ($settings->receipt_show_address           ?? true),
            'show_phone'             => (bool) ($settings->receipt_show_phone             ?? true),
            'show_invoice_number'    => (bool) ($settings->receipt_show_invoice_number    ?? true),
            'show_date'              => (bool) ($settings->receipt_show_date              ?? true),
            'show_student'           => (bool) ($settings->receipt_show_student           ?? true),
            'show_payment_method'    => (bool) ($settings->receipt_show_payment_method    ?? true),
            'show_cashier'           => (bool) ($settings->receipt_show_cashier           ?? false),
            'show_item_price'        => (bool) ($settings->receipt_show_item_price        ?? true),
            'show_subtotal_per_item' => (bool) ($settings->receipt_show_subtotal_per_item ?? true),
            'show_change'            => (bool) ($settings->receipt_show_change            ?? true),
            'show_balance_after'     => (bool) ($settings->receipt_show_balance_after     ?? true),
            'show_footer'            => (bool) ($settings->receipt_show_footer            ?? true),
            'show_barcode'           => (bool) ($settings->receipt_show_barcode           ?? false),
        ];

        $this->cart             = collect();
        $this->showReceiptModal = true;
        $this->clearStudent();

        Notification::make()
            ->title('Transaksi berhasil!')
            ->body("Invoice: " . ($order->invoice_number ?? "ORD-{$order->id}"))
            ->success()
            ->send();
    }

    public function printReceipt(): void
    {
        $this->dispatch('print-receipt', receiptData: $this->lastOrder);
    }

    public function closeReceiptModal(): void
    {
        $this->showReceiptModal = false;
        $this->lastOrder        = null;
        $this->cashAmount       = 0;
    }

    public function clearCart(): void
    {
        $this->cart       = collect();
        $this->cashAmount = 0;
        $this->clearStudent();
        Notification::make()->title('Keranjang dikosongkan')->send();
    }


    public function openCheckout(): void
    {
        if ($this->cart->isEmpty()) return;
        $this->showCheckoutModal = true;
    }

    public function closeCheckoutModal(): void
    {
        $this->showCheckoutModal = false;
    }

    public function checkoutFromModal(string $method, float $cash): void
    {
        $this->paymentMethod = $method;
        $this->cashAmount    = $cash;
        $this->showCheckoutModal = false;
        $this->checkout();
    }

    public function refreshProducts(): void
    {
        /* jangan refresh klo modalnya terbuka */
        if ($this->showCheckoutModal || $this->showReceiptModal) return;
        unset($this->products);
        unset($this->categories);
    }
    
    /* tamu */
    public function setGuestMode(bool $guest): void
    {
        $this->isGuestMode = $guest;
        if ($guest) {
            $this->clearStudent();
            $this->paymentMethod = 'cash'; // tamu hanya bisa tunai
        }else{
            $this->paymentMethod = 'wallet'; // default ke dompet kalau bukan tamu
        }
    }
}

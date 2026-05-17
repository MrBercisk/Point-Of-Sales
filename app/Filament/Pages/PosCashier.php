<?php

namespace App\Filament\Pages;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Category;
use App\Models\Student;
use App\Models\Settings;
use App\Models\Draft;
use App\Services\ReceiptService;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use BackedEnum;
use Filament\Facades\Filament;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Cache;
use UnitEnum;

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
    protected static ?string $slug            = 'pos-cashier';
    protected static string|UnitEnum|null $navigationGroup = 'Sales';
    protected static ?int $navigationSort     = 2;
    protected string $view                    = 'filament.pages.pos-cashier';

    // Guest / Student
    public bool    $isGuestMode = false;
    public ?int    $selectedStudentId      = null;
    public ?string $selectedStudentName    = null;
    public ?string $selectedStudentClass   = null;
    public float   $selectedStudentBalance = 0;
    public string  $searchStudent          = '';
    public bool    $showStudentDropdown    = false;

    // Product / Category
    public string $searchProduct    = '';
    public ?int   $selectedCategory = null;

    // Payment
    public string $paymentMethod = 'wallet';
    public float  $cashAmount    = 0;

    // Cart
    public Collection $cart;

    // Modals
    public bool   $showReceiptModal  = false;
    public ?array $lastOrder         = null;
    public bool   $showCheckoutModal = false;

    // Modifier modal
    public bool   $showModifierModal     = false;
    public ?int   $pendingProductId      = null;
    public array  $selectedModifiers     = [];
    public array  $pendingModifierGroups = [];

    // Draft
    public bool   $showSaveDraftModal = false;
    public bool   $showDraftPanel     = false;
    public string $draftLabel         = '';
    public ?int $currentDraftId = null; // track draft yang sedang dimuat

    // Draft cart payload — public agar persisten antar Livewire request
    public array   $draftCartPayload  = [];
    public ?int    $draftStudentId    = null;
    public ?string $draftStudentName  = null;
    public ?string $draftStudentClass = null;
    public float   $draftStudentBal   = 0;
    public bool    $draftIsGuest      = false;
    public string  $draftPayMethod    = 'cash';

    protected bool $lazyLoad = false;

    // ── Access ────────────────────────────────────────────────────────

    public static function canAccess(): bool
    {
        $user = request()->user();
        if (! $user) return false;
        if ($user->hasRole('super_admin')) return true;
        return $user->can('pos-cashier.view');
    }

    public function getLayout(): string
    {
        return 'filament.layouts.pos';
    }

    public function getLayoutData(): array
    {
        return ['title' => 'POS Cashier'];
    }

    public function mount(): void
    {
        $this->cart = collect();
    }

    // ── Computed ──────────────────────────────────────────────────────

    #[Computed]
    public function categories(): Collection
    {
        return Cache::remember('pos_categories', 60, fn() =>
            Category::where('is_active', true)
                ->withCount(['products' => fn($q) => $q
                    ->where('is_active', true)
                    ->where('stock', '>', 0)
                    ->where('not_for_selling', false)
                ])
                ->orderBy('name')
                ->get()
        );
    }

    #[Computed]
    public function products(): Collection
    {
        return Product::query()
            ->select('id', 'name', 'price', 'stock', 'image', 'barcode', 'category_id')
            ->withCount('modifierGroups')
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->where('not_for_selling', false)
            ->when($this->searchProduct, fn($q) =>
                $q->where(fn($q2) =>
                    $q2->where('name', 'like', '%' . $this->searchProduct . '%')
                       ->orWhere('barcode', $this->searchProduct)
                )
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
            ->where(fn($q) =>
                $q->where('name', 'like', '%' . $this->searchStudent . '%')
                  ->orWhere('class', 'like', '%' . $this->searchStudent . '%')
                  ->orWhere('nisn', 'like', '%' . $this->searchStudent . '%')
            )
            ->orderBy('class')
            ->orderBy('name')
            ->limit(8)
            ->get();
    }

    #[Computed]
    public function savedDrafts(): Collection
    {
        $userId = Filament::auth()->id();
        return Cache::remember("pos_drafts_{$userId}", 10, fn() =>
            Draft::where('user_id', $userId)
                 ->latest()
                 ->limit(20)
                 ->get()
        );
    }

    // ── Cart helpers ──────────────────────────────────────────────────

    public function cartTotal(): float
    {
        return $this->cart->sum(fn($i) => $i['price'] * $i['quantity']);
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

    // ── Get product data for cart ─────────────────────────────────────

    public function getProductForCart(int $productId): ?array
    {
        $product = Product::select('id', 'name', 'price', 'stock', 'image', 'barcode')
            ->find($productId);

        if (! $product || $product->stock <= 0) {
            return ['action' => 'error', 'message' => 'Stok habis!'];
        }

        $modifierGroups = $product->modifierGroups()
            ->where('is_active', true)
            ->with(['modifiers' => fn($q) => $q->where('is_active', true)])
            ->get();

        if ($modifierGroups->isNotEmpty()) {
            $this->pendingProductId      = $productId;
            $this->pendingModifierGroups = $modifierGroups->toArray();
            $this->selectedModifiers     = [];
            $this->showModifierModal     = true;
            return ['action' => 'modifier_modal'];
        }

        return [
            'action'     => 'add_to_cart',
            'product_id' => $product->id,
            'name'       => $product->name,
            'price'      => (float) $product->price,
            'base_price' => (float) $product->price,
            'stock'      => $product->stock,
            'image'      => $product->image,
            'modifiers'  => [],
        ];
    }

    // ── Checkout ──────────────────────────────────────────────────────

    public function checkoutFromAlpine(
        array   $cart,
        ?int    $studentId,
        ?string $studentName,
        ?string $studentClass,
        float   $studentBalance,
        bool    $isGuest,
        string  $paymentMethod,
        float   $cashAmount
    ): void {
        abort_unless(request()->user()?->can('pos-cashier.view'), 403);

        $this->cart                   = collect($cart);
        $this->selectedStudentId      = $studentId;
        $this->selectedStudentName    = $studentName;
        $this->selectedStudentClass   = $studentClass;
        $this->selectedStudentBalance = $studentBalance;
        $this->isGuestMode            = $isGuest;
        $this->paymentMethod          = $paymentMethod;
        $this->cashAmount             = $cashAmount;

        if ($this->cart->isEmpty()) {
            $this->toast('Keranjang kosong!', 'error');
            return;
        }

        $total = $this->cartTotal();

        // Payment validation
        if ($paymentMethod === 'wallet') {
            if ($isGuest) {
                $this->paymentMethod = 'cash';
            } elseif (! $studentId) {
                $this->toast('Pilih siswa terlebih dahulu! Pembayaran dompet memerlukan data siswa.', 'error');
                return;
            } else {
                $student = Student::findOrFail($studentId);
                if (! $student->hasSufficientBalance($total)) {
                    $this->toast(
                        'Saldo tidak cukup! Saldo: Rp ' . number_format($student->balance, 0, ',', '.') .
                        ' | Total: Rp ' . number_format($total, 0, ',', '.'),
                        'error'
                    );
                    return;
                }
            }
        }

        if ($this->paymentMethod === 'cash' && $cashAmount < $total) {
            $this->toast('Uang tunai kurang! Masukkan jumlah uang yang dibayarkan.', 'error');
            return;
        }

        // Create order
        $order = Order::create([
            'student_id'     => $studentId,
            'customer_name'  => $studentName ?? ($isGuest ? 'Tamu' : null),
            'customer_phone' => $studentId
                ? Student::find($studentId)?->parent_phone
                : null,
            'total_amount'   => $total,
            'payment_method' => $this->paymentMethod,
            'cash_amount'    => $this->paymentMethod === 'cash' ? $cashAmount : null,
            'change_amount'  => $this->paymentMethod === 'cash' ? $this->kembalian() : null,
            'status'         => 'completed',
        ]);

        foreach ($this->cart as $item) {
            $basePrice      = (float) ($item['base_price'] ?? $item['price']);
            $modifiersTotal = collect($item['modifiers'] ?? [])->sum('price');

            $orderItem = OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $item['product_id'],
                'quantity'   => $item['quantity'],
                'price'      => $basePrice,
                'subtotal'   => ($basePrice + $modifiersTotal) * $item['quantity'],
            ]);

            if (! empty($item['modifiers'])) {
                $modifierService = app(OrderItemModifierService::class);
                $modifierIds     = collect($item['modifiers'])->pluck('id')->toArray();
                $modifierService->attachModifiers($orderItem, $modifierIds);
            }
        }

        $order->updateQuietly(['total_amount' => $total]);

        // Hapus draft yang sedang aktif jika ada
        if ($this->currentDraftId) {
            Draft::where('user_id', Filament::auth()->id())
                ->where('id', $this->currentDraftId)
                ->delete();
            Cache::forget('pos_drafts_' . Filament::auth()->id());
            $this->currentDraftId = null;
        }

        // Deduct wallet
        $balanceAfter = null;
        if ($this->paymentMethod === 'wallet' && $studentId) {
            $student = Student::findOrFail($studentId);
            $student->deduct(
                amount:    $total,
                reference: $order->invoice_number ?? "ORD-{$order->id}",
                note:      "Belanja di kantin — Order #{$order->id}",
            );
            $balanceAfter = $student->fresh()->balance;
        }

        // PDF
        $pdfPath = null;
        try {
            $pdfPath = app(ReceiptService::class)->generatePdf(
                $order->load('items.product', 'items.modifiers.modifier')
            );
        } catch (\Throwable) {}

        $settings = Settings::current();

        $this->lastOrder = [
            'id'             => $order->id,
            'invoice_number' => $order->invoice_number ?? "ORD-{$order->id}",
            'student_name'   => $studentName,
            'student_class'  => $studentClass,
            'payment_method' => $this->paymentMethod,
            'total_amount'   => $total,
            'cash_amount'    => $cashAmount,
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

        Cache::forget('pos_drafts_' . Filament::auth()->id());

        $this->dispatch('checkout-success');

        $this->toast('Transaksi berhasil! Invoice: ' . ($order->invoice_number ?? "ORD-{$order->id}"));
    }

    // ── Modifier modal ────────────────────────────────────────────────

    public function confirmModifiers(): void
    {
        if (! $this->showModifierModal || ! $this->pendingProductId) return;

        $product = Product::find($this->pendingProductId);
        if (! $product) return;

        foreach ($this->pendingModifierGroups as $group) {
            if ($group['is_required']) {
                $hasSelected = collect($this->selectedModifiers)
                    ->contains(fn($id) => collect($group['modifiers'])->contains('id', $id));
                if (! $hasSelected) {
                    $this->toast("Pilihan \"{$group['name']}\" wajib dipilih!", 'error');
                    return;
                }
            }
        }

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

        $modifiersTotal = collect($selectedModifierData)->sum('price');

        // Tutup modal DULU sebelum dispatch
        $this->closeModifierModal();

        $this->dispatch('modifier-confirmed', item: [
            'product_id' => $product->id,
            'name'       => $product->name,
            'price'      => (float) $product->price + $modifiersTotal,
            'base_price' => (float) $product->price,
            'stock'      => $product->stock,
            'image'      => $product->image,
            'modifiers'  => $selectedModifierData,
        ]);
    }

    public function closeModifierModal(): void
    {
        $this->showModifierModal     = false;
        $this->pendingProductId      = null;
        $this->selectedModifiers     = [];
        $this->pendingModifierGroups = [];
    }

    public function toggleModifier(int $modifierId, int $groupId, int $maxSelect): void
    {
        $groupModifierIds = collect($this->pendingModifierGroups)
            ->firstWhere('id', $groupId)['modifiers'] ?? [];
        $groupModifierIds = collect($groupModifierIds)->pluck('id')->toArray();

        $selectedInGroup = collect($this->selectedModifiers)
            ->filter(fn($id) => in_array($id, $groupModifierIds))
            ->values();

        if (in_array($modifierId, $this->selectedModifiers)) {
            $this->selectedModifiers = collect($this->selectedModifiers)
                ->filter(fn($id) => $id !== $modifierId)
                ->values()->toArray();
        } else {
            if ($selectedInGroup->count() >= $maxSelect) {
                if ($maxSelect === 1) {
                    $this->selectedModifiers = collect($this->selectedModifiers)
                        ->filter(fn($id) => ! in_array($id, $groupModifierIds))
                        ->values()->toArray();
                } else {
                    $this->toast("Maksimal {$maxSelect} pilihan untuk grup ini!", 'warning');
                    return;
                }
            }
            $this->selectedModifiers[] = $modifierId;
        }
    }

    // ── Draft ─────────────────────────────────────────────────────────

    public function openSaveDraftModal(
        array   $cart,
        ?int    $studentId,
        ?string $studentName,
        ?string $studentClass,
        float   $studentBalance,
        bool    $isGuest,
        string  $payMethod
    ): void {
        if (empty($cart)) {
            $this->toast('Keranjang kosong!', 'error');
            return;
        }

        $this->draftCartPayload  = $cart;
        $this->draftStudentId    = $studentId;
        $this->draftStudentName  = $studentName;
        $this->draftStudentClass = $studentClass;
        $this->draftStudentBal   = $studentBalance;
        $this->draftIsGuest      = $isGuest;
        $this->draftPayMethod    = $payMethod;
        $this->draftLabel        = '';

        $this->showSaveDraftModal = true;
    }

    public function closeSaveDraftModal(): void
    {
        $this->showSaveDraftModal = false;
        $this->draftLabel         = '';
    }

    public function saveDraft(): void
    {
        if (empty($this->draftCartPayload)) {
            $this->closeSaveDraftModal();
            return;
        }

        $total     = collect($this->draftCartPayload)->sum(fn($i) => $i['price'] * $i['quantity']);
        $itemCount = collect($this->draftCartPayload)->sum('quantity');

        Draft::create([
            'user_id'         => Filament::auth()->id(),
            'label'           => $this->draftLabel ?: null,
            'cart'            => $this->draftCartPayload,
            'student_id'      => $this->draftStudentId,
            'student_name'    => $this->draftStudentName,
            'student_class'   => $this->draftStudentClass,
            'student_balance' => $this->draftStudentBal,
            'is_guest'        => $this->draftIsGuest,
            'payment_method'  => $this->draftPayMethod,
            'total'           => $total,
            'item_count'      => $itemCount,
        ]);

        Cache::forget('pos_drafts_' . Filament::auth()->id());

        $this->currentDraftId   = null; //reset, ini draft baru bukan yg dimuat
        $this->draftCartPayload = [];
        $this->closeSaveDraftModal();

        $this->dispatch('draft-saved');

        $this->toast('Draft disimpan!');
    }

    public function loadDraft(int $draftId): void
    {
        $draft = Draft::where('user_id', Filament::auth()->id())->find($draftId);
        if (! $draft) return;

        // draft yang dimuat
        $this->currentDraftId = $draftId;

        $this->dispatch('draft-loaded', payload: [
            'cart'            => $draft->cart,
            'student_id'      => $draft->student_id,
            'student_name'    => $draft->student_name,
            'student_class'   => $draft->student_class,
            'student_balance' => (float) ($draft->student_balance ?? 0),
            'is_guest'        => (bool) $draft->is_guest,
            'pay_method'      => $draft->payment_method ?? 'cash',
        ]);

        $this->showDraftPanel = false;

        $this->toast('Draft dimuat!');
    }

    public function deleteDraft(int $draftId): void
    {
        Draft::where('user_id', Filament::auth()->id())->where('id', $draftId)->delete();
        Cache::forget('pos_drafts_' . Filament::auth()->id());
        $this->toast('Draft dihapus.');
    }

    public function toggleDraftPanel(): void
    {
        $this->showDraftPanel = ! $this->showDraftPanel;
        Cache::forget('pos_drafts_' . Filament::auth()->id());
    }

    // ── Student ───────────────────────────────────────────────────────

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

        $this->dispatch('student-selected', student: [
            'id'      => $student->id,
            'name'    => $student->name,
            'class'   => $student->class,
            'balance' => (float) $student->balance,
        ]);
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
        if (empty($this->searchStudent)) $this->clearStudent();
    }

    public function selectCategory(?int $categoryId): void
    {
        $this->selectedCategory = $categoryId === $this->selectedCategory ? null : $categoryId;
    }

    // ── Receipt / checkout modal helpers ──────────────────────────────

    public function closeReceiptModal(): void
    {
        $this->showReceiptModal = false;
        $this->lastOrder        = null;
        $this->cashAmount       = 0;
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

    public function setGuestMode(bool $guest): void
    {
        $this->isGuestMode = $guest;
        if ($guest) {
            $this->clearStudent();
            $this->paymentMethod = 'cash';
        } else {
            $this->paymentMethod = 'wallet';
        }
    }

    // ── Product poll ──────────────────────────────────────────────────

    public function refreshProducts(): void
    {
        if ($this->showCheckoutModal || $this->showReceiptModal
            || $this->showModifierModal || $this->showSaveDraftModal) {
            return;
        }
        unset($this->products);
        unset($this->categories);
    }

    // ── Legacy server-side cart (kept for compatibility) ──────────────

    public function addToCart(int $productId): void
    {
        $result = $this->getProductForCart($productId);
        if (! $result || $result['action'] === 'error') return;
        if ($result['action'] === 'modifier_modal') return;
        if ($result['action'] === 'add_to_cart') {
            $this->pushToCartFromArray($result);
        }
    }

    private function pushToCartFromArray(array $item): void
    {
        $product = Product::find($item['product_id']);
        if (! $product) return;
        $this->pushToCart($product, $item['modifiers'] ?? []);
    }

    private function pushToCart(Product $product, array $modifiers): void
    {
        $modifiersTotal = collect($modifiers)->sum('price');

        $existingIndex = empty($modifiers)
            ? $this->cart->search(
                fn($i) => $i['product_id'] === $product->id && empty($i['modifiers'])
            )
            : false;

        if ($existingIndex !== false) {
            $currentQty = $this->cart[$existingIndex]['quantity'];
            if ($currentQty >= $product->stock) {
                $this->toast("Stok tidak cukup! Tersedia: {$product->stock}", 'error');
                return;
            }
            $cart = $this->cart->toArray();
            $cart[$existingIndex]['quantity']++;
            $this->cart = collect($cart);
        } else {
            $this->cart->push([
                'product_id' => $product->id,
                'name'       => $product->name,
                'price'      => (float) $product->price + $modifiersTotal,
                'base_price' => (float) $product->price,
                'quantity'   => 1,
                'stock'      => $product->stock,
                'image'      => $product->image,
                'modifiers'  => $modifiers,
            ]);
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
        if ($quantity <= 0) { $this->removeFromCart($index); return; }
        $cart = $this->cart->toArray();
        $item = $cart[$index];
        if ($quantity > $item['stock']) {
            $this->toast("Stok tidak cukup! Tersedia: {$item['stock']}", 'error');
            return;
        }
        $cart[$index]['quantity'] = $quantity;
        $this->cart = collect($cart);
    }

    public function incrementQty(int $index): void
    {
        $this->updateQuantity($index, $this->cart->toArray()[$index]['quantity'] + 1);
    }

    public function decrementQty(int $index): void
    {
        $this->updateQuantity($index, $this->cart->toArray()[$index]['quantity'] - 1);
    }

    public function clearCart(): void
    {
        $this->cart       = collect();
        $this->cashAmount = 0;
        $this->clearStudent();
        $this->toast('Keranjang dikosongkan.');
    }

    // Toast helper

    private function toast(string $message, string $type = 'success'): void
    {
        $this->js("
            window.dispatchEvent(new CustomEvent('show-toast', {
                detail: { message: " . json_encode($message) . ", type: " . json_encode($type) . " }
            }));
        ");
    }
}
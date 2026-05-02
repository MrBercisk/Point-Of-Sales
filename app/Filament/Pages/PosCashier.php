<?php

namespace App\Filament\Pages;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Category;
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

class PosCashier extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ComputerDesktop;
    protected static ?string $navigationLabel = 'POS Cashier';
    protected static ?string $title = 'Point of Sale';
     protected static string|UnitEnum|null $navigationGroup = 'Sales';
    protected static ?int $navigationSort = 2; 
    protected string $view = 'filament.pages.pos-cashier';

    // Properties
    public Collection $cart;
    public string $customerName = '';
    public string $customerPhone = '';  // Nomor WA pelanggan
    public string $searchProduct = '';
    public ?int $selectedCategory = null;

    // Receipt modal state
    public bool $showReceiptModal = false;
    public ?array $lastOrder = null;       // Data order terakhir untuk ditampilkan di modal
    public bool $isSendingWhatsApp = false;

    public function mount(): void
    {
        $this->cart = collect();
    }

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
            ->when($this->searchProduct, function ($query) {
                $query->where('name', 'like', '%' . $this->searchProduct . '%');
            })
            ->when($this->selectedCategory, function ($query) {
                $query->where('category_id', $this->selectedCategory);
            })
            ->with('category')
            ->orderBy('name')
            ->get();
    }

    public function cartTotal(): float
    {
        return $this->cart->sum(fn ($item) => $item['price'] * $item['quantity']);
    }

    public function cartItemsCount(): int
    {
        return $this->cart->sum('quantity');
    }

    public function selectCategory(?int $categoryId): void
    {
        $this->selectedCategory = $categoryId === $this->selectedCategory ? null : $categoryId;
    }

    public function addToCart(int $productId): void
    {
        $product = Product::find($productId);

        if (!$product || $product->stock <= 0) {
            Notification::make()->title('Product out of stock!')->danger()->send();
            return;
        }

        $existingIndex = $this->cart->search(fn ($item) => $item['product_id'] === $productId);

        if ($existingIndex !== false) {
            $currentQty = $this->cart[$existingIndex]['quantity'];
            if ($currentQty >= $product->stock) {
                Notification::make()
                    ->title('Not enough stock!')
                    ->body("Available: {$product->stock}")
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
                'price'      => $product->price,
                'quantity'   => 1,
                'stock'      => $product->stock,
                'image'      => $product->image,
            ]);
        }

        Notification::make()->title('Added to cart')->success()->duration(1000)->send();
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

        $cart  = $this->cart->toArray();
        $item  = $cart[$index];

        if ($quantity > $item['stock']) {
            Notification::make()
                ->title('Not enough stock!')
                ->body("Available: {$item['stock']}")
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

    // ─── Checkout ──────────────────────────────────────────────────────────────

    public function checkout(): void
    {
        if ($this->cart->isEmpty()) {
            Notification::make()->title('Cart is empty!')->danger()->send();
            return;
        }

        // 1. Buat order
        $order = Order::create([
            'customer_name'  => $this->customerName ?: null,
            'customer_phone' => $this->customerPhone ?: null,
            'total_amount'   => $this->cartTotal(),
            'status'         => 'completed',
        ]);

        // 2. Buat order items
        foreach ($this->cart as $item) {
            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $item['product_id'],
                'quantity'   => $item['quantity'],
                'price'      => $item['price'],
                'subtotal'   => $item['price'] * $item['quantity'],
            ]);
        }

        // 3. Generate PDF struk via ReceiptService
        $receiptService  = app(ReceiptService::class);
        $orderWithItems  = $order->load('items.product');
        $pdfPath         = $receiptService->generatePdf($orderWithItems);

        // 4. Kirim WA jika ada nomor pelanggan
        if ($this->customerPhone) {
            try {
                app(WhatsAppService::class)->sendReceipt(
                    $this->customerPhone,
                    $orderWithItems,
                    $pdfPath
                );
            } catch (\Throwable $e) {
                // Gagal kirim WA tidak menghentikan proses
                Notification::make()
                    ->title('WhatsApp gagal dikirim')
                    ->body($e->getMessage())
                    ->warning()
                    ->send();
            }
        }

        // 5. Simpan data untuk modal struk
        $this->lastOrder = [
            'id'             => $order->id,
            'invoice_number' => $order->invoice_number,
            'customer_name'  => $order->customer_name,
            'customer_phone' => $order->customer_phone,
            'total_amount'   => $order->total_amount,
            'created_at'     => $order->created_at->format('d/m/Y H:i'),
            'items'          => $this->cart->toArray(),
            'pdf_url'        => route('receipt.download', $order->id),
        ];

        // 6. Reset cart & buka modal struk
        $this->cart          = collect();
        $this->customerName  = '';
        $this->customerPhone = '';
        $this->showReceiptModal = true;

        Notification::make()
            ->title('Order selesai!')
            ->body("Invoice: {$order->invoice_number}")
            ->success()
            ->send();
    }

    // ─── Modal & Print ─────────────────────────────────────────────────────────

    /**
     * Dipanggil dari JS: emit event ke browser untuk cetak struk via Bluetooth
     * Mengirimkan data ESC/POS-ready ke window.bluetoothPrint()
     */
    public function printReceipt(): void
    {
        // Dispatch browser event → blade akan menangkap dan trigger print
        $this->dispatch('print-receipt', receiptData: $this->lastOrder);
    }

    public function closeReceiptModal(): void
    {
        $this->showReceiptModal = false;
        $this->lastOrder = null;
    }

    public function clearCart(): void
    {
        $this->cart = collect();
        $this->customerName  = '';
        $this->customerPhone = '';

        Notification::make()->title('Cart cleared')->send();
    }
}
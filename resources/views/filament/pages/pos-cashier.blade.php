<x-filament::page>
    @push('styles')
        @vite('resources/css/app.css')
    @endpush

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        .pos-wrap * {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .pos-wrap {
          --pos-accent: #16a34a;
            --pos-accent-dark: #15803d;
            --pos-bg: #0f172a;
            --pos-surface: #1e293b;
            --pos-surface2: #273449;
            --pos-border: #334155;
            --pos-text: #f1f5f9;
            --pos-muted: #94a3b8;
            --pos-success: #10b981;
            --pos-danger: #ef4444;
        }

        .pos-wrap {
            display: grid;
            grid-template-columns: 1fr 380px;
            gap: 20px;
            min-height: calc(100vh - 120px);
        }

        @media (max-width: 1024px) {
            .pos-wrap { grid-template-columns: 1fr; }
        }

        /* LEFT PANEL */
        .pos-left { display: flex; flex-direction: column; gap: 16px; }

        /* Search bar */
        .pos-search-box {
            background: var(--pos-surface);
            border: 1px solid var(--pos-border);
            border-radius: 16px;
            padding: 16px 20px;
        }

        .pos-search-input {
            width: 100%;
            background: var(--pos-bg);
            border: 1px solid var(--pos-border);
            border-radius: 10px;
            padding: 10px 16px 10px 42px;
            color: var(--pos-text);
            font-size: 14px;
            outline: none;
            transition: border-color 0.2s;
            box-sizing: border-box;
        }
        .pos-search-input:focus { border-color: var(--pos-accent); }
        .pos-search-input::placeholder { color: var(--pos-muted); }

        .pos-search-wrap {
            position: relative;
            margin-bottom: 14px;
        }
        .pos-search-wrap svg {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--pos-muted);
        }

        /* Category pills */
        .pos-cats { display: flex; flex-wrap: wrap; gap: 8px; }

        .pos-cat-btn {
            padding: 6px 14px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 600;
            border: 1px solid var(--pos-border);
            background: var(--pos-surface2);
            color: var(--pos-muted);
            cursor: pointer;
            transition: all 0.2s;
        }
        .pos-cat-btn:hover { border-color: var(--pos-accent); color: var(--pos-accent); }
        .pos-cat-btn.active {
            background: var(--pos-accent);
            border-color: var(--pos-accent);
            color: #000;
        }

        /* Product grid */
        .pos-products {
            background: var(--pos-surface);
            border: 1px solid var(--pos-border);
            border-radius: 16px;
            padding: 20px;
            flex: 1;
        }

        .pos-product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 14px;
        }

        .pos-product-card {
            background: var(--pos-surface2);
            border: 1px solid var(--pos-border);
            border-radius: 14px;
            padding: 14px;
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
            overflow: hidden;
        }
        .pos-product-card:hover {
            border-color: var(--pos-accent);
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(245,158,11,0.15);
        }
        .pos-product-card:active { transform: translateY(0); }

        .pos-product-img {
            aspect-ratio: 1;
            border-radius: 10px;
            background: var(--pos-bg);
            margin-bottom: 10px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .pos-product-img img { width: 100%; height: 100%; object-fit: cover; }

        .pos-product-name {
            font-size: 13px;
            font-weight: 700;
            color: var(--pos-text);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-bottom: 2px;
        }
        .pos-product-cat {
            font-size: 11px;
            color: var(--pos-muted);
            margin-bottom: 6px;
        }
        .pos-product-price {
            font-size: 14px;
            font-weight: 800;
            color: var(--pos-accent);
        }
        .pos-product-stock {
            font-size: 11px;
            margin-top: 4px;
        }
        .stock-low { color: #f97316; }
        .stock-ok { color: var(--pos-muted); }

        /* Empty state */
        .pos-empty {
            text-align: center;
            padding: 48px 0;
            color: var(--pos-muted);
        }
        .pos-empty svg { margin: 0 auto 12px; opacity: 0.3; display: block; }

        /* RIGHT PANEL - Cart */
        .pos-cart {
            background: var(--pos-surface);
            border: 1px solid var(--pos-border);
            border-radius: 16px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            position: sticky;
            top: 20px;
            max-height: calc(100vh - 100px);
        }

        .pos-cart-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }
        .pos-cart-title {
            font-size: 18px;
            font-weight: 800;
            color: var(--pos-text);
        }
        .pos-cart-badge {
            background: var(--pos-accent);
            color: #000;
            font-size: 12px;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 999px;
        }

        /* Customer input */
        .pos-customer-input {
            width: 100%;
            background: var(--pos-bg);
            border: 1px solid var(--pos-border);
            border-radius: 10px;
            padding: 10px 14px;
            color: var(--pos-text);
            font-size: 13px;
            outline: none;
            margin-bottom: 14px;
            box-sizing: border-box;
            transition: border-color 0.2s;
        }
        .pos-customer-input:focus { border-color: var(--pos-accent); }
        .pos-customer-input::placeholder { color: var(--pos-muted); }

        /* Cart items */
        .pos-cart-items {
            flex: 1;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-bottom: 16px;
            scrollbar-width: thin;
            scrollbar-color: var(--pos-border) transparent;
        }

        .pos-cart-item {
            background: var(--pos-surface2);
            border: 1px solid var(--pos-border);
            border-radius: 12px;
            padding: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .pos-item-info { flex: 1; min-width: 0; }
        .pos-item-name {
            font-size: 13px;
            font-weight: 700;
            color: var(--pos-text);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .pos-item-price { font-size: 11px; color: var(--pos-muted); }
        .pos-item-subtotal {
            font-size: 13px;
            font-weight: 700;
            color: var(--pos-accent);
            margin-top: 2px;
        }

        /* Qty controls */
        .pos-qty {
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .pos-qty-btn {
            width: 28px;
            height: 28px;
            border-radius: 8px;
            background: var(--pos-bg);
            border: 1px solid var(--pos-border);
            color: var(--pos-text);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.15s;
            font-size: 16px;
            line-height: 1;
        }
        .pos-qty-btn:hover { border-color: var(--pos-accent); color: var(--pos-accent); }
        .pos-qty-num {
            font-size: 14px;
            font-weight: 700;
            color: var(--pos-text);
            width: 24px;
            text-align: center;
        }

        .pos-remove-btn {
            background: none;
            border: none;
            color: var(--pos-muted);
            cursor: pointer;
            padding: 4px;
            border-radius: 6px;
            transition: color 0.15s;
            display: flex;
        }
        .pos-remove-btn:hover { color: var(--pos-danger); }

        /* Cart empty */
        .pos-cart-empty {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: var(--pos-muted);
            text-align: center;
            padding: 20px 0;
        }
        .pos-cart-empty svg { margin-bottom: 12px; opacity: 0.25; }
        .pos-cart-empty p { font-size: 14px; font-weight: 600; }
        .pos-cart-empty span { font-size: 12px; margin-top: 4px; display: block; }

        /* Divider */
        .pos-divider {
            height: 1px;
            background: var(--pos-border);
            margin: 14px 0;
        }

        /* Total */
        .pos-total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }
        .pos-total-label { font-size: 14px; color: var(--pos-muted); font-weight: 600; }
        .pos-total-amount {
            font-size: 24px;
            font-weight: 800;
            color: var(--pos-accent);
        }

        /* Buttons */
        .pos-btn-checkout {
            width: 100%;
            padding: 14px;
            background: var(--pos-accent);
            color: #000;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 800;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-bottom: 8px;
        }
        .pos-btn-checkout:hover { background: var(--pos-accent-dark); transform: translateY(-1px); }
        .pos-btn-checkout:disabled { opacity: 0.4; cursor: not-allowed; transform: none; }

        .pos-btn-clear {
            width: 100%;
            padding: 10px;
            background: transparent;
            color: var(--pos-muted);
            border: 1px solid var(--pos-border);
            border-radius: 12px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        .pos-btn-clear:hover { border-color: var(--pos-danger); color: var(--pos-danger); }
        .pos-btn-clear:disabled { opacity: 0.3; cursor: not-allowed; }
    </style>

    <div class="pos-wrap">

        {{-- LEFT: Products --}}
        <div class="pos-left">

            {{-- Search & Filter --}}
            <div class="pos-search-box">
                <div class="pos-search-wrap">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                    </svg>
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="searchProduct"
                        placeholder="Search products..."
                        class="pos-search-input"
                    >
                </div>

                <div class="pos-cats">
                    <button
                        wire:click="selectCategory(null)"
                        class="pos-cat-btn {{ !$this->selectedCategory ? 'active' : '' }}"
                    >All</button>
                    @foreach($this->categories as $category)
                        <button
                            wire:click="selectCategory({{ $category->id }})"
                            class="pos-cat-btn {{ $this->selectedCategory === $category->id ? 'active' : '' }}"
                        >{{ $category->name }} <span style="opacity:0.6">({{ $category->products_count }})</span></button>
                    @endforeach
                </div>
            </div>

            {{-- Product Grid --}}
            <div class="pos-products">
                <div class="pos-product-grid">
                    @forelse($this->products as $product)
                        <div
                            wire:click="addToCart({{ $product->id }})"
                            wire:key="product-{{ $product->id }}"
                            class="pos-product-card"
                        >
                            <div class="pos-product-img">
                                @if($product->image)
                                    <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}">
                                @else
                                    <svg width="36" height="36" fill="none" viewBox="0 0 24 24" stroke="#475569" stroke-width="1.5">
                                        <path d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3 21h18a.75.75 0 00.75-.75V6a.75.75 0 00-.75-.75H3A.75.75 0 002.25 6v14.25c0 .414.336.75.75.75z"/>
                                    </svg>
                                @endif
                            </div>
                            <div class="pos-product-name">{{ $product->name }}</div>
                            <div class="pos-product-cat">{{ $product->category?->name }}</div>
                            <div class="pos-product-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                            <div class="pos-product-stock {{ $product->stock <= 10 ? 'stock-low' : 'stock-ok' }}">
                                Stok: {{ $product->stock }}
                            </div>
                        </div>
                    @empty
                        <div class="pos-empty" style="grid-column: 1/-1">
                            <svg width="48" height="48" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/>
                            </svg>
                            <p>No products found</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- RIGHT: Cart --}}
        <div class="pos-cart">
            <div class="pos-cart-header">
                <span class="pos-cart-title">🛒 Cart</span>
                <span class="pos-cart-badge">{{ $this->cartItemsCount }} items</span>
            </div>

            <input
                type="text"
                wire:model="customerName"
                placeholder="👤 Customer name (optional)"
                class="pos-customer-input"
            >

            {{-- Items --}}
            @if($this->cart->isEmpty())
                <div class="pos-cart-empty">
                    <svg width="56" height="56" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                        <path d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/>
                    </svg>
                    <p>Cart is empty</p>
                    <span>Tap a product to add it</span>
                </div>
            @else
                <div class="pos-cart-items">
                    @foreach($this->cart as $index => $item)
                        <div wire:key="cart-{{ $index }}" class="pos-cart-item">
                            <div class="pos-item-info">
                                <div class="pos-item-name">{{ $item['name'] }}</div>
                                <div class="pos-item-price">Rp {{ number_format($item['price'], 0, ',', '.') }}</div>
                                <div class="pos-item-subtotal">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</div>
                            </div>
                            <div class="pos-qty">
                                <button wire:click="decrementQty({{ $index }})" class="pos-qty-btn">−</button>
                                <span class="pos-qty-num">{{ $item['quantity'] }}</span>
                                <button wire:click="incrementQty({{ $index }})" class="pos-qty-btn">+</button>
                            </div>
                            <button wire:click="removeFromCart({{ $index }})" class="pos-remove-btn">
                                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                </svg>
                            </button>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="pos-divider"></div>

            <div class="pos-total-row">
                <span class="pos-total-label">Total</span>
                <span class="pos-total-amount">Rp {{ number_format($this->cartTotal, 0, ',', '.') }}</span>
            </div>

            <button
                wire:click="checkout"
                wire:loading.attr="disabled"
                @if($this->cart->isEmpty()) disabled @endif
                class="pos-btn-checkout"
            >
                <wire:loading.remove wire:target="checkout">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path d="M4.5 12.75l6 6 9-13.5"/>
                    </svg>
                    Complete Order
                </wire:loading.remove>
                <wire:loading wire:target="checkout">
                    Processing...
                </wire:loading>
            </button>

            <button
                wire:click="clearCart"
                @if($this->cart->isEmpty()) disabled @endif
                class="pos-btn-clear"
            >
                Clear Cart
            </button>
        </div>
    </div>
</x-filament::page>
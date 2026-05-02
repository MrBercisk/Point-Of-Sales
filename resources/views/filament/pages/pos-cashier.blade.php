<x-filament-panels::page>

<style>
/* ── Reset & Base ─────────────────────────────────────────── */
.pos-wrap { display:flex; gap:16px; height:calc(100vh - 9rem); }

/* ── Search ───────────────────────────────────────────────── */
.pos-search-wrap { position:relative; margin-bottom:8px; }
.pos-search-icon { position:absolute; left:12px; top:50%; transform:translateY(-50%); width:16px; height:16px; color:#9ca3af; }
.pos-search { width:100%; padding:8px 16px 8px 38px; border-radius:12px; border:1px solid #e5e7eb; background:#fff; font-size:14px; outline:none; box-sizing:border-box; }
.pos-search:focus { border-color:#6366f1; box-shadow:0 0 0 2px #e0e7ff; }

/* ── Category pills ───────────────────────────────────────── */
.pos-cats { display:flex; gap:8px; overflow-x:auto; padding-bottom:4px; margin-bottom:8px; scrollbar-width:none; }
.pos-cats::-webkit-scrollbar { display:none; }
.cat-btn { padding:6px 16px; border-radius:999px; font-size:13px; font-weight:500; white-space:nowrap; border:1px solid #e5e7eb; background:#fff; color:#4b5563; cursor:pointer; transition:all .15s; }
.cat-btn:hover { border-color:#6366f1; color:#6366f1; }
.cat-btn.active { background:#6366f1; color:#fff; border-color:#6366f1; }

/* ── Product grid ─────────────────────────────────────────── */
.pos-left { flex:1; display:flex; flex-direction:column; overflow:hidden; }
.pos-grid { flex:1; overflow-y:auto; display:grid; grid-template-columns:repeat(auto-fill,minmax(150px,1fr)); gap:12px; align-content:start; padding-right:4px; }
.prod-card { background:#fff; border:1px solid #f3f4f6; border-radius:12px; padding:12px; text-align:left; cursor:pointer; transition:all .15s; width:100%; }
.prod-card:hover { border-color:#6366f1; box-shadow:0 4px 12px rgba(99,102,241,.15); }
.prod-img { width:100%; height:96px; object-fit:cover; border-radius:8px; margin-bottom:8px; }
.prod-img-placeholder { width:100%; height:96px; background:#f3f4f6; border-radius:8px; margin-bottom:8px; display:flex; align-items:center; justify-content:center; }
.prod-name { font-size:13px; font-weight:600; color:#111827; margin-bottom:4px; overflow:hidden; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; }
.prod-price { font-size:13px; font-weight:700; color:#6366f1; }
.prod-stock { font-size:11px; color:#9ca3af; margin-top:2px; }

/* ── Cart panel ───────────────────────────────────────────── */
.pos-cart { width:320px; display:flex; flex-direction:column; background:#fff; border:1px solid #e5e7eb; border-radius:16px; overflow:hidden; flex-shrink:0; }
.cart-header { padding:12px 16px; border-bottom:1px solid #f3f4f6; display:flex; align-items:center; justify-content:space-between; }
.cart-title { display:flex; align-items:center; gap:8px; font-weight:700; font-size:15px; }
.cart-badge { background:#6366f1; color:#fff; font-size:11px; font-weight:700; padding:2px 8px; border-radius:999px; }
.cart-clear { font-size:12px; color:#ef4444; background:none; border:none; cursor:pointer; }
.cart-clear:hover { color:#b91c1c; }

/* ── Customer fields ──────────────────────────────────────── */
.cart-customer { padding:12px 16px; border-bottom:1px solid #f3f4f6; display:flex; flex-direction:column; gap:8px; }
.cust-input { width:100%; padding:7px 12px; font-size:13px; border-radius:8px; border:1px solid #e5e7eb; background:#f9fafb; outline:none; box-sizing:border-box; }
.cust-input:focus { border-color:#6366f1; box-shadow:0 0 0 2px #e0e7ff; }
.cust-phone-wrap { position:relative; }
.cust-prefix { position:absolute; left:10px; top:50%; transform:translateY(-50%); font-size:12px; color:#9ca3af; font-family:monospace; }
.cust-phone { padding-left:38px !important; }
.cust-wa-icon { position:absolute; right:10px; top:50%; transform:translateY(-50%); width:16px; height:16px; color:#22c55e; }
.wa-hint { font-size:11px; color:#16a34a; display:flex; align-items:center; gap:4px; }

/* ── Cart items ───────────────────────────────────────────── */
.cart-items { flex:1; overflow-y:auto; padding:8px 16px; }
.cart-item { display:flex; gap:8px; padding:10px 0; border-bottom:1px solid #f9fafb; }
.ci-info { flex:1; min-width:0; }
.ci-name { font-size:13px; font-weight:500; color:#111827; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.ci-unit { font-size:11px; color:#9ca3af; margin-top:1px; }
.ci-controls { display:flex; flex-direction:column; align-items:flex-end; gap:4px; }
.ci-qty-row { display:flex; align-items:center; gap:4px; }
.qty-btn { width:24px; height:24px; border-radius:50%; background:#f3f4f6; border:none; font-size:15px; line-height:1; cursor:pointer; display:flex; align-items:center; justify-content:center; color:#4b5563; transition:all .15s; }
.qty-btn:hover { background:#e0e7ff; color:#6366f1; }
.qty-num { width:24px; text-align:center; font-size:13px; font-weight:700; }
.ci-subtotal { font-size:12px; font-weight:600; color:#6366f1; }

/* ── Cart empty ───────────────────────────────────────────── */
.cart-empty { text-align:center; padding:40px 0; color:#d1d5db; }
.cart-empty p { font-size:13px; }

/* ── Checkout bar ─────────────────────────────────────────── */
.cart-footer { padding:16px; border-top:1px solid #e5e7eb; }
.cart-total-row { display:flex; justify-content:space-between; font-weight:700; font-size:15px; margin-bottom:12px; }
.cart-total-amt { color:#6366f1; }
.btn-checkout { width:100%; padding:12px; border-radius:12px; font-weight:700; font-size:15px; color:#fff; background:#6366f1; border:none; cursor:pointer; transition:all .15s; display:flex; align-items:center; justify-content:center; gap:8px; }
.btn-checkout:hover:not(:disabled) { background:#4f46e5; }
.btn-checkout:active:not(:disabled) { transform:scale(.98); }
.btn-checkout:disabled { background:#d1d5db; cursor:not-allowed; }

/* ── Spinner ──────────────────────────────────────────────── */
@keyframes pos-spin { to { transform:rotate(360deg); } }
.pos-spin { animation:pos-spin .8s linear infinite; display:inline-block; }

/* ── MODAL ────────────────────────────────────────────────── */
.modal-overlay { position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,.55); display:flex; align-items:center; justify-content:center; padding:16px; }
.modal-box { background:#fff; border-radius:20px; width:100%; max-width:420px; overflow:hidden; box-shadow:0 20px 60px rgba(0,0,0,.25); }
.modal-header { background:#22c55e; padding:16px 20px; display:flex; align-items:center; justify-content:space-between; }
.modal-header-left { display:flex; align-items:center; gap:8px; color:#fff; }
.modal-header-left span { font-weight:700; font-size:16px; }
.modal-close { background:none; border:none; color:rgba(255,255,255,.8); cursor:pointer; padding:4px; border-radius:6px; }
.modal-close:hover { color:#fff; }
.modal-body { padding:20px; max-height:55vh; overflow-y:auto; }
.receipt-store { text-align:center; margin-bottom:12px; }
.receipt-store-name { font-weight:700; font-size:15px; color:#111827; }
.receipt-store-addr { font-size:11px; color:#6b7280; margin-top:2px; }
.receipt-divider { border:none; border-top:1px dashed #d1d5db; margin:10px 0; }
.receipt-meta { font-size:13px; }
.receipt-meta-row { display:flex; justify-content:space-between; padding:2px 0; }
.receipt-meta-label { color:#6b7280; }
.receipt-meta-value { font-weight:500; font-family:monospace; }
.receipt-items-table { width:100%; font-size:13px; border-collapse:collapse; margin-bottom:8px; }
.receipt-items-table td { padding:4px 0; vertical-align:top; }
.receipt-item-name { font-weight:500; color:#111827; }
.receipt-item-unit { font-size:11px; color:#9ca3af; }
.receipt-item-price { text-align:right; font-weight:500; white-space:nowrap; }
.receipt-total-row { display:flex; justify-content:space-between; font-weight:700; font-size:16px; margin-top:4px; }
.receipt-total-amt { color:#16a34a; }
.receipt-thanks { text-align:center; font-size:11px; color:#9ca3af; margin-top:10px; }

/* ── Modal action buttons ─────────────────────────────────── */
.modal-actions { display:grid; grid-template-columns:1fr 1fr 1fr; gap:8px; padding:16px 20px; background:#f9fafb; border-top:1px solid #f3f4f6; }
.modal-btn { display:flex; flex-direction:column; align-items:center; justify-content:center; gap:4px; padding:10px 8px; border-radius:12px; border:none; cursor:pointer; transition:all .15s; text-decoration:none; font-size:11px; font-weight:600; line-height:1.3; text-align:center; }
.modal-btn svg { width:20px; height:20px; flex-shrink:0; }
.modal-btn-pdf { background:#eff6ff; color:#1d4ed8; }
.modal-btn-pdf:hover { background:#dbeafe; }
.modal-btn-print { background:#f5f3ff; color:#6d28d9; }
.modal-btn-print:hover:not(:disabled) { background:#ede9fe; }
.modal-btn-print:disabled { background:#f3f4f6; color:#9ca3af; cursor:not-allowed; }
.modal-btn-bt { background:#fff7ed; color:#c2410c; }
.modal-btn-bt:hover { background:#fed7aa; }
.modal-btn-bt-on { background:#f0fdf4; color:#15803d; }
.modal-btn-bt-on:hover { background:#dcfce7; }

/* ── BT Status bar ────────────────────────────────────────── */
.bt-status { padding:8px 20px; text-align:center; font-size:12px; }
.bt-ok { background:#f0fdf4; color:#15803d; }
.bt-err { background:#fff7ed; color:#c2410c; }

/* ── Dark mode ────────────────────────────────────────────── */
.dark .pos-search { background:#1f2937; border-color:#374151; color:#f9fafb; }
.dark .cat-btn { background:#1f2937; border-color:#374151; color:#d1d5db; }
.dark .prod-card { background:#1f2937; border-color:#374151; }
.dark .prod-name { color:#f9fafb; }
.dark .prod-img-placeholder { background:#374151; }
.dark .pos-cart { background:#111827; border-color:#374151; }
.dark .cart-header,.dark .cart-customer,.dark .cart-footer { border-color:#374151; }
.dark .cust-input { background:#1f2937; border-color:#374151; color:#f9fafb; }
.dark .cart-item { border-color:#1f2937; }
.dark .ci-name,.dark .cart-total-row { color:#f9fafb; }
.dark .qty-btn { background:#374151; color:#d1d5db; }
.dark .modal-box { background:#1f2937; }
.dark .receipt-store-name,.dark .receipt-item-name,.dark .receipt-total-row { color:#f9fafb; }
.dark .modal-actions { background:#111827; border-color:#374151; }
</style>

{{-- ══════════════════════════════════════════════════════════
     MODAL STRUK
═══════════════════════════════════════════════════════════ --}}
@if($showReceiptModal && $lastOrder)
<div
    class="modal-overlay"
    x-data="receiptModal(@js($lastOrder))"
    x-init="initBluetooth()"
>
    <div class="modal-box">

        {{-- Header --}}
        <div class="modal-header">
            <div class="modal-header-left">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:22px;height:22px">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Transaksi Berhasil!</span>
            </div>
            <button class="modal-close" wire:click="closeReceiptModal">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:20px;height:20px">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Preview struk --}}
        <div class="modal-body">
            <div class="receipt-store">
                <div class="receipt-store-name">{{ config('pos.store_name', 'Toko Saya') }}</div>
                <div class="receipt-store-addr">{{ config('pos.store_address', '') }}</div>
            </div>

            <hr class="receipt-divider">

            <div class="receipt-meta">
                <div class="receipt-meta-row">
                    <span class="receipt-meta-label">Invoice</span>
                    <span class="receipt-meta-value">{{ $lastOrder['invoice_number'] }}</span>
                </div>
                <div class="receipt-meta-row">
                    <span class="receipt-meta-label">Waktu</span>
                    <span class="receipt-meta-value">{{ $lastOrder['created_at'] }}</span>
                </div>
                @if($lastOrder['customer_name'])
                <div class="receipt-meta-row">
                    <span class="receipt-meta-label">Pelanggan</span>
                    <span class="receipt-meta-value">{{ $lastOrder['customer_name'] }}</span>
                </div>
                @endif
                @if($lastOrder['customer_phone'])
                <div class="receipt-meta-row">
                    <span class="receipt-meta-label">No. HP</span>
                    <span class="receipt-meta-value">{{ $lastOrder['customer_phone'] }}</span>
                </div>
                @endif
            </div>

            <hr class="receipt-divider">

            <table class="receipt-items-table">
                <tbody>
                    @foreach($lastOrder['items'] as $item)
                    <tr>
                        <td>
                            <div class="receipt-item-name">{{ $item['name'] }}</div>
                            <div class="receipt-item-unit">Rp {{ number_format($item['price'], 0, ',', '.') }} × {{ $item['quantity'] }}</div>
                        </td>
                        <td class="receipt-item-price">
                            Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <hr class="receipt-divider">

            <div class="receipt-total-row">
                <span>TOTAL</span>
                <span class="receipt-total-amt">Rp {{ number_format($lastOrder['total_amount'], 0, ',', '.') }}</span>
            </div>

            <div class="receipt-thanks">Terima kasih telah berbelanja! 🙏</div>
        </div>

        {{-- Tombol aksi --}}
        <div class="modal-actions">

            {{-- Download PDF --}}
            <a href="{{ $lastOrder['pdf_url'] }}" target="_blank" class="modal-btn modal-btn-pdf">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/>
                </svg>
                Download PDF
            </a>

            {{-- Cetak Bluetooth --}}
            <button
                class="modal-btn modal-btn-print"
                @click="printBluetooth()"
                :disabled="!btConnected"
            >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5zm-3 0h.008v.008H15V10.5z"/>
                </svg>
                Cetak Struk
            </button>

            {{-- Konek / Putus Bluetooth --}}
            <button
                class="modal-btn"
                :class="btConnected ? 'modal-btn-bt-on' : 'modal-btn-bt'"
                @click="btConnected ? disconnectBT() : connectBluetooth()"
            >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.288 15.038a5.25 5.25 0 017.424 0M5.106 11.856c3.807-3.808 9.98-3.808 13.788 0M1.924 8.674c5.565-5.565 14.587-5.565 20.152 0M12.53 18.22l-.53.53-.53-.53a.75.75 0 011.06 0z"/>
                </svg>
                <span x-text="btConnected ? 'Putuskan' : 'Konek Printer'">Konek Printer</span>
            </button>
        </div>

        {{-- Status Bluetooth --}}
        <div
            x-show="btStatus !== ''"
            class="bt-status"
            :class="btConnected ? 'bt-ok' : 'bt-err'"
            x-text="btStatus"
            style="display:none"
        ></div>

    </div>
</div>
@endif

{{-- ══════════════════════════════════════════════════════════
     MAIN POS
═══════════════════════════════════════════════════════════ --}}
<div class="pos-wrap">

    {{-- LEFT: Produk --}}
    <div class="pos-left">

        <div class="pos-search-wrap">
            <svg class="pos-search-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
            </svg>
            <input
                wire:model.live.debounce.300ms="searchProduct"
                type="text"
                placeholder="Cari produk..."
                class="pos-search"
            />
        </div>

        <div class="pos-cats">
            <button wire:click="selectCategory(null)" class="cat-btn {{ is_null($selectedCategory) ? 'active' : '' }}">
                Semua
            </button>
            @foreach($this->categories as $cat)
            <button wire:click="selectCategory({{ $cat->id }})" class="cat-btn {{ $selectedCategory === $cat->id ? 'active' : '' }}">
                {{ $cat->name }} ({{ $cat->products_count }})
            </button>
            @endforeach
        </div>

        <div class="pos-grid">
            @forelse($this->products as $product)
            <button class="prod-card" wire:click="addToCart({{ $product->id }})">
                @if($product->image)
                    <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="prod-img"/>
                @else
                    <div class="prod-img-placeholder">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#d1d5db" style="width:32px;height:32px">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/>
                        </svg>
                    </div>
                @endif
                <div class="prod-name">{{ $product->name }}</div>
                <div class="prod-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                <div class="prod-stock">Stok: {{ $product->stock }}</div>
            </button>
            @empty
            <div style="grid-column:1/-1;text-align:center;padding:48px 0;color:#9ca3af;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:48px;height:48px;margin:0 auto 8px;display:block">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.182 16.318A4.486 4.486 0 0012.016 15a4.486 4.486 0 00-3.198 1.318M21 12a9 9 0 11-18 0 9 9 0 0118 0zM9.75 9.75c0 .414-.168.75-.375.75S9 10.164 9 9.75 9.168 9 9.375 9s.375.336.375.75zm-.375 0h.008v.015h-.008V9.75zm5.625 0c0 .414-.168.75-.375.75s-.375-.336-.375-.75.168-.75.375-.75.375.336.375.75zm-.375 0h.008v.015h-.008V9.75z"/>
                </svg>
                <p style="font-size:14px">Produk tidak ditemukan</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- RIGHT: Keranjang --}}
    <div class="pos-cart">

        <div class="cart-header">
            <div class="cart-title">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#6366f1" style="width:20px;height:20px">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/>
                </svg>
                Keranjang
                @if($this->cartItemsCount() > 0)
                <span class="cart-badge">{{ $this->cartItemsCount() }}</span>
                @endif
            </div>
            @if($cart->isNotEmpty())
            <button class="cart-clear" wire:click="clearCart">Hapus semua</button>
            @endif
        </div>

        <div class="cart-customer">
            <input wire:model.live="customerName" type="text" placeholder="Nama pelanggan (opsional)" class="cust-input"/>
            <div class="cust-phone-wrap">
                <span class="cust-prefix">+62</span>
                <input wire:model.live="customerPhone" type="text" placeholder="08xxxxxx (WhatsApp)" class="cust-input cust-phone"/>
                @if($customerPhone)
                <svg class="cust-wa-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 9.75a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375m-13.5 3.01c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.184-4.183a1.14 1.14 0 01.778-.332 48.294 48.294 0 005.83-.498c1.585-.233 2.708-1.626 2.708-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z"/>
                </svg>
                @endif
            </div>
            @if($customerPhone)
            <div class="wa-hint">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:12px;height:12px">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Struk akan dikirim ke WhatsApp
            </div>
            @endif
        </div>

        <div class="cart-items">
            @forelse($cart as $index => $item)
            <div class="cart-item">
                <div class="ci-info">
                    <div class="ci-name">{{ $item['name'] }}</div>
                    <div class="ci-unit">Rp {{ number_format($item['price'], 0, ',', '.') }}</div>
                </div>
                <div class="ci-controls">
                    <div class="ci-qty-row">
                        <button class="qty-btn" wire:click="decrementQty({{ $index }})">−</button>
                        <span class="qty-num">{{ $item['quantity'] }}</span>
                        <button class="qty-btn" wire:click="incrementQty({{ $index }})">+</button>
                    </div>
                    <span class="ci-subtotal">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                </div>
            </div>
            @empty
            <div class="cart-empty">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#e5e7eb" style="width:48px;height:48px;margin:0 auto 8px;display:block">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/>
                </svg>
                <p>Keranjang kosong</p>
            </div>
            @endforelse
        </div>

        <div class="cart-footer">
            <div class="cart-total-row">
                <span>Total</span>
                <span class="cart-total-amt">Rp {{ number_format($this->cartTotal(), 0, ',', '.') }}</span>
            </div>
            <button
                class="btn-checkout"
                wire:click="checkout"
                wire:loading.attr="disabled"
                wire:target="checkout"
                {{ $cart->isEmpty() ? 'disabled' : '' }}
            >
                <span wire:loading.remove wire:target="checkout" style="display:flex;align-items:center;gap:6px">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:20px;height:20px">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75"/>
                    </svg>
                    Bayar
                </span>
                <span wire:loading wire:target="checkout" style="display:flex;align-items:center;gap:6px">
                    <svg class="pos-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" style="width:20px;height:20px">
                        <circle cx="12" cy="12" r="10" stroke="rgba(255,255,255,.3)" stroke-width="4"/>
                        <path fill="white" d="M4 12a8 8 0 018-8v8z"/>
                    </svg>
                    Memproses...
                </span>
            </button>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════
     ALPINE.JS – Bluetooth ESC/POS
═══════════════════════════════════════════════════════════ --}}
@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('receiptModal', (order) => ({
        order,
        btConnected: false,
        btDevice: null,
        btCharacteristic: null,
        btStatus: '',

        async initBluetooth() {
            if (!navigator.bluetooth) {
                this.btStatus = '⚠️ Gunakan Chrome/Edge untuk fitur Bluetooth';
            }
        },

        async connectBluetooth() {
            try {
                this.btStatus = 'Mencari printer Bluetooth...';
                const device = await navigator.bluetooth.requestDevice({
                    acceptAllDevices: true,
                    optionalServices: [
                        '000018f0-0000-1000-8000-00805f9b34fb',
                        '0000ff00-0000-1000-8000-00805f9b34fb',
                        'e7810a71-73ae-499d-8c15-faa9aef0c3f2',
                    ],
                });
                this.btStatus = 'Menghubungkan ke ' + device.name + '...';
                const server = await device.gatt.connect();

                let service;
                try { service = await server.getPrimaryService('000018f0-0000-1000-8000-00805f9b34fb'); }
                catch { service = await server.getPrimaryService('0000ff00-0000-1000-8000-00805f9b34fb'); }

                try { this.btCharacteristic = await service.getCharacteristic('00002af1-0000-1000-8000-00805f9b34fb'); }
                catch { this.btCharacteristic = await service.getCharacteristic('0000ff01-0000-1000-8000-00805f9b34fb'); }

                this.btDevice = device;
                this.btConnected = true;
                this.btStatus = '✅ Terhubung: ' + device.name;

                device.addEventListener('gattserverdisconnected', () => {
                    this.btConnected = false;
                    this.btCharacteristic = null;
                    this.btStatus = '⚠️ Printer terputus';
                });
            } catch (err) {
                this.btConnected = false;
                this.btStatus = '❌ ' + err.message;
            }
        },

        disconnectBT() {
            if (this.btDevice?.gatt?.connected) this.btDevice.gatt.disconnect();
            this.btConnected = false;
            this.btCharacteristic = null;
            this.btStatus = 'Printer diputuskan';
        },

        async printBluetooth() {
            if (!this.btCharacteristic) {
                this.btStatus = '❌ Hubungkan printer terlebih dahulu';
                return;
            }
            try {
                this.btStatus = 'Mencetak struk...';
                const data = this.buildEscPos();
                const chunk = 512;
                for (let i = 0; i < data.length; i += chunk) {
                    await this.btCharacteristic.writeValue(data.slice(i, i + chunk));
                    await new Promise(r => setTimeout(r, 50));
                }
                this.btStatus = '✅ Struk berhasil dicetak!';
            } catch (err) {
                this.btStatus = '❌ Gagal cetak: ' + err.message;
            }
        },

        buildEscPos() {
            const ESC = 0x1B, GS = 0x1D, b = [];
            const push = (...x) => x.forEach(v => b.push(v));
            const text = s => { for (const c of s) b.push(c.charCodeAt(0) > 127 ? 63 : c.charCodeAt(0)); };
            const line = (s = '') => { text(s); push(10); };
            const div  = () => line('--------------------------------');
            const pad  = (l, r, w = 32) => l + ' '.repeat(Math.max(w - l.length - r.length, 1)) + r;

            push(ESC, 0x40);
            push(ESC, 0x61, 1); push(ESC, 0x45, 1); push(GS, 0x21, 0x11);
            line('{{ config("pos.store_name", "Toko Saya") }}');
            push(GS, 0x21, 0); push(ESC, 0x45, 0);
            line('{{ config("pos.store_address", "") }}');
            line('Telp: {{ config("pos.store_phone", "") }}');
            push(ESC, 0x61, 0);
            div();

            line(pad('Invoice:', this.order.invoice_number));
            line(pad('Tanggal:', this.order.created_at));
            if (this.order.customer_name) line(pad('Pelanggan:', this.order.customer_name));
            if (this.order.customer_phone) line(pad('No HP:', this.order.customer_phone));
            div();

            for (const item of this.order.items) {
                line(item.name.substring(0, 32));
                line(pad(item.quantity + 'x', 'Rp ' + (item.price * item.quantity).toLocaleString('id-ID')));
            }
            div();

            push(ESC, 0x45, 1); push(GS, 0x21, 1);
            line(pad('TOTAL:', 'Rp ' + Number(this.order.total_amount).toLocaleString('id-ID')));
            push(GS, 0x21, 0); push(ESC, 0x45, 0);
            div();

            push(ESC, 0x61, 1);
            line('Terima kasih!');
            line('Barang yang sudah dibeli');
            line('tidak dapat dikembalikan.');
            push(10, 10, 10, 10);
            push(GS, 0x56, 0x41, 0x10);

            return new Uint8Array(b);
        },
    }));
});
</script>
@endpush

</x-filament-panels::page>
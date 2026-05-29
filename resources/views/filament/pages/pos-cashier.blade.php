<div>

{{-- TOP BAR --}}
<div class="pos-topbar">
    <div class="tb-left">
        <div class="tb-brand"><div class="tb-logo">🛒</div>POS Cashier</div>
        <div class="tb-divider"></div>
        <div class="tb-register">
            <span class="tb-dot"></span>
            {{ auth()->user()?->name ?? 'Kasir 1' }} &middot; Online
        </div>
    </div>
    <div class="tb-right">
        <a href="{{ filament()->getUrl() }}" class="tb-btn" title="Kembali ke Dashboard">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/></svg>
            Dashboard
        </a>
        <div class="tb-user">
            <div class="tb-avatar">{{ strtoupper(substr(auth()->user()?->name ?? 'K', 0, 1)) }}</div>
            {{ auth()->user()?->name ?? 'Kasir' }}
        </div>
    </div>
</div>

{{-- MODAL STRUK --}}
@if($showReceiptModal && $lastOrder)
@php
    $o = array_merge([
        'paper_size'             => '80mm',
        'layout'                 => 'standard',
        'store_name'             => 'Kantin Sekolah',
        'store_address'          => '',
        'store_phone'            => '',
        'store_footer'           => 'Terima kasih! Selamat belajar.',
        'cashier_name'           => 'Kasir',
        'show_store_name'        => true,
        'show_address'           => true,
        'show_phone'             => true,
        'show_invoice_number'    => true,
        'show_date'              => true,
        'show_student'           => true,
        'show_payment_method'    => true,
        'show_cashier'           => false,
        'show_item_price'        => true,
        'show_subtotal_per_item' => true,
        'show_change'            => true,
        'show_balance_after'     => true,
        'show_footer'            => true,
        'show_barcode'           => false,
        'pdf_url'                => null,
        'balance_after'          => null,
    ], $lastOrder);
@endphp
<div class="modal-overlay" x-data="receiptModal(@js($lastOrder))" x-init="initBluetooth()">
    <div class="modal-box {{ ($o['paper_size'] ?? '80mm') === '58mm' ? 'w-58' : 'w-80' }}">
        <div class="modal-head">
            <div class="mh-left">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Transaksi Berhasil!</span>
            </div>
            <button class="mh-close" wire:click="closeReceiptModal">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="modal-body">
            @if($o['show_store_name'])<div class="rp-store-name">{{ $o['store_name'] }}</div>@endif
            @if($o['show_address'] && $o['store_address'])<div class="rp-store-info">{{ $o['store_address'] }}</div>@endif
            @if($o['show_phone'] && $o['store_phone'])<div class="rp-store-info">Telp: {{ $o['store_phone'] }}</div>@endif
            <hr class="rp-divider">
            @if($o['show_invoice_number'])<div class="rp-row"><span class="rp-row-label">Invoice</span><span>{{ $o['invoice_number'] }}</span></div>@endif
            @if($o['show_date'])<div class="rp-row"><span class="rp-row-label">Tanggal</span><span>{{ $o['created_at'] }}</span></div>@endif
            @if($o['show_student'] && $o['student_name'])<div class="rp-row"><span class="rp-row-label">Siswa</span><span>{{ $o['student_name'] }} (Kls {{ $o['student_class'] }})</span></div>@endif
            @if($o['show_payment_method'])<div class="rp-row"><span class="rp-row-label">Bayar</span><span>{{ $o['payment_method'] === 'wallet' ? 'Dompet Siswa' : 'Tunai' }}</span></div>@endif
            @if($o['show_cashier'])<div class="rp-row"><span class="rp-row-label">Kasir</span><span>{{ $o['cashier_name'] }}</span></div>@endif
            <hr class="rp-divider">
            @if($o['layout'] === 'detailed')<div class="rp-det-head"><span>Item</span><span style="text-align:center">Qty</span><span style="text-align:right">Total</span></div>@endif
            @foreach($o['items'] as $item)
                @if($o['layout'] === 'standard')
                <div class="rp-item-std">
                    <div class="rp-item-name">{{ $item['name'] }}</div>
                    @if($o['show_item_price'])<div class="rp-item-detail">{{ $item['quantity'] }}× Rp {{ number_format($item['price'], 0, ',', '.') }}</div>@endif
                    @if($o['show_subtotal_per_item'])<div class="rp-item-sub">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</div>@endif
                </div>
                @elseif($o['layout'] === 'compact')
                <div class="rp-compact"><span>{{ $item['name'] }} ({{ $item['quantity'] }}×)</span><span>Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span></div>
                @else
                <div class="rp-detailed">
                    <span>{{ $item['name'] }}</span>
                    <span style="text-align:center">{{ $item['quantity'] }}</span>
                    <span style="text-align:right">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                </div>
                @endif
            @endforeach
            <hr class="rp-divider">
            <div class="rp-total"><span>TOTAL</span><span class="rp-total-amt">Rp {{ number_format($o['total_amount'], 0, ',', '.') }}</span></div>
            @if($o['show_balance_after'] && $o['payment_method'] === 'wallet' && isset($o['balance_after']))<div class="rp-summary rp-saldo"><span>Sisa Saldo</span><span>Rp {{ number_format($o['balance_after'], 0, ',', '.') }}</span></div>@endif
            @if($o['show_change'] && $o['payment_method'] === 'cash')
            <div class="rp-summary rp-bayar"><span>Bayar</span><span>Rp {{ number_format($o['cash_amount'], 0, ',', '.') }}</span></div>
            <div class="rp-summary rp-kembalian"><span>Kembalian</span><span>Rp {{ number_format($o['change_amount'], 0, ',', '.') }}</span></div>
            @endif
            @if($o['show_footer'] && $o['store_footer'])<hr class="rp-divider"><div class="rp-footer">@foreach(explode("\n", $o['store_footer']) as $line)@if(trim($line))<p>{{ trim($line) }}</p>@endif@endforeach</div>@endif
            @if($o['show_barcode'])<div class="rp-barcode"><div style="font-family:monospace;font-size:16px;letter-spacing:-1px">||| || ||| | || ||| ||</div><div>{{ $o['invoice_number'] }}</div></div>@endif
        </div>
        <div class="modal-actions">
            @if($o['pdf_url'])
            <a href="{{ $o['pdf_url'] }}" target="_blank" class="modal-act-btn btn-pdf">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                Download PDF
            </a>
            @else
            <div class="modal-act-btn" style="background:#f3f4f6;color:#9ca3af;cursor:default">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                Tidak Ada PDF
            </div>
            @endif
            <button class="modal-act-btn btn-print" @click="printBluetooth()" :disabled="!btConnected">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5zm-3 0h.008v.008H15V10.5z"/></svg>
                Cetak Struk
            </button>
            <button class="modal-act-btn" :class="btConnected ? 'btn-bt-on' : 'btn-bt'" @click="btConnected ? disconnectBT() : connectBluetooth()">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.288 15.038a5.25 5.25 0 017.424 0M5.106 11.856c3.807-3.808 9.98-3.808 13.788 0M1.924 8.674c5.565-5.565 14.587-5.565 20.152 0M12.53 18.22l-.53.53-.53-.53a.75.75 0 011.06 0z"/></svg>
                <span x-text="btConnected ? 'Putuskan' : 'Konek Printer'"></span>
            </button>
        </div>
        <div x-show="btStatus !== ''" class="bt-status" :class="btConnected ? 'bt-ok' : 'bt-err'" x-text="btStatus" style="display:none"></div>
    </div>
</div>
@endif

{{-- MODIFIER MODAL  --}}
@if($showModifierModal && $pendingProductId)
@php $pendingProduct = \App\Models\Product::find($pendingProductId); @endphp
<div class="modal-overlay" style="z-index:9999">
    <div class="modal-box" style="max-width:460px;width:100%">
        <div class="modal-head">
            <div class="mh-left">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:18px;height:18px"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Pilihan Tambahan</span>
            </div>
            <button class="mh-close" wire:click="closeModifierModal">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="modal-body" style="padding:16px 20px">
            <div style="display:flex;align-items:center;gap:12px;padding:12px;background:#f9fafb;border-radius:10px;margin-bottom:16px;border:1px solid #f3f4f6">
                @if($pendingProduct?->image)
                    <img src="{{ Storage::url($pendingProduct->image) }}" style="width:44px;height:44px;border-radius:8px;object-fit:cover">
                @else
                    <div style="width:44px;height:44px;border-radius:8px;background:#e5e7eb;display:flex;align-items:center;justify-content:center;font-size:20px">🍽</div>
                @endif
                <div>
                    <div style="font-size:14px;font-weight:600;color:#111827">{{ $pendingProduct?->name }}</div>
                    <div style="font-size:12px;color:#6b7280">Rp {{ number_format($pendingProduct?->price ?? 0, 0, ',', '.') }}</div>
                </div>
            </div>
            @foreach($pendingModifierGroups as $group)
            <div style="margin-bottom:16px">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px">
                    <div style="font-size:13px;font-weight:600;color:#374151">
                        {{ $group['name'] }}
                        @if($group['is_required'])<span style="font-size:10px;background:#fee2e2;color:#dc2626;padding:2px 7px;border-radius:99px;margin-left:6px">Wajib</span>@else<span style="font-size:10px;background:#f3f4f6;color:#9ca3af;padding:2px 7px;border-radius:99px;margin-left:6px">Opsional</span>@endif
                    </div>
                    <div style="font-size:11px;color:#9ca3af">Maks. {{ $group['max_select'] }} pilihan</div>
                </div>
                <div style="display:flex;flex-direction:column;gap:6px">
                    @foreach($group['modifiers'] as $modifier)
                    @php
                        $isSelected   = in_array($modifier['id'], $selectedModifiers);
                        $isOutOfStock = isset($modifier['product_id']) && $modifier['product_id']
                            ? (\App\Models\Product::find($modifier['product_id'])?->stock ?? 0) <= 0 : false;
                    @endphp
                    <button wire:click="toggleModifier({{ $modifier['id'] }}, {{ $group['id'] }}, {{ $group['max_select'] }})" @disabled($isOutOfStock)
                        style="display:flex;align-items:center;justify-content:space-between;padding:10px 14px;border-radius:10px;text-align:left;border:1.5px solid {{ $isSelected ? '#16a34a' : '#e5e7eb' }};background:{{ $isSelected ? '#f0fdf4' : '#fff' }};cursor:{{ $isOutOfStock ? 'not-allowed' : 'pointer' }};opacity:{{ $isOutOfStock ? '0.5' : '1' }};transition:all .15s;width:100%">
                        <div style="display:flex;align-items:center;gap:10px">
                            <div style="width:20px;height:20px;border-radius:{{ $group['max_select'] === 1 ? '50%' : '5px' }};border:2px solid {{ $isSelected ? '#16a34a' : '#d1d5db' }};background:{{ $isSelected ? '#16a34a' : 'transparent' }};display:flex;align-items:center;justify-content:center;flex-shrink:0">
                                @if($isSelected)<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="white" style="width:12px;height:12px"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>@endif
                            </div>
                            <div>
                                <div style="font-size:13px;font-weight:500;color:#111827">{{ $modifier['name'] }}</div>
                                @if($isOutOfStock)<div style="font-size:11px;color:#ef4444">Stok habis</div>@endif
                            </div>
                        </div>
                        <div style="font-size:13px;font-weight:600;color:{{ $modifier['price'] > 0 ? '#16a34a' : '#9ca3af' }}">
                            {{ $modifier['price'] > 0 ? '+Rp ' . number_format($modifier['price'], 0, ',', '.') : 'Gratis' }}
                        </div>
                    </button>
                    @endforeach
                </div>
            </div>
            @endforeach
            @php $modifierTotal = collect($pendingModifierGroups)->flatMap(fn($g) => $g['modifiers'])->filter(fn($m) => in_array($m['id'], $selectedModifiers))->sum('price'); @endphp
            <div style="border-top:1px solid #f3f4f6;padding-top:12px;margin-top:4px">
                <div style="display:flex;justify-content:space-between;margin-bottom:4px">
                    <span style="font-size:12px;color:#6b7280">Harga produk</span>
                    <span style="font-size:12px;color:#374151">Rp {{ number_format($pendingProduct?->price ?? 0, 0, ',', '.') }}</span>
                </div>
                @if($modifierTotal > 0)
                <div style="display:flex;justify-content:space-between;margin-bottom:4px">
                    <span style="font-size:12px;color:#6b7280">Pilihan tambahan</span>
                    <span style="font-size:12px;color:#16a34a">+Rp {{ number_format($modifierTotal, 0, ',', '.') }}</span>
                </div>
                @endif
                <div style="display:flex;justify-content:space-between;margin-top:6px;padding-top:6px;border-top:1px dashed #e5e7eb">
                    <span style="font-size:14px;font-weight:600;color:#111827">Total</span>
                    <span style="font-size:14px;font-weight:700;color:#16a34a">Rp {{ number_format(($pendingProduct?->price ?? 0) + $modifierTotal, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
        <div class="modal-actions">
            <button class="modal-act-btn" wire:click="closeModifierModal" style="background:#f3f4f6;color:#374151">Batal</button>
            <button class="modal-act-btn btn-print" wire:click="confirmModifiers" style="flex:1;justify-content:center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:16px;height:16px"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                Tambah ke Keranjang
            </button>
        </div>
    </div>
</div>
@endif

{{-- MODAL SIMPAN DRAFT --}}
@if($showSaveDraftModal)
<div class="modal-overlay" style="z-index:9999">
    <div class="modal-box" style="max-width:380px;width:100%">
        <div class="modal-head">
            <div class="mh-left">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:18px;height:18px"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                <span>Simpan Draft</span>
            </div>
            <button class="mh-close" wire:click="closeSaveDraftModal">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="modal-body" style="padding:16px 20px">
            <div style="background:#f9fafb;border:1px solid #f3f4f6;border-radius:10px;padding:10px 12px;margin-bottom:14px" x-data x-html="$store.posCart.draftPreviewHtml()"></div>
            <div>
                <label style="font-size:12px;font-weight:600;color:#374151;display:block;margin-bottom:6px">Nama Draft <span style="color:#9ca3af;font-weight:400">(opsional)</span></label>
                <input wire:model="draftLabel" type="text" placeholder="cth: Pesanan Bu Sari, Meja 3..." style="width:100%;padding:8px 12px;border:1.5px solid #e5e7eb;border-radius:8px;font-size:13px;color:#111827;outline:none;box-sizing:border-box" autofocus maxlength="60"/>
            </div>
        </div>
        <div class="modal-actions">
            <button class="modal-act-btn" wire:click="closeSaveDraftModal" style="background:#f3f4f6;color:#374151">Batal</button>
            <button class="modal-act-btn btn-print" wire:click="saveDraft" style="flex:1;justify-content:center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:16px;height:16px"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                Simpan Draft
            </button>
        </div>
    </div>
</div>
@endif

{{--POS --}}

<div class="pos-wrap"
    x-data
    x-init="
        $store.posCart.init();
        $store.posCart.modifierCache = @js($this->productsModifiers);
    ">


    {{--  Produk ─ --}}
    <div class="pos-left">
        <div class="prod-toolbar">
            <div class="search-wrap">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                <input wire:model.live.debounce.300ms="searchProduct" type="text" placeholder="Scan / cari produk atau barcode..." class="search-input"/>
            </div>
            <div class="barcode-btn" title="Scan Barcode">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 6.75h.75v.75h-.75v-.75zM6.75 16.5h.75v.75h-.75v-.75zM16.5 6.75h.75v.75h-.75v-.75zM13.5 13.5h.75v.75h-.75v-.75zM13.5 18.75h.75v.75h-.75v-.75zM18.75 13.5h.75v.75h-.75v-.75zM18.75 18.75h.75v.75h-.75v-.75zM16.5 16.5h.75v.75h-.75v-.75z"/></svg>
            </div>
        </div>

        {{-- Category tabs --}}
        <div class="cat-bar">
            <button wire:click="selectCategory(null)" class="cat-pill {{ is_null($selectedCategory) ? 'active' : '' }}">Semua</button>
            @foreach($this->categories as $cat)
            @if($cat->products_count > 0)
            <button wire:click="selectCategory({{ $cat->id }})" class="cat-pill {{ $selectedCategory === $cat->id ? 'active' : '' }}">{{ $cat->name }} <span style="opacity:.7">({{ $cat->products_count }})</span></button>
            @endif
            @endforeach
        </div>

        {{-- Product grid --}}
        <div class="prod-body" wire:poll.30s="refreshProducts">
            <div class="prod-grid">
                @forelse($this->products as $product)
                <button
                    class="prod-card"
                    :class="$store.posCart.inCart({{ $product->id }}) ? 'in-cart' : ''"
                    @click="$store.posCart.addProductFast(
                        {{ $product->id }}, 
                        @js([
                            'id'            => $product->id,
                            'name'          => $product->name,
                            'price'         => $product->price,
                            'stock'         => $product->stock,
                            'image'         => $product->image,
                            'has_modifiers' => $product->modifier_groups_count > 0,
                        ]),
                        $wire
                    )"
                    wire:key="prod-{{ $product->id }}"
                >
                    <div class="pc-img-wrap">
                        @if($product->image)
                            <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" loading="lazy" decoding="async">
                        @else
                            <div class="pc-img-placeholder">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                            </div>
                        @endif
                        {{-- Badge qty --}}
                        <div class="pc-cart-badge" x-show="$store.posCart.inCart({{ $product->id }})" x-text="$store.posCart.qtyInCart({{ $product->id }})" style="display:none"></div>
                    </div>
                    <div class="pc-body">
                        <div class="pc-name">{{ $product->name }}</div>
                        @if($product->barcode)<div class="pc-sku">{{ $product->barcode }}</div>@endif
                        <div class="pc-stock">Stok: <strong>{{ $product->stock }}</strong></div>
                        <div class="pc-footer">
                            <span class="pc-price">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            <span class="pc-add-btn">+</span>
                        </div>
                    </div>
                </button>
                @empty
                <div class="prod-empty">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>
                    <p>Produk tidak ditemukan</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Bottom bar --}}
        <div class="prod-footer">
            <span class="pf-info"><strong>{{ $this->products->count() }}</strong> produk ditemukan</span>
            <div class="pf-actions">
                {{-- DRAFT --}}
                <button
                    class="pf-btn"
                    :class="$store.posCart.showDraftPanel ? 'active' : ''"
                    @click="$wire.toggleDraftPanel()"
                    title="Draft tersimpan"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:13px;height:13px"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                    Draft
                    @if($this->savedDrafts->count() > 0)
                    <span style="background:#6366f1;color:#fff;border-radius:99px;padding:0 5px;font-size:10px;line-height:16px">{{ $this->savedDrafts->count() }}</span>
                    @endif
                </button>
                {{-- HOLD --}}
                <button
                    class="pf-btn hold"
                    :class="$store.posCart.showHoldPanel ? 'active' : ''"
                    @click="$store.posCart.toggleHoldPanel()"
                    title="Tahan transaksi (maks. 3)"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:13px;height:13px"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25v13.5m-7.5-13.5v13.5"/></svg>
                    Tahan
                    <span x-show="$store.posCart.holdSlots.length > 0"
                          x-text="$store.posCart.holdSlots.length"
                          style="background:#f59e0b;color:#fff;border-radius:99px;padding:0 5px;font-size:10px;line-height:16px;display:none"></span>
                </button>
            </div>
        </div>
    </div>

    {{--  Keranjang  --}}
    <div class="pos-cart">

        {{-- Header --}}
        <div class="cart-header">
            <div class="ch-left">
                <div class="ch-title">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/></svg>
                    Keranjang
                    <span class="ch-badge" x-show="$store.posCart.itemCount() > 0" x-text="$store.posCart.itemCount()" style="display:none"></span>
                </div>
            </div>
            <button class="ch-clear" x-show="$store.posCart.cart.length > 0" @click="$store.posCart.clearCart()" style="display:none">Hapus semua</button>
        </div>

        {{-- Hold Panel --}}
        <div x-show="$store.posCart.showHoldPanel" style="display:none">
            <div style="border:1.5px solid #f59e0b;border-radius:12px;margin:0 0 10px;background:#fffbeb;overflow:hidden;">
                <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 14px;border-bottom:1px solid #fde68a">
                    <div style="display:flex;align-items:center;gap:6px;font-size:13px;font-weight:700;color:#92400e">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:15px;height:15px"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25v13.5m-7.5-13.5v13.5"/></svg>
                        Ditahan (<span x-text="$store.posCart.holdSlots.length"></span>/3)
                    </div>
                    <button @click="$store.posCart.holdCart()" style="font-size:11px;padding:3px 10px;border-radius:99px;background:#f59e0b;color:#fff;cursor:pointer;font-weight:600;border:none">+ Tahan Sekarang</button>
                </div>
                <template x-if="$store.posCart.holdSlots.length === 0">
                    <div style="padding:16px;text-align:center;font-size:12px;color:#a16207">Belum ada transaksi yang ditahan.</div>
                </template>
                <template x-for="(slot, i) in $store.posCart.holdSlots" :key="i">
                    <div style="display:flex;align-items:center;gap:10px;padding:10px 14px;border-top:1px solid #fde68a">
                        <div style="flex:1;min-width:0">
                            <div style="font-size:13px;font-weight:600;color:#78350f" x-text="slot.label"></div>
                            <div style="font-size:11px;color:#a16207">
                                <span x-text="slot.itemCount + ' item'"></span> ·
                                <span x-text="'Rp ' + slot.total.toLocaleString('id-ID')"></span> ·
                                <span x-text="slot.heldAt"></span>
                                <span x-show="slot.studentName"> · <span x-text="slot.studentName"></span></span>
                                <span x-show="!slot.studentName && slot.isGuest"> · Tamu</span>
                            </div>
                        </div>
                        <button @click="$store.posCart.restoreHold(i)" style="font-size:11px;padding:3px 10px;border-radius:99px;background:#16a34a;color:#fff;cursor:pointer;font-weight:600;border:none;white-space:nowrap">Pulihkan</button>
                        <button @click="$store.posCart.discardHold(i)" style="font-size:11px;padding:3px 10px;border-radius:99px;background:#ef4444;color:#fff;cursor:pointer;font-weight:600;border:none">×</button>
                    </div>
                </template>
            </div>
        </div>

        {{-- Draft Panel --}}
        @if($showDraftPanel)
        <div style="border:1.5px solid #6366f1;border-radius:12px;margin:0 0 10px;background:#eef2ff;overflow:hidden;">
            <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 14px;border-bottom:1px solid #c7d2fe">
                <div style="display:flex;align-items:center;gap:6px;font-size:13px;font-weight:700;color:#3730a3">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:15px;height:15px"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                    Draft Tersimpan ({{ $this->savedDrafts->count() }})
                </div>
                <button @click="$store.posCart.openSaveDraftModal($wire)" style="font-size:11px;padding:3px 10px;border-radius:99px;background:#6366f1;color:#fff;cursor:pointer;font-weight:600;border:none">+ Simpan Sekarang</button>
            </div>
            @if($this->savedDrafts->isEmpty())
            <div style="padding:16px;text-align:center;font-size:12px;color:#4338ca">Belum ada draft tersimpan.</div>
            @else
            <div style="display:flex;flex-direction:column;gap:0;max-height:220px;overflow-y:auto">
                @foreach($this->savedDrafts as $draft)
                <div style="display:flex;align-items:center;gap:10px;padding:10px 14px;{{ !$loop->last ? 'border-bottom:1px solid #c7d2fe;' : '' }}">
                    <div style="flex:1;min-width:0">
                        <div style="font-size:13px;font-weight:600;color:#312e81;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $draft->display_label }}</div>
                        <div style="font-size:11px;color:#4338ca">{{ $draft->summary }}@if($draft->student_name) · {{ $draft->student_name }}@endif · {{ $draft->created_at->format('d/m H:i') }}</div>
                    </div>
                    <button wire:click="loadDraft({{ $draft->id }})" style="font-size:11px;padding:3px 10px;border-radius:99px;background:#16a34a;color:#fff;cursor:pointer;font-weight:600;border:none;white-space:nowrap">Muat</button>
                    <button wire:click="deleteDraft({{ $draft->id }})" wire:confirm="Hapus draft \"{{ $draft->display_label }}\"?" style="font-size:11px;padding:3px 10px;border-radius:99px;background:#ef4444;color:#fff;cursor:pointer;font-weight:600;border:none">×</button>
                </div>
                @endforeach
            </div>
            @endif
        </div>
        @endif

        {{-- Student --}}
        <div class="cart-student">
            <div class="section-label" style="display:flex;justify-content:space-between;align-items:center;margin-bottom:7px">
                <span>Pembeli</span>
                <div style="display:flex;gap:4px">
                    <button @click="$store.posCart.setGuestMode(false)"
                        :style="'font-size:10px;padding:2px 10px;border-radius:99px;cursor:pointer;transition:all .15s;border:1px solid ' + (!$store.posCart.isGuest ? 'var(--p)' : 'var(--border)') + ';background:' + (!$store.posCart.isGuest ? 'var(--p)' : 'transparent') + ';color:' + (!$store.posCart.isGuest ? '#fff' : 'var(--tx-3)')">Siswa</button>
                    <button @click="$store.posCart.setGuestMode(true)"
                        :style="'font-size:10px;padding:2px 10px;border-radius:99px;cursor:pointer;transition:all .15s;border:1px solid ' + ($store.posCart.isGuest ? 'var(--p)' : 'var(--border)') + ';background:' + ($store.posCart.isGuest ? 'var(--p)' : 'transparent') + ';color:' + ($store.posCart.isGuest ? '#fff' : 'var(--tx-3)')">Tamu</button>
                </div>
            </div>

            {{-- Tamu --}}
            <template x-if="$store.posCart.isGuest">
                <div style="display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:var(--r-sm);background:#f9fafb;border:1px dashed var(--border)">
                    <span style="font-size:22px">👤</span>
                    <div>
                        <div style="font-size:13px;font-weight:600;color:var(--tx)">Pembeli Umum / Tamu</div>
                        <div style="font-size:11px;color:var(--tx-4)">Hanya pembayaran tunai</div>
                    </div>
                </div>
            </template>

            {{-- Siswa terpilih --}}
            <template x-if="!$store.posCart.isGuest && $store.posCart.student.id">
                <div class="stu-card">
                    <div class="stu-card-avatar" x-text="$store.posCart.student.name.charAt(0).toUpperCase()"></div>
                    <div class="stu-card-info">
                        <div class="stu-card-name" x-text="$store.posCart.student.name"></div>
                        <div class="stu-card-meta" x-text="'Kelas ' + $store.posCart.student.class"></div>
                    </div>
                    <div class="stu-card-bal">
                        <div class="stu-card-bal-label">Saldo</div>
                        <div class="stu-card-bal-amount" :class="$store.posCart.student.balance <= 5000 ? 'low' : ''"
                             x-text="'Rp ' + $store.posCart.student.balance.toLocaleString('id-ID')"></div>
                    </div>
                    <button class="stu-clear" @click="$store.posCart.clearStudent(); $wire.set('searchStudent', ''); $wire.set('showStudentDropdown', false)">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </template>

            {{-- Search siswa --}}
            <template x-if="!$store.posCart.isGuest && !$store.posCart.student.id">
                <div class="stu-search-wrap">
                    <svg class="stu-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                    <input wire:model.live.debounce.300ms="searchStudent" type="text" placeholder="Cari nama / kelas / NISN..." class="stu-input"
                        @focus="$wire.set('showStudentDropdown', $wire.searchStudent.length > 0)" autocomplete="off"/>
                    @if($showStudentDropdown && $this->studentSearchResults->isNotEmpty())
                    <div class="stu-dropdown">
                        @foreach($this->studentSearchResults as $student)
                        <div class="stu-option" wire:click="selectStudent({{ $student->id }})">
                            <div>
                                <div class="stu-opt-name">{{ $student->name }}</div>
                                <div class="stu-opt-meta">Kelas {{ $student->class }}</div>
                            </div>
                            <div class="stu-opt-bal">Rp {{ number_format($student->balance, 0, ',', '.') }}</div>
                        </div>
                        @endforeach
                    </div>
                    @elseif($showStudentDropdown && strlen($searchStudent) > 0)
                    <div class="stu-dropdown">
                        <div style="padding:14px;text-align:center;color:#9ca3af;font-size:13px">Siswa tidak ditemukan</div>
                    </div>
                    @endif
                </div>
            </template>
        </div>

        {{-- Kembalian preview  --}}
        <template x-if="($store.posCart.isGuest || $store.posCart.payMethod === 'cash') && $store.posCart.cashAmount > 0 && $store.posCart.total() > 0">
            <div class="cart-cash">
                <div class="kem-row">
                    <span class="kem-label">Kembalian</span>
                    <span class="kem-amount" x-text="'Rp ' + Math.max(0, $store.posCart.cashAmount - $store.posCart.total()).toLocaleString('id-ID')"></span>
                </div>
            </div>
        </template>

        {{-- Cart items --}}
        <div class="cart-items">
            <template x-if="$store.posCart.cart.length === 0">
                <div class="cart-empty">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.2" stroke="currentColor" style="color:#d1d5db"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/></svg>
                    <p>Keranjang masih kosong</p>
                </div>
            </template>
            <template x-for="(item, index) in $store.posCart.cart" :key="index">
                <div class="cart-item">
                    <div class="ci-thumb">
                        <template x-if="item.image">
                            <img :src="'/storage/' + item.image" :alt="item.name">
                        </template>
                        <template x-if="!item.image">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                        </template>
                    </div>
                    <div class="ci-info">
                        <div class="ci-name" x-text="item.name"></div>
                        <template x-if="item.modifiers && item.modifiers.length > 0">
                            <div style="margin-top:2px">
                                <template x-for="mod in item.modifiers" :key="mod.id">
                                    <span style="font-size:10px;background:#f0fdf4;color:#16a34a;padding:1px 7px;border-radius:99px;margin-right:3px;display:inline-block;margin-top:2px" x-text="'+' + mod.name"></span>
                                </template>
                            </div>
                        </template>
                        <div class="ci-unit" x-text="'Rp ' + (item.base_price ?? item.price).toLocaleString('id-ID') + ' / pcs'"></div>
                    </div>
                    <div class="ci-right">
                        <div class="ci-qty-row">
                            <button class="qty-btn" @click="$store.posCart.decrement(index)">−</button>
                            <span class="qty-num" x-text="item.quantity"></span>
                            <button class="qty-btn" @click="$store.posCart.increment(index)">+</button>
                        </div>
                        <span class="ci-sub" x-text="'Rp ' + (item.price * item.quantity).toLocaleString('id-ID')"></span>
                    </div>
                </div>
            </template>
        </div>

        {{-- Cart footer --}}
        <div class="cart-footer">
            <div class="cf-rows">
                <div class="cf-row"><span>Subtotal</span><span x-text="'Rp ' + $store.posCart.total().toLocaleString('id-ID')"></span></div>
                <div class="cf-row total">
                    <span>Total</span>
                    <span class="cf-total-amt" x-text="'Rp ' + $store.posCart.total().toLocaleString('id-ID')"></span>
                </div>
            </div>

            {{-- Saldo habis bayar --}}
            <template x-if="$store.posCart.payMethod === 'wallet' && $store.posCart.student.id && $store.posCart.cart.length > 0">
                <div class="cf-saldo-row" :class="$store.posCart.student.balance < $store.posCart.total() ? 'low' : ''">
                    <span class="cf-saldo-label" :class="$store.posCart.student.balance < $store.posCart.total() ? 'low' : ''">Sisa saldo</span>
                    <span class="cf-saldo-val"   :class="$store.posCart.student.balance < $store.posCart.total() ? 'low' : ''"
                          x-text="'Rp ' + Math.max(0, $store.posCart.student.balance - $store.posCart.total()).toLocaleString('id-ID')"></span>
                </div>
            </template>
            <template x-if="$store.posCart.payMethod === 'wallet' && $store.posCart.student.id && $store.posCart.cart.length > 0 && $store.posCart.student.balance < $store.posCart.total()">
                <div class="saldo-warn" style="margin-top:6px">
                    ⚠ Saldo tidak cukup — kurang Rp <span x-text="($store.posCart.total() - $store.posCart.student.balance).toLocaleString('id-ID')"></span>
                </div>
            </template>

            <button class="btn-pay" @click="$store.posCart.openCheckout()" :disabled="$store.posCart.cart.length === 0" style="margin-top:10px">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:18px;height:18px"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/></svg>
                Pay Now
            </button>
        </div>
    </div>

   {{-- modal cekout --}}
    <template x-if="$store.posCart.showCheckout">
        <div class="co-overlay" x-data="{
            get total() { return $store.posCart.total(); },
            get method() { return $store.posCart.payMethod; },
            cashInput: 0,
            fmt(n) { return 'Rp ' + Math.max(0,n).toLocaleString('id-ID'); },
            init() { this.cashInput = this.total; },
            selectMethod(m) { $store.posCart.payMethod = m; },
            get change() { return Math.max(0, this.cashInput - this.total); },
            get balanceAfter() { return Math.max(0, $store.posCart.student.balance - this.total); },
            completePayment() {
                $store.posCart.cashAmount = parseFloat(this.cashInput) || 0;
                $store.posCart.checkout($wire);
            }
        }">
            <div class="co-box">
                <div class="co-head">
                    <div class="co-head-left">
                        <div class="co-head-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/></svg>
                        </div>
                        <span class="co-head-title">Payment Checkout</span>
                    </div>
                    <button class="co-head-close" @click="$store.posCart.showCheckout = false">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <div class="co-body">
                    {{-- Summary --}}
                    <div class="co-summary">
                        <div class="co-sum-label">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z"/></svg>
                            Transaction Summary
                        </div>
                        <div class="co-total-box">
                            <div class="co-total-lbl">TOTAL AMOUNT</div>
                            <div class="co-total-amt" x-text="fmt(total)"></div>
                        </div>
                        <div class="co-breakdown">
                            <div style="font-size:10px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.06em">Payment Breakdown</div>
                            <div class="co-bd-item"><div class="co-bd-icon green">✅</div><div class="co-bd-info"><div class="co-bd-lbl">Paying</div><div class="co-bd-val" x-text="fmt(total)"></div></div></div>
                            <div class="co-bd-item"><div class="co-bd-icon orange">💰</div><div class="co-bd-info"><div class="co-bd-lbl">Balance / Kembalian</div><div class="co-bd-val orange" x-text="method === 'wallet' ? fmt(balanceAfter) : fmt(change)"></div></div></div>
                            <div class="co-bd-item"><div class="co-bd-icon blue">🔄</div><div class="co-bd-info"><div class="co-bd-lbl">Change</div><div class="co-bd-val blue" x-text="method === 'cash' ? fmt(change) : 'Rp 0'"></div></div></div>
                        </div>
                        <div>
                            <div style="font-size:10px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.06em;margin-bottom:8px">Items</div>
                            <div class="co-items">
                                <template x-for="item in $store.posCart.cart" :key="item.product_id + '_' + JSON.stringify(item.modifiers)">
                                    <div class="co-item">
                                        <span class="co-item-name" x-text="item.name"></span>
                                        <span class="co-item-qty" x-text="'×' + item.quantity"></span>
                                        <span class="co-item-price" x-text="'Rp ' + (item.price * item.quantity).toLocaleString('id-ID')"></span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    {{-- Payment --}}
                    <div class="co-payment">
                        <div class="co-pay-num"><div class="co-pay-badge">1</div><div class="co-pay-title">Payment #1</div></div>
                        <div>
                            <div class="co-field-label">Amount</div>
                            <div class="co-amount-wrap">
                                <span class="co-amount-prefix">Rp</span>
                                <input type="number" class="co-amount-inp" x-model.number="cashInput" placeholder="0"/>
                            </div>
                        </div>

                        <template x-if="method === 'wallet' && $store.posCart.student.id">
                            <div class="co-student-section">
                                <div class="co-field-label">Siswa</div>
                                <div class="co-stu-selected">
                                    <div class="co-stu-avatar" x-text="$store.posCart.student.name.charAt(0).toUpperCase()"></div>
                                    <div class="co-stu-info">
                                        <div class="co-stu-name" x-text="$store.posCart.student.name"></div>
                                        <div class="co-stu-meta" x-text="'Kelas ' + $store.posCart.student.class"></div>
                                    </div>
                                    <div class="co-stu-bal" x-text="'Rp ' + $store.posCart.student.balance.toLocaleString('id-ID')"></div>
                                </div>
                            </div>
                        </template>

                        <div>
                            <div class="co-field-label">Metode Pembayaran</div>
                            <div class="co-method-grid">
                                <template x-if="$store.posCart.isGuest">
                                    <button class="co-method-btn" :class="method === 'cash' ? 'active' : ''" @click="selectMethod('cash')">
                                        <div class="co-method-check" x-show="method === 'cash'">✓</div>
                                        <div class="co-method-icon indigo">🏧</div><span>Tunai</span>
                                    </button>
                                </template>
                                <template x-if="$store.posCart.isGuest">
                                    <button class="co-method-btn" :class="method === 'transfer' ? 'active' : ''" @click="selectMethod('transfer')">
                                        <div class="co-method-check" x-show="method === 'transfer'">✓</div>
                                        <div class="co-method-icon indigo">🏦</div><span>Transfer</span>
                                    </button>
                                </template>
                                <template x-if="!$store.posCart.isGuest">
                                    <button class="co-method-btn active">
                                        <div class="co-method-check">✓</div>
                                        <div class="co-method-icon green">👛</div><span>Dompet Siswa</span>
                                    </button>
                                </template>
                            </div>
                        </div>

                        <div>
                            <div class="co-field-label">Payment Date</div>
                            <input type="date" class="co-date-inp" value="{{ now()->format('Y-m-d') }}" readonly>
                        </div>
                    </div>

                    {{-- Quick amounts --}}
                    <div class="co-quick">
                        <div class="co-quick-label">Quick Amount</div>
                        <template x-for="qa in $store.posCart.quickAmounts()" :key="qa">
                            <button class="co-quick-btn" :class="qa === total ? 'exact' : ''" @click="cashInput = qa">
                                <span x-text="(qa === total ? '✓ Exact ' : '') + 'Rp ' + qa.toLocaleString('id-ID')"></span>
                            </button>
                        </template>
                    </div>
                </div>

                <div class="co-footer">
                    <button class="co-btn-cancel" @click="$store.posCart.showCheckout = false">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        Cancel
                    </button>
                    <button class="co-btn-complete" @click="completePayment()" wire:loading.attr="disabled" wire:target="checkoutFromAlpine">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Complete Payment
                    </button>
                </div>
            </div>
        </div>
    </template>
</div>

@push('scripts')
<script>

document.addEventListener('alpine:init', () => {

    // ── Cart Store ───────────────────────────────────────────────────
    Alpine.store('posCart', {
        cart: [],
        modifierCache: {},
        student: { id: null, name: '', class: '', balance: 0 },
        isGuest: false,
        payMethod: 'wallet',
        cashAmount: 0,
        showCheckout: false,
        showHoldPanel: false,
        showDraftPanel: false,
        holdSlots: [],          // max 3
        _pendingWire: null,     // referensi $wire sementara
        _adding: false,

        init() {
            if (window._posCartInitialized) return;
            window._posCartInitialized = true;

            window.addEventListener('student-selected',   e => Alpine.store('posCart').onStudentSelected(e.detail.student));
            window.addEventListener('modifier-confirmed',  e => Alpine.store('posCart').pushItem(e.detail.item));
            window.addEventListener('checkout-success',   () => Alpine.store('posCart').resetCart());
            window.addEventListener('draft-saved',        () => Alpine.store('posCart').resetCart());
            window.addEventListener('draft-loaded',        e => Alpine.store('posCart').onDraftLoaded(e.detail.payload));
        
            window.addEventListener('show-toast', e => {
                Alpine.store('posCart').showToast(e.detail.message, e.detail.type ?? 'success');
            });
        },


        // Helpers
        total() {
            return this.cart.reduce((s, i) => s + i.price * i.quantity, 0);
        },
        itemCount() {
            return this.cart.reduce((s, i) => s + i.quantity, 0);
        },
        inCart(productId) {
            return this.cart.some(i => i.product_id === productId && (!i.modifiers || i.modifiers.length === 0));
        },
        qtyInCart(productId) {
            const item = this.cart.find(i => i.product_id === productId && (!i.modifiers || i.modifiers.length === 0));
            return item ? item.quantity : 0;
        },
        quickAmounts() {
            const t = this.total();
            const fixed = [2000, 5000, 10000, 20000, 50000, 100000];
            return [...new Set([t, ...fixed])].filter(n => n > 0).sort((a, b) => a - b);
        },

        // Produk
        async addProduct(productId, wire) {
            // cegah double-call
            if (this._adding) return;
            this._adding = true;

            try {
                const result = await wire.getProductForCart(productId);
                if (!result || result.action === 'error') {
                    if (result?.message) this.showToast(result.message, 'error');
                    return;
                }
                if (result.action === 'modifier_modal') return; // server buka modal
                if (result.action === 'add_to_cart') {
                    this.pushItem(result);
                    this.playBeep();
                }
            } finally {
                // reset
                this._adding = false;
            }
        },
                async addProductFast(productId, productData, wire) {
            if (this._adding) return;
            this._adding = true;
 
            try {
                const hasModifiers = productData.has_modifiers ?? false;
 
                if (!hasModifiers) {
                    // Tidak ada modifier — langsung push, zero latency
                    this.pushItem({
                        action:     'add_to_cart',
                        product_id: productData.id,
                        name:       productData.name,
                        price:      parseFloat(productData.price),
                        base_price: parseFloat(productData.price),
                        stock:      productData.stock,
                        image:      productData.image,
                        modifiers:  [],
                    });
                    this.playBeep();
                } else {
                    // Ada modifier — ambil dari cache lokal, zero DB query
                    const groups = this.modifierCache[productId] ?? [];
                    if (groups.length === 0) {
                        // Fallback ke server jika cache kosong (seharusnya tidak terjadi)
                        const result = await wire.getProductForCart(productId);
                        if (!result || result.action === 'error') {
                            if (result?.message) this.showToast(result.message, 'error');
                            return;
                        }
                        if (result.action === 'modifier_modal') return;
                        if (result.action === 'add_to_cart') {
                            this.pushItem(result);
                            this.playBeep();
                        }
                    } else {
                        // Buka modal langsung dari data cache — hanya 1 Livewire call ringan
                        await wire.openModifierModalFromData(productId, groups);
                    }
                }
            } finally {
                this._adding = false;
            }
        },

        pushItem(item) {
            // Produk tanpa modifier gabung qty
            if (!item.modifiers || item.modifiers.length === 0) {
                const idx = this.cart.findIndex(
                    i => i.product_id === item.product_id &&
                         (!i.modifiers || i.modifiers.length === 0)
                );
                if (idx >= 0) {
                    if (this.cart[idx].quantity >= item.stock) {
                        this.showToast('Stok tidak cukup!', 'error');
                        return;
                    }
                    this.cart[idx].quantity++;
                    return;
                }
            }
            // Produk dengan modifier atau baru push
            this.cart.push({
                product_id: item.product_id,
                name:       item.name,
                price:      item.price,
                base_price: item.base_price ?? item.price,
                quantity:   1,
                stock:      item.stock,
                image:      item.image,
                modifiers:  item.modifiers ?? [],
            });
        },

        increment(index) {
            const item = this.cart[index];
            if (item.quantity >= item.stock) {
                this.showToast('Stok tidak cukup!', 'error');
                return;
            }
            this.cart[index].quantity++;
        },
        decrement(index) {
            if (this.cart[index].quantity <= 1) {
                this.cart.splice(index, 1);
            } else {
                this.cart[index].quantity--;
            }
        },
        clearCart() {
            if (confirm('Hapus semua item dari keranjang?')) {
                this.resetCart();
            }
        },
        resetCart() {
            this.cart        = [];
            this.cashAmount  = 0;
            this.showCheckout = false;
        },

        // Student
        onStudentSelected(student) {
            this.student   = student;
            this.payMethod = 'wallet';
            this.isGuest   = false;
        },
        clearStudent() {
            this.student   = { id: null, name: '', class: '', balance: 0 };
            this.payMethod = 'cash';
        },
        setGuestMode(guest) {
            this.isGuest   = guest;
            this.payMethod = guest ? 'cash' : 'wallet';
            if (guest) this.clearStudent();
        },

        // Checkout
        openCheckout() {
            if (this.cart.length === 0) return;
            this.showCheckout = true;
        },
        async checkout(wire) {
            await wire.checkoutFromAlpine(
                this.cart,
                this.student.id,
                this.student.name,
                this.student.class,
                this.student.balance,
                this.isGuest,
                this.payMethod,
                this.cashAmount
            );
        },

        // Hold 
        MAX_HOLD: 3,
        toggleHoldPanel() {
            this.showHoldPanel  = !this.showHoldPanel;
            this.showDraftPanel = false;
        },
        holdCart() {
            if (this.cart.length === 0) {
                this.showToast('Keranjang kosong, tidak ada yang ditahan.', 'error');
                return;
            }
            if (this.holdSlots.length >= this.MAX_HOLD) {
                this.showToast('Maksimal 3 transaksi yang bisa ditahan!', 'error');
                return;
            }
            const n = this.holdSlots.length + 1;
            this.holdSlots.push({
                label:       'Tahan #' + n,
                cart:        JSON.parse(JSON.stringify(this.cart)),
                student:     { ...this.student },
                isGuest:     this.isGuest,
                payMethod:   this.payMethod,
                heldAt:      new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }),
                studentName: this.student.name,
                total:       this.total(),
                itemCount:   this.itemCount(),
            });
            this.resetCart();
            this.clearStudent();
            this.showToast('Transaksi ditahan sebagai Tahan #' + n, 'success');
        },
        restoreHold(index) {
            if (this.cart.length > 0) {
                if (this.holdSlots.length >= this.MAX_HOLD) {
                    this.showToast('Slot penuh! Selesaikan salah satu transaksi yang ditahan.', 'error');
                    return;
                }
                this.holdCart();
            }
            const slot       = this.holdSlots.splice(index, 1)[0];
            this.cart        = slot.cart;
            this.student     = slot.student;
            this.isGuest     = slot.isGuest;
            this.payMethod   = slot.payMethod;
            this.showHoldPanel = false;
            this.showToast('Transaksi "' + slot.label + '" dipulihkan.', 'success');
        },
        discardHold(index) {
            const label = this.holdSlots[index].label;
            this.holdSlots.splice(index, 1);
            this.showToast('Transaksi "' + label + '" dihapus.', 'success');
        },

        // Draft
        async openSaveDraftModal(wire) {
            if (this.cart.length === 0) {
                this.showToast('Keranjang kosong!', 'error');
                return;
            }
            await wire.openSaveDraftModal(
                this.cart,
                this.student.id,
                this.student.name,
                this.student.class,
                this.student.balance,
                this.isGuest,
                this.payMethod
            );
        },
        onDraftLoaded(payload) {
            this.cart      = payload.cart;
            this.isGuest   = payload.is_guest;
            this.payMethod = payload.pay_method;
            this.student   = {
                id:      payload.student_id,
                name:    payload.student_name   ?? '',
                class:   payload.student_class  ?? '',
                balance: payload.student_balance ?? 0,
            };
        },

        // Preview draft untuk modal
        draftPreviewHtml() {
            if (this.cart.length === 0) return '<p style="color:#9ca3af;font-size:12px">Keranjang kosong</p>';
            const rows = this.cart.map(i =>
                `<div style="display:flex;justify-content:space-between;font-size:12px;color:#374151;margin-bottom:2px">
                    <span>${i.name} ×${i.quantity}</span>
                    <span>Rp ${(i.price * i.quantity).toLocaleString('id-ID')}</span>
                </div>`
            ).join('');
            const total = `<div style="border-top:1px dashed #e5e7eb;margin-top:6px;padding-top:6px;display:flex;justify-content:space-between;font-size:13px;font-weight:700;color:#111827">
                <span>Total</span><span>Rp ${this.total().toLocaleString('id-ID')}</span>
            </div>`;
            return `<div style="font-size:11px;color:#6b7280;margin-bottom:6px;font-weight:600;text-transform:uppercase;letter-spacing:.05em">Isi Keranjang</div>${rows}${total}`;
        },

        //Toast & Beep 
        showToast(message, type = 'success') {
            // Hapus toast yang sudah ada supaya tidak numpuk
            document.querySelectorAll('.pos-toast').forEach(t => t.remove());

            const colors = {
                success: { bg: '#16a34a', icon: 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z' },
                error:   { bg: '#ef4444', icon: 'M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z' },
                warning: { bg: '#f59e0b', icon: 'M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z' },
            };

            const c = colors[type] ?? colors.success;

            const toast = document.createElement('div');
            toast.className = 'pos-toast';
            toast.style.cssText = `
                position: fixed;
                top: 16px;
                right: 24px;
                bottom: unset;
                z-index: 999999;
                display: flex;
                align-items: center;
                gap: 10px;
                padding: 10px 16px;
                border-radius: 10px;
                background: ${c.bg};
                color: #fff;
                font-size: 13px;
                font-weight: 500;
                font-family: sans-serif;
                max-width: 300px;
                box-shadow: 0 4px 16px rgba(0,0,0,.2);
                animation: posToastIn .25s ease;
                pointer-events: none;
            `;

            toast.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width:16px;height:16px;flex-shrink:0">
                    <path stroke-linecap="round" stroke-linejoin="round" d="${c.icon}"/>
                </svg>
                <span>${message}</span>
            `;

            // Inject keyframe sekali saja
            if (!document.getElementById('pos-toast-style')) {
                const style = document.createElement('style');
                style.id = 'pos-toast-style';
                style.textContent = `
                    @keyframes posToastIn {
                        from { opacity: 0; transform: translateY(-10px) scale(.97); }
                        to   { opacity: 1; transform: translateY(0)     scale(1);   }
                    }
                    @keyframes posToastOut {
                        from { opacity: 1; transform: translateY(0); }
                        to   { opacity: 0; transform: translateY(-8px); }
                    }
                `;
                document.head.appendChild(style);
            }

            document.body.appendChild(toast);

            // Fade out sebelum remove
            setTimeout(() => {
                toast.style.animation = 'posToastOut .2s ease forwards';
                setTimeout(() => toast.remove(), 200);
            }, 2300);
        },
        playBeep() {
            try {
                const ctx = new (window.AudioContext || window.webkitAudioContext)();
                const beep = (freq, start, dur) => {
                    const o = ctx.createOscillator(), g = ctx.createGain();
                    o.connect(g); g.connect(ctx.destination);
                    o.type = 'sine'; o.frequency.setValueAtTime(freq, ctx.currentTime + start);
                    g.gain.setValueAtTime(0, ctx.currentTime + start);
                    g.gain.linearRampToValueAtTime(0.4, ctx.currentTime + start + 0.005);
                    g.gain.setValueAtTime(0.4, ctx.currentTime + start + dur - 0.01);
                    g.gain.linearRampToValueAtTime(0, ctx.currentTime + start + dur);
                    o.start(ctx.currentTime + start); o.stop(ctx.currentTime + start + dur);
                };
                beep(1200, 0, 0.06); beep(1800, 0.07, 0.09);
            } catch(e) {}
        },
    });

    // Receipt modal Alpine component
    Alpine.data('receiptModal', (order) => ({
        order, btConnected: false, btDevice: null, btCharacteristic: null, btStatus: '',
        async initBluetooth() {
            if (!navigator.bluetooth) this.btStatus = '⚠️ Bluetooth tidak tersedia. Gunakan Chrome/Edge.';
        },
        async connectBluetooth() {
            try {
                this.btStatus = 'Mencari printer Bluetooth...';
                const device = await navigator.bluetooth.requestDevice({ acceptAllDevices: true, optionalServices: ['000018f0-0000-1000-8000-00805f9b34fb','0000ff00-0000-1000-8000-00805f9b34fb'] });
                this.btStatus = 'Menghubungkan ke ' + device.name + '...';
                const server = await device.gatt.connect();
                let svc;
                try { svc = await server.getPrimaryService('000018f0-0000-1000-8000-00805f9b34fb'); } catch { svc = await server.getPrimaryService('0000ff00-0000-1000-8000-00805f9b34fb'); }
                try { this.btCharacteristic = await svc.getCharacteristic('00002af1-0000-1000-8000-00805f9b34fb'); } catch { this.btCharacteristic = await svc.getCharacteristic('0000ff01-0000-1000-8000-00805f9b34fb'); }
                this.btDevice = device; this.btConnected = true;
                this.btStatus = '✅ Terhubung: ' + device.name;
                device.addEventListener('gattserverdisconnected', () => { this.btConnected = false; this.btCharacteristic = null; this.btStatus = '⚠️ Printer terputus'; });
            } catch (err) { this.btConnected = false; this.btStatus = '❌ ' + err.message; }
        },
        disconnectBT() {
            if (this.btDevice?.gatt?.connected) this.btDevice.gatt.disconnect();
            this.btConnected = false; this.btCharacteristic = null; this.btStatus = 'Printer diputuskan';
        },
        async printBluetooth() {
            if (!this.btCharacteristic) { this.btStatus = '❌ Hubungkan printer terlebih dahulu'; return; }
            try {
                this.btStatus = 'Mencetak struk...';
                const data = this.buildEscPos(), chunk = 512;
                for (let i = 0; i < data.length; i += chunk) {
                    await this.btCharacteristic.writeValue(data.slice(i, i + chunk));
                    await new Promise(r => setTimeout(r, 50));
                }
                this.btStatus = '✅ Struk berhasil dicetak!';
            } catch (err) { this.btStatus = '❌ ' + err.message; }
        },
        buildEscPos() {
            const o = this.order, ESC = 0x1B, GS = 0x1D, b = [];
            const push = (...x) => x.forEach(v => b.push(v));
            const text = s => { for (const c of String(s)) b.push(c.charCodeAt(0) > 127 ? 63 : c.charCodeAt(0)); };
            const line = (s='') => { text(s); push(10); };
            const div  = () => line('--------------------------------');
            const pad  = (l,r,w=32) => String(l)+' '.repeat(Math.max(w-String(l).length-String(r).length,1))+String(r);
            const fmt  = n => 'Rp '+Number(n).toLocaleString('id-ID');
            push(ESC,0x40);
            if(o.show_store_name){push(ESC,0x61,1,ESC,0x45,1,GS,0x21,0x11);line(o.store_name);push(GS,0x21,0,ESC,0x45,0);}
            if(o.show_address&&o.store_address){push(ESC,0x61,1);line(o.store_address.substring(0,32));}
            if(o.show_phone&&o.store_phone){push(ESC,0x61,1);line('Telp: '+o.store_phone);}
            push(ESC,0x61,0);div();
            if(o.show_invoice_number)line(pad('Invoice:',o.invoice_number));
            if(o.show_date)line(pad('Tanggal:',o.created_at));
            if(o.show_student&&o.student_name)line(pad('Siswa:',(o.student_name+' / Kls '+o.student_class).substring(0,20)));
            if(o.show_payment_method)line(pad('Bayar:',o.payment_method==='wallet'?'Dompet Siswa':'Tunai'));
            if(o.show_cashier)line(pad('Kasir:',o.cashier_name||'Kasir'));
            div();
            for(const item of o.items){
                if(o.layout==='compact'){line(pad(item.name.substring(0,20),fmt(item.price*item.quantity)));}
                else if(o.layout==='detailed'){line(pad(item.name.substring(0,20)+'  '+item.quantity+'x',fmt(item.price*item.quantity)));}
                else{line(item.name.substring(0,32));if(o.show_item_price)line('  '+item.quantity+'x '+fmt(item.price));if(o.show_subtotal_per_item)line(pad('',fmt(item.price*item.quantity)));}
            }
            div();push(ESC,0x45,1,GS,0x21,1);line(pad('TOTAL:',fmt(o.total_amount)));push(GS,0x21,0,ESC,0x45,0);
            if(o.show_balance_after&&o.payment_method==='wallet'&&o.balance_after!==null)line(pad('Sisa Saldo:',fmt(o.balance_after)));
            if(o.show_change&&o.payment_method==='cash'){line(pad('Bayar:',fmt(o.cash_amount)));line(pad('Kembalian:',fmt(o.change_amount)));}
            if(o.show_footer&&o.store_footer){div();push(ESC,0x61,1);for(const fl of o.store_footer.split('\n')){if(fl.trim())line(fl.trim().substring(0,32));}}
            push(10,10,10,10,GS,0x56,0x41,0x10);
            return new Uint8Array(b);
        }
    }));
});
</script>
@endpush

</div>
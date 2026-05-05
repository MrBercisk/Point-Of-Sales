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

/* ── Student selector ─────────────────────────────────────── */
.student-section { padding:12px 16px; border-bottom:1px solid #f3f4f6; }
.student-label { font-size:11px; font-weight:600; color:#6b7280; text-transform:uppercase; letter-spacing:.05em; margin-bottom:6px; }
.student-search-wrap { position:relative; }
.student-search { width:100%; padding:8px 12px; font-size:13px; border-radius:10px; border:1px solid #e5e7eb; background:#f9fafb; outline:none; box-sizing:border-box; }
.student-search:focus { border-color:#6366f1; box-shadow:0 0 0 2px #e0e7ff; }
.student-dropdown { position:absolute; top:calc(100% + 4px); left:0; right:0; background:#fff; border:1px solid #e5e7eb; border-radius:10px; box-shadow:0 8px 24px rgba(0,0,0,.1); z-index:50; max-height:200px; overflow-y:auto; }
.student-option { padding:8px 12px; cursor:pointer; font-size:13px; display:flex; justify-content:space-between; align-items:center; }
.student-option:hover { background:#f5f3ff; }
.student-option-name { font-weight:500; color:#111827; }
.student-option-meta { font-size:11px; color:#9ca3af; }
.student-option-balance { font-size:12px; font-weight:600; color:#1D9E75; }

/* ── Student card (setelah dipilih) ──────────────────────── */
.student-card { background:#f0fdf4; border:1px solid #bbf7d0; border-radius:10px; padding:10px 12px; display:flex; justify-content:space-between; align-items:center; }
.student-card-info { display:flex; flex-direction:column; gap:2px; }
.student-card-name { font-size:13px; font-weight:700; color:#166534; }
.student-card-meta { font-size:11px; color:#4ade80; }
.student-card-balance { text-align:right; }
.student-card-balance-label { font-size:10px; color:#16a34a; }
.student-card-balance-amount { font-size:15px; font-weight:700; color:#15803d; }
.student-card-balance-amount.low { color:#dc2626; }
.student-card-clear { background:none; border:none; cursor:pointer; color:#9ca3af; padding:2px; margin-left:8px; }
.student-card-clear:hover { color:#ef4444; }

/* ── Payment method toggle ────────────────────────────────── */
.payment-section { padding:10px 16px; border-bottom:1px solid #f3f4f6; }
.payment-label { font-size:11px; font-weight:600; color:#6b7280; text-transform:uppercase; letter-spacing:.05em; margin-bottom:6px; }
.payment-toggle { display:flex; gap:6px; }
.pay-btn { flex:1; padding:7px; border-radius:8px; font-size:12px; font-weight:600; border:1px solid #e5e7eb; background:#f9fafb; color:#6b7280; cursor:pointer; transition:all .15s; text-align:center; }
.pay-btn.active { background:#6366f1; color:#fff; border-color:#6366f1; }
.pay-btn.active-green { background:#1D9E75; color:#fff; border-color:#1D9E75; }

/* ── Cash input ───────────────────────────────────────────── */
.cash-section { padding:8px 16px; border-bottom:1px solid #f3f4f6; }
.cash-input-wrap { position:relative; }
.cash-prefix { position:absolute; left:10px; top:50%; transform:translateY(-50%); font-size:13px; color:#6b7280; font-weight:500; }
.cash-input { width:100%; padding:8px 12px 8px 36px; font-size:14px; font-weight:600; border-radius:8px; border:1px solid #e5e7eb; outline:none; box-sizing:border-box; }
.cash-input:focus { border-color:#6366f1; box-shadow:0 0 0 2px #e0e7ff; }
.kembalian-row { display:flex; justify-content:space-between; margin-top:6px; font-size:13px; }
.kembalian-label { color:#6b7280; }
.kembalian-amount { font-weight:700; color:#1D9E75; }

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
.cart-empty { text-align:center; padding:40px 0; color:#d1d5db; }
.cart-empty p { font-size:13px; }

/* ── Checkout bar ─────────────────────────────────────────── */
.cart-footer { padding:16px; border-top:1px solid #e5e7eb; }
.cart-total-row { display:flex; justify-content:space-between; font-weight:700; font-size:15px; margin-bottom:4px; }
.cart-total-amt { color:#6366f1; }
.saldo-after-row { display:flex; justify-content:space-between; font-size:12px; margin-bottom:10px; color:#6b7280; }
.saldo-after-val { font-weight:600; }
.saldo-after-ok { color:#1D9E75; }
.saldo-after-low { color:#dc2626; }
.btn-checkout { width:100%; padding:12px; border-radius:12px; font-weight:700; font-size:15px; color:#fff; background:#6366f1; border:none; cursor:pointer; transition:all .15s; display:flex; align-items:center; justify-content:center; gap:8px; }
.btn-checkout:hover:not(:disabled) { background:#4f46e5; }
.btn-checkout:disabled { background:#d1d5db; cursor:not-allowed; }
.btn-checkout.wallet-mode { background:#1D9E75; }
.btn-checkout.wallet-mode:hover:not(:disabled) { background:#158a64; }

/* ── Saldo kurang warning ─────────────────────────────────── */
.saldo-warning { background:#fef2f2; border:1px solid #fecaca; border-radius:8px; padding:8px 12px; font-size:12px; color:#dc2626; margin-bottom:8px; text-align:center; }

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
.modal-body { padding:20px; max-height:55vh; overflow-y:auto; }
.receipt-store { text-align:center; margin-bottom:12px; }
.receipt-store-name { font-weight:700; font-size:15px; color:#111827; }
.receipt-store-addr { font-size:11px; color:#6b7280; margin-top:2px; }
.receipt-store-phone { font-size:11px; color:#6b7280; }
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
.receipt-saldo-row { display:flex; justify-content:space-between; font-size:13px; margin-top:6px; padding:6px 8px; background:#f0fdf4; border-radius:6px; }
.receipt-saldo-label { color:#16a34a; font-weight:500; }
.receipt-saldo-val { color:#15803d; font-weight:700; }
.receipt-kembalian-row { display:flex; justify-content:space-between; font-size:13px; margin-top:6px; padding:6px 8px; background:#eff6ff; border-radius:6px; }
.receipt-footer-text { text-align:center; font-size:11px; color:#9ca3af; margin-top:10px; line-height:1.5; }
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
.bt-status { padding:8px 20px; text-align:center; font-size:12px; }
.bt-ok { background:#f0fdf4; color:#15803d; }
.bt-err { background:#fff7ed; color:#c2410c; }

/* ── Dark mode ────────────────────────────────────────────── */
.dark .pos-search,.dark .student-search,.dark .cash-input { background:#1f2937; border-color:#374151; color:#f9fafb; }
.dark .cat-btn,.dark .pay-btn { background:#1f2937; border-color:#374151; color:#d1d5db; }
.dark .prod-card { background:#1f2937; border-color:#374151; }
.dark .prod-name { color:#f9fafb; }
.dark .prod-img-placeholder { background:#374151; }
.dark .pos-cart { background:#111827; border-color:#374151; }
.dark .ci-name,.dark .cart-total-row { color:#f9fafb; }
.dark .qty-btn { background:#374151; color:#d1d5db; }
.dark .modal-box { background:#1f2937; }
.dark .receipt-store-name,.dark .receipt-item-name,.dark .receipt-total-row { color:#f9fafb; }
.dark .modal-actions { background:#111827; border-color:#374151; }
</style>

{{-- ══ MODAL STRUK ══════════════════════════════════════════════════════ --}}
@if($showReceiptModal && $lastOrder)
<div
    class="modal-overlay"
    x-data="receiptModal(@js($lastOrder))"
    x-init="initBluetooth()"
>
    <div class="modal-box">
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

        <div class="modal-body">
            {{-- Header toko dari Settings --}}
            <div class="receipt-store">
                <div class="receipt-store-name">{{ $lastOrder['store_name'] ?? config('pos.store_name', 'Kantin Sekolah') }}</div>
                @if(!empty($lastOrder['store_address']))
                <div class="receipt-store-addr">{{ $lastOrder['store_address'] }}</div>
                @endif
                @if(!empty($lastOrder['store_phone']))
                <div class="receipt-store-phone">Telp: {{ $lastOrder['store_phone'] }}</div>
                @endif
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
                @if($lastOrder['student_name'])
                <div class="receipt-meta-row">
                    <span class="receipt-meta-label">Siswa</span>
                    <span class="receipt-meta-value">{{ $lastOrder['student_name'] }} (Kelas {{ $lastOrder['student_class'] }})</span>
                </div>
                @endif
                <div class="receipt-meta-row">
                    <span class="receipt-meta-label">Pembayaran</span>
                    <span class="receipt-meta-value">{{ $lastOrder['payment_method'] === 'wallet' ? 'Dompet Siswa' : 'Tunai' }}</span>
                </div>
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

            {{-- Sisa saldo jika bayar dompet --}}
            @if($lastOrder['payment_method'] === 'wallet' && isset($lastOrder['balance_after']))
            <div class="receipt-saldo-row">
                <span class="receipt-saldo-label">Sisa Saldo</span>
                <span class="receipt-saldo-val">Rp {{ number_format($lastOrder['balance_after'], 0, ',', '.') }}</span>
            </div>
            @endif

            {{-- Kembalian jika bayar tunai --}}
            @if($lastOrder['payment_method'] === 'cash')
            <div class="receipt-kembalian-row">
                <span style="color:#1d4ed8;font-weight:500">Bayar</span>
                <span style="color:#1e40af;font-weight:700">Rp {{ number_format($lastOrder['cash_amount'], 0, ',', '.') }}</span>
            </div>
            <div class="receipt-kembalian-row" style="background:#f0fdf4">
                <span style="color:#16a34a;font-weight:500">Kembalian</span>
                <span style="color:#15803d;font-weight:700">Rp {{ number_format($lastOrder['change_amount'], 0, ',', '.') }}</span>
            </div>
            @endif

            {{-- Footer dari Settings --}}
            <div class="receipt-footer-text">
                @foreach(explode("\n", $lastOrder['store_footer'] ?? 'Terima kasih telah berbelanja!') as $line)
                    @if(trim($line)) <p>{{ trim($line) }}</p> @endif
                @endforeach
            </div>
        </div>

        <div class="modal-actions">
            @if($lastOrder['pdf_url'])
            <a href="{{ $lastOrder['pdf_url'] }}" target="_blank" class="modal-btn modal-btn-pdf">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/>
                </svg>
                Download PDF
            </a>
            @else
            <div class="modal-btn" style="background:#f3f4f6;color:#9ca3af;cursor:default">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/>
                </svg>
                Tidak ada PDF
            </div>
            @endif

            <button class="modal-btn modal-btn-print" @click="printBluetooth()" :disabled="!btConnected">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5zm-3 0h.008v.008H15V10.5z"/>
                </svg>
                Cetak Struk
            </button>

            <button class="modal-btn" :class="btConnected ? 'modal-btn-bt-on' : 'modal-btn-bt'" @click="btConnected ? disconnectBT() : connectBluetooth()">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.288 15.038a5.25 5.25 0 017.424 0M5.106 11.856c3.807-3.808 9.98-3.808 13.788 0M1.924 8.674c5.565-5.565 14.587-5.565 20.152 0M12.53 18.22l-.53.53-.53-.53a.75.75 0 011.06 0z"/>
                </svg>
                <span x-text="btConnected ? 'Putuskan' : 'Konek Printer'"></span>
            </button>
        </div>

        <div x-show="btStatus !== ''" class="bt-status" :class="btConnected ? 'bt-ok' : 'bt-err'" x-text="btStatus" style="display:none"></div>
    </div>
</div>
@endif

{{-- ══ MAIN POS ══════════════════════════════════════════════════════════ --}}
<div class="pos-wrap">

    {{-- LEFT: Produk --}}
    <div class="pos-left">
        <div class="pos-search-wrap">
            <svg class="pos-search-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
            </svg>
            <input wire:model.live.debounce.300ms="searchProduct" type="text" placeholder="Cari produk..." class="pos-search"/>
        </div>

        <div class="pos-cats">
            <button wire:click="selectCategory(null)" class="cat-btn {{ is_null($selectedCategory) ? 'active' : '' }}">Semua</button>
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
                <p style="font-size:14px">Produk tidak ditemukan</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- RIGHT: Keranjang --}}
    <div class="pos-cart">

        {{-- Header --}}
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

        {{-- ── PILIH SISWA ── --}}
        <div class="student-section">
            <div class="student-label">Siswa</div>

            @if($selectedStudentId)
                <div class="student-card">
                    <div class="student-card-info">
                        <div class="student-card-name">{{ $selectedStudentName }}</div>
                        <div class="student-card-meta">Kelas {{ $selectedStudentClass }}</div>
                    </div>
                    <div class="student-card-balance">
                        <div class="student-card-balance-label">Saldo</div>
                        <div class="student-card-balance-amount {{ $selectedStudentBalance <= 5000 ? 'low' : '' }}">
                            Rp {{ number_format($selectedStudentBalance, 0, ',', '.') }}
                        </div>
                    </div>
                    <button class="student-card-clear" wire:click="clearStudent" title="Ganti siswa">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:16px;height:16px">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            @else
                <div class="student-search-wrap" x-data="{ open: @entangle('showStudentDropdown') }">
                    <input
                        wire:model.live.debounce.300ms="searchStudent"
                        type="text"
                        placeholder="Cari nama / kelas siswa..."
                        class="student-search"
                        @focus="$wire.set('showStudentDropdown', $wire.searchStudent.length > 0)"
                        autocomplete="off"
                    />
                    @if($showStudentDropdown && $this->studentSearchResults->isNotEmpty())
                    <div class="student-dropdown">
                        @foreach($this->studentSearchResults as $student)
                        <div class="student-option" wire:click="selectStudent({{ $student->id }})">
                            <div>
                                <div class="student-option-name">{{ $student->name }}</div>
                                <div class="student-option-meta">Kelas {{ $student->class }}</div>
                            </div>
                            <div class="student-option-balance">
                                Rp {{ number_format($student->balance, 0, ',', '.') }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @elseif($showStudentDropdown && strlen($searchStudent) > 0)
                    <div class="student-dropdown">
                        <div style="padding:12px;text-align:center;color:#9ca3af;font-size:13px">Siswa tidak ditemukan</div>
                    </div>
                    @endif
                </div>
            @endif
        </div>

        {{-- ── METODE BAYAR ── --}}
        <div class="payment-section">
            <div class="payment-label">Metode Bayar</div>
            <div class="payment-toggle">
                <button
                    wire:click="$set('paymentMethod', 'wallet')"
                    class="pay-btn {{ $paymentMethod === 'wallet' ? 'active-green' : '' }}"
                >
                    💳 Dompet Siswa
                </button>
                <button
                    wire:click="$set('paymentMethod', 'cash')"
                    class="pay-btn {{ $paymentMethod === 'cash' ? 'active' : '' }}"
                >
                    💵 Tunai
                </button>
            </div>
        </div>

        {{-- ── INPUT TUNAI ──
             FIX BUG: wire:model.blur (bukan .live) agar tidak reset
             setiap kali komponen re-render dari server.
             Kembalian dihitung client-side via Alpine agar tetap reaktif. --}}
        @if($paymentMethod === 'cash')
        <div
            class="cash-section"
            x-data="{
                cash: {{ (int) $cashAmount }},
                total: {{ (int) $this->cartTotal() }},
                get kembalian() { return Math.max(0, this.cash - this.total); },
                fmt(n) { return 'Rp ' + Number(n).toLocaleString('id-ID'); }
            }"
        >
            <div class="cash-input-wrap">
                <span class="cash-prefix">Rp</span>
                <input
                    x-model.number="cash"
                    @change="$wire.set('cashAmount', cash)"
                    type="number"
                    placeholder="Uang yang dibayar"
                    class="cash-input"
                    min="0"
                />
            </div>
            <template x-if="cash > 0 && total > 0">
                <div class="kembalian-row">
                    <span class="kembalian-label">Kembalian</span>
                    <span class="kembalian-amount" x-text="fmt(kembalian)"></span>
                </div>
            </template>
        </div>
        @endif

        {{-- ── CART ITEMS ── --}}
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

        {{-- ── FOOTER / CHECKOUT ── --}}
        <div class="cart-footer">
            <div class="cart-total-row">
                <span>Total</span>
                <span class="cart-total-amt">Rp {{ number_format($this->cartTotal(), 0, ',', '.') }}</span>
            </div>

            @if($paymentMethod === 'wallet' && $selectedStudentId && $cart->isNotEmpty())
            <div class="saldo-after-row">
                <span>Sisa saldo</span>
                <span class="saldo-after-val {{ $this->saldoCukup() ? 'saldo-after-ok' : 'saldo-after-low' }}">
                    Rp {{ number_format($this->saldoSetelahBayar(), 0, ',', '.') }}
                </span>
            </div>
            @endif

            @if($paymentMethod === 'wallet' && $selectedStudentId && $cart->isNotEmpty() && !$this->saldoCukup())
            <div class="saldo-warning">
                ⚠ Saldo tidak cukup! Kurang Rp {{ number_format($this->cartTotal() - $selectedStudentBalance, 0, ',', '.') }}
            </div>
            @endif

            <button
                class="btn-checkout {{ $paymentMethod === 'wallet' ? 'wallet-mode' : '' }}"
                wire:click="checkout"
                wire:loading.attr="disabled"
                wire:target="checkout"
                @disabled(
                    $cart->isEmpty() ||
                    ($paymentMethod === 'wallet' && (!$selectedStudentId || !$this->saldoCukup())) ||
                    ($paymentMethod === 'cash' && $cashAmount < $this->cartTotal())
                )
            >
                <span wire:loading.remove wire:target="checkout" style="display:flex;align-items:center;gap:6px">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:20px;height:20px">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75"/>
                    </svg>
                    {{ $paymentMethod === 'wallet' ? 'Bayar — Dompet' : 'Bayar — Tunai' }}
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
                    ],
                });
                this.btStatus = 'Menghubungkan ke ' + device.name + '...';
                const server = await device.gatt.connect();
                let service;
                try { service = await server.getPrimaryService('000018f0-0000-1000-8000-00805f9b34fb'); }
                catch { service = await server.getPrimaryService('0000ff00-0000-1000-8000-00805f9b34fb'); }
                try { this.btCharacteristic = await service.getCharacteristic('00002af1-0000-1000-8000-00805f9b34fb'); }
                catch { this.btCharacteristic = await service.getCharacteristic('0000ff01-0000-1000-8000-00805f9b34fb'); }
                this.btDevice    = device;
                this.btConnected = true;
                this.btStatus    = '✅ Terhubung: ' + device.name;
                device.addEventListener('gattserverdisconnected', () => {
                    this.btConnected      = false;
                    this.btCharacteristic = null;
                    this.btStatus         = '⚠️ Printer terputus';
                });
            } catch (err) {
                this.btConnected = false;
                this.btStatus    = '❌ ' + err.message;
            }
        },

        disconnectBT() {
            if (this.btDevice?.gatt?.connected) this.btDevice.gatt.disconnect();
            this.btConnected      = false;
            this.btCharacteristic = null;
            this.btStatus         = 'Printer diputuskan';
        },

        async printBluetooth() {
            if (!this.btCharacteristic) {
                this.btStatus = '❌ Hubungkan printer terlebih dahulu';
                return;
            }
            try {
                this.btStatus = 'Mencetak struk...';
                const data  = this.buildEscPos();
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
            const center = (s, w = 32) => {
                const sp = Math.max(0, Math.floor((w - s.length) / 2));
                return ' '.repeat(sp) + s;
            };

            // Init printer
            push(ESC, 0x40);

            // ── Nama toko (dari Settings via lastOrder) ──
            push(ESC, 0x61, 1); push(ESC, 0x45, 1); push(GS, 0x21, 0x11);
            line(this.order.store_name || 'Kantin Sekolah');
            push(GS, 0x21, 0); push(ESC, 0x45, 0);

            // Alamat & telepon
            if (this.order.store_address) {
                push(ESC, 0x61, 1);
                line(this.order.store_address.substring(0, 32));
            }
            if (this.order.store_phone) {
                push(ESC, 0x61, 1);
                line('Telp: ' + this.order.store_phone);
            }
            push(ESC, 0x61, 0);

            div();

            // ── Info invoice ──
            line(pad('Invoice:', this.order.invoice_number));
            line(pad('Tanggal:', this.order.created_at));
            if (this.order.student_name) {
                line(pad('Siswa:', (this.order.student_name + ' / Kls ' + this.order.student_class).substring(0, 20)));
            }
            line(pad('Bayar:', this.order.payment_method === 'wallet' ? 'Dompet Siswa' : 'Tunai'));

            div();

            // ── Item produk ──
            for (const item of this.order.items) {
                line(item.name.substring(0, 32));
                line(pad(
                    '  ' + item.quantity + 'x Rp ' + Number(item.price).toLocaleString('id-ID'),
                    'Rp ' + Number(item.price * item.quantity).toLocaleString('id-ID')
                ));
            }

            div();

            // ── Total ──
            push(ESC, 0x45, 1); push(GS, 0x21, 1);
            line(pad('TOTAL:', 'Rp ' + Number(this.order.total_amount).toLocaleString('id-ID')));
            push(GS, 0x21, 0); push(ESC, 0x45, 0);

            // Sisa saldo / kembalian
            if (this.order.payment_method === 'wallet' && this.order.balance_after !== null) {
                line(pad('Sisa Saldo:', 'Rp ' + Number(this.order.balance_after).toLocaleString('id-ID')));
            }
            if (this.order.payment_method === 'cash') {
                line(pad('Bayar:', 'Rp ' + Number(this.order.cash_amount).toLocaleString('id-ID')));
                line(pad('Kembalian:', 'Rp ' + Number(this.order.change_amount).toLocaleString('id-ID')));
            }

            div();

            // ── Footer dari Settings ──
            push(ESC, 0x61, 1);
            const footerLines = (this.order.store_footer || 'Terima kasih!').split('\n');
            for (const fl of footerLines) {
                if (fl.trim()) line(fl.trim().substring(0, 32));
            }
            push(10, 10, 10, 10);

            // Cut kertas
            push(GS, 0x56, 0x41, 0x10);

            return new Uint8Array(b);
        },
    }));
});
</script>
@endpush

</x-filament-panels::page>
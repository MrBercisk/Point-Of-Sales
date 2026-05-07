<x-filament-panels::page>

<style>
/* ── Layout utama ─────────────────────────────────────────────────────── */
.rs-wrap {
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 24px;
    align-items: start;
}

/* ── Panel kiri (form) ────────────────────────────────────────────────── */
.rs-panel {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 16px;
    overflow: hidden;
}
.rs-panel-header {
    padding: 14px 20px;
    border-bottom: 1px solid #f3f4f6;
    display: flex;
    align-items: center;
    gap: 8px;
}
.rs-panel-title {
    font-weight: 700;
    font-size: 14px;
    color: #111827;
}
.rs-panel-body {
    padding: 20px;
}

/* ── Info toko ────────────────────────────────────────────────────────── */
.rs-grid-3 { display: grid; grid-template-columns: repeat(3,1fr); gap: 12px; }
.rs-grid-2 { display: grid; grid-template-columns: repeat(2,1fr); gap: 12px; }
.rs-input-group { display: flex; flex-direction: column; gap: 4px; }
.rs-label { font-size: 12px; font-weight: 600; color: #4b5563; }
.rs-input {
    padding: 8px 12px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    font-size: 13px;
    outline: none;
    width: 100%;
    box-sizing: border-box;
    background: #f9fafb;
}
.rs-input:focus { border-color: #6366f1; background: #fff; box-shadow: 0 0 0 2px #e0e7ff; }
.rs-textarea {
    padding: 8px 12px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    font-size: 13px;
    outline: none;
    width: 100%;
    box-sizing: border-box;
    resize: vertical;
    background: #f9fafb;
    font-family: inherit;
}
.rs-textarea:focus { border-color: #6366f1; background: #fff; box-shadow: 0 0 0 2px #e0e7ff; }

/* ── Layout picker ────────────────────────────────────────────────────── */
.layout-cards { display: grid; grid-template-columns: repeat(3,1fr); gap: 12px; }
.layout-card {
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    padding: 12px;
    cursor: pointer;
    transition: all .15s;
    text-align: center;
    background: #fff;
}
.layout-card:hover { border-color: #a5b4fc; }
.layout-card.selected { border-color: #6366f1; background: #eef2ff; }
.layout-card-icon {
    width: 48px;
    height: 64px;
    margin: 0 auto 8px;
    border: 1px solid currentColor;
    border-radius: 4px;
    display: flex;
    flex-direction: column;
    padding: 4px;
    gap: 2px;
    color: #9ca3af;
}
.layout-card.selected .layout-card-icon { color: #6366f1; }
.lc-bar { background: currentColor; border-radius: 1px; }
.layout-card-name { font-size: 12px; font-weight: 700; color: #374151; }
.layout-card-desc { font-size: 10px; color: #9ca3af; margin-top: 2px; }
.layout-card.selected .layout-card-name { color: #4338ca; }

/* ── Toggle grid ──────────────────────────────────────────────────────── */
.toggle-grid {
    display: grid;
    grid-template-columns: repeat(3,1fr);
    gap: 8px;
}
.toggle-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 8px 12px;
    background: #f9fafb;
    border: 1px solid #f3f4f6;
    border-radius: 8px;
    gap: 8px;
}
.toggle-item-label { font-size: 12px; color: #374151; font-weight: 500; flex: 1; }
.toggle-item.disabled { opacity: .45; pointer-events: none; }

/* Toggle switch */
.ts { position:relative; display:inline-block; width:34px; height:18px; flex-shrink:0; }
.ts input { opacity:0; width:0; height:0; }
.ts-slider {
    position:absolute; cursor:pointer; inset:0;
    background:#d1d5db; border-radius:999px; transition:.2s;
}
.ts-slider:before {
    content:''; position:absolute;
    width:12px; height:12px; left:3px; bottom:3px;
    background:#fff; border-radius:50%; transition:.2s;
}
.ts input:checked + .ts-slider { background:#6366f1; }
.ts input:checked + .ts-slider:before { transform:translateX(16px); }

/* Paper size */
.paper-btns { display:flex; gap:8px; }
.paper-btn {
    flex: 1; padding: 8px; border-radius: 8px; font-size: 12px; font-weight: 600;
    border: 1px solid #e5e7eb; background: #f9fafb; color: #6b7280;
    cursor: pointer; transition: all .15s; text-align: center;
}
.paper-btn.active { background: #6366f1; color: #fff; border-color: #6366f1; }

/* ── Divider ──────────────────────────────────────────────────────────── */
.rs-divider { border: none; border-top: 1px solid #f3f4f6; margin: 16px 0; }

/* ── Save button ──────────────────────────────────────────────────────── */
.rs-save-btn {
    width: 100%; padding: 11px; border-radius: 10px;
    background: #6366f1; color: #fff; font-weight: 700; font-size: 14px;
    border: none; cursor: pointer; transition: all .15s;
    display: flex; align-items: center; justify-content: center; gap: 8px;
}
.rs-save-btn:hover { background: #4f46e5; }

/* ── Panel preview ────────────────────────────────────────────────────── */
.rs-preview-panel {
    position: sticky;
    top: 20px;
    background: #f3f4f6;
    border-radius: 16px;
    padding: 16px;
}
.rs-preview-title {
    font-size: 12px; font-weight: 700; color: #6b7280;
    text-transform: uppercase; letter-spacing: .05em;
    margin-bottom: 12px; text-align: center;
}

/* ── Struk preview ────────────────────────────────────────────────────── */
.receipt-wrap {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 20px rgba(0,0,0,.12);
    overflow: hidden;
    max-width: 260px;
    margin: 0 auto;
    transition: all .2s;
}
.receipt-wrap.paper-58 { max-width: 200px; }

.rp { /* receipt preview */
    font-family: 'Courier New', Courier, monospace;
    font-size: 11px;
    color: #111;
    padding: 12px;
}
.rp-center { text-align: center; }
.rp-store-name { font-size: 13px; font-weight: 700; text-align: center; }
.rp-store-info { font-size: 10px; text-align: center; color: #555; }
.rp-logo {
    width: 48px; height: 48px; background: #e5e7eb;
    border-radius: 50%; margin: 0 auto 6px;
    display: flex; align-items: center; justify-content: center;
    font-size: 9px; color: #9ca3af; font-weight: 700;
}
.rp-divider { border-top: 1px dashed #ccc; margin: 6px 0; }
.rp-row { display: flex; justify-content: space-between; font-size: 10px; padding: 1px 0; }
.rp-row-label { color: #666; }
.rp-items { margin: 4px 0; }
.rp-item { margin: 3px 0; }
.rp-item-name { font-weight: 600; font-size: 11px; }
.rp-item-detail { font-size: 9px; color: #666; }
.rp-item-price { text-align: right; font-size: 10px; }

/* Layout: compact — item dalam 1 baris */
.rp.compact .rp-item { display: flex; justify-content: space-between; }
.rp.compact .rp-item-detail { display: none; }
.rp.compact .rp-item-name { font-size: 10px; }

/* Layout: detailed — tambah kolom qty & harga */
.rp.detailed .rp-items-header {
    display: grid; grid-template-columns: 1fr auto auto;
    font-size: 9px; font-weight: 700; color: #666;
    border-bottom: 1px solid #eee; padding-bottom: 2px; margin-bottom: 2px;
    gap: 4px;
}
.rp.detailed .rp-item {
    display: grid; grid-template-columns: 1fr auto auto;
    gap: 4px; align-items: start;
}
.rp.detailed .rp-item-name { font-size: 10px; }
.rp.detailed .rp-item-qty { font-size: 10px; text-align: center; }
.rp.detailed .rp-item-price { font-size: 10px; }

.rp-total { display:flex; justify-content:space-between; font-weight:700; font-size:13px; margin-top:4px; }
.rp-summary-row { display:flex; justify-content:space-between; font-size:10px; padding:1px 0; }
.rp-footer { text-align:center; font-size:9px; color:#666; margin-top:6px; line-height:1.4; }
.rp-barcode {
    text-align:center; margin-top:6px;
    font-size:8px; color:#555;
    border: 1px dashed #ccc; padding: 4px; border-radius: 4px;
}

/* ── Dark mode ────────────────────────────────────────────────────────── */
.dark .rs-panel { background:#1f2937; border-color:#374151; }
.dark .rs-panel-header { border-color:#374151; }
.dark .rs-panel-title { color:#f9fafb; }
.dark .rs-input,.dark .rs-textarea { background:#374151; border-color:#4b5563; color:#f9fafb; }
.dark .rs-label,.dark .toggle-item-label { color:#d1d5db; }
.dark .toggle-item { background:#374151; border-color:#4b5563; }
.dark .layout-card { background:#374151; border-color:#4b5563; }
.dark .layout-card.selected { background:#312e81; border-color:#6366f1; }
.dark .paper-btn { background:#374151; border-color:#4b5563; color:#d1d5db; }
.dark .rs-preview-panel { background:#111827; }
.dark .receipt-wrap { background:#1f2937; }
.dark .rp { color:#f9fafb; }
.dark .rp-store-info,.dark .rp-row-label,.dark .rp-item-detail { color:#9ca3af; }
</style>

<div class="rs-wrap">

    {{-- ══ PANEL KIRI: FORM ══════════════════════════════════════════════ --}}
    <div style="display:flex;flex-direction:column;gap:16px;">

        {{-- ── Info Toko ── --}}
        <div class="rs-panel">
            <div class="rs-panel-header">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#6366f1" style="width:18px;height:18px">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016 2.993 2.993 0 002.25-1.016 3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z"/>
                </svg>
                <span class="rs-panel-title">Informasi Toko</span>
            </div>
            <div class="rs-panel-body">
                <div class="rs-grid-3" style="margin-bottom:12px;">
                    <div class="rs-input-group" style="grid-column:span 2;">
                        <label class="rs-label">Nama Toko / Kantin</label>
                        <input wire:model.blur="receipt_store_name" type="text" class="rs-input" placeholder="Kantin SD Muhammadiyah"/>
                    </div>
                    <div class="rs-input-group">
                        <label class="rs-label">Telepon</label>
                        <input wire:model.blur="receipt_store_phone" type="text" class="rs-input" placeholder="08123456789"/>
                    </div>
                </div>
                <div class="rs-input-group" style="margin-bottom:12px;">
                    <label class="rs-label">Alamat</label>
                    <input wire:model.blur="receipt_store_address" type="text" class="rs-input" placeholder="Jl. Contoh No. 1, Kota"/>
                </div>
                <div class="rs-input-group">
                    <label class="rs-label">Pesan Footer Struk</label>
                    <textarea wire:model.blur="receipt_footer" rows="2" class="rs-textarea" placeholder="Terima kasih telah berbelanja!&#10;Selamat belajar 🎒"></textarea>
                    <span style="font-size:10px;color:#9ca3af;">Pisahkan dengan Enter untuk baris baru.</span>
                </div>
            </div>
        </div>

        {{-- ── Layout Struk ── --}}
        <div class="rs-panel">
            <div class="rs-panel-header">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#6366f1" style="width:18px;height:18px">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/>
                </svg>
                <span class="rs-panel-title">Layout Struk</span>
            </div>
            <div class="rs-panel-body">
                <div class="layout-cards">
                    {{-- Standard --}}
                    <div class="layout-card {{ $receipt_layout === 'standard' ? 'selected' : '' }}"
                         wire:click="$set('receipt_layout','standard')">
                        <div class="layout-card-icon">
                            <div class="lc-bar" style="height:5px;opacity:.5"></div>
                            <div class="lc-bar" style="height:3px;width:60%"></div>
                            <div class="lc-bar" style="height:3px;width:80%"></div>
                            <div class="lc-bar" style="height:1px;opacity:.3"></div>
                            <div class="lc-bar" style="height:3px"></div>
                            <div class="lc-bar" style="height:3px"></div>
                            <div class="lc-bar" style="height:3px"></div>
                            <div class="lc-bar" style="height:1px;opacity:.3"></div>
                            <div class="lc-bar" style="height:4px;opacity:.7"></div>
                        </div>
                        <div class="layout-card-name">Standard</div>
                        <div class="layout-card-desc">Nama produk + harga<br>per item</div>
                    </div>

                    {{-- Compact --}}
                    <div class="layout-card {{ $receipt_layout === 'compact' ? 'selected' : '' }}"
                         wire:click="$set('receipt_layout','compact')">
                        <div class="layout-card-icon">
                            <div class="lc-bar" style="height:4px;opacity:.5"></div>
                            <div class="lc-bar" style="height:3px;width:70%"></div>
                            <div class="lc-bar" style="height:1px;opacity:.3"></div>
                            <div class="lc-bar" style="height:2px"></div>
                            <div class="lc-bar" style="height:2px"></div>
                            <div class="lc-bar" style="height:2px"></div>
                            <div class="lc-bar" style="height:2px"></div>
                            <div class="lc-bar" style="height:1px;opacity:.3"></div>
                            <div class="lc-bar" style="height:3px;opacity:.7"></div>
                        </div>
                        <div class="layout-card-name">Compact</div>
                        <div class="layout-card-desc">Ringkas, 1 baris<br>per item</div>
                    </div>

                    {{-- Detailed --}}
                    <div class="layout-card {{ $receipt_layout === 'detailed' ? 'selected' : '' }}"
                         wire:click="$set('receipt_layout','detailed')">
                        <div class="layout-card-icon">
                            <div class="lc-bar" style="height:4px;opacity:.5"></div>
                            <div class="lc-bar" style="height:3px;width:75%"></div>
                            <div class="lc-bar" style="height:1px;opacity:.3"></div>
                            <div style="display:flex;gap:1px">
                                <div class="lc-bar" style="flex:1;height:2px"></div>
                                <div class="lc-bar" style="width:6px;height:2px"></div>
                                <div class="lc-bar" style="width:8px;height:2px"></div>
                            </div>
                            <div style="display:flex;gap:1px">
                                <div class="lc-bar" style="flex:1;height:2px;opacity:.6"></div>
                                <div class="lc-bar" style="width:6px;height:2px;opacity:.6"></div>
                                <div class="lc-bar" style="width:8px;height:2px;opacity:.6"></div>
                            </div>
                            <div style="display:flex;gap:1px">
                                <div class="lc-bar" style="flex:1;height:2px;opacity:.6"></div>
                                <div class="lc-bar" style="width:6px;height:2px;opacity:.6"></div>
                                <div class="lc-bar" style="width:8px;height:2px;opacity:.6"></div>
                            </div>
                            <div class="lc-bar" style="height:1px;opacity:.3"></div>
                            <div class="lc-bar" style="height:3px;opacity:.8"></div>
                        </div>
                        <div class="layout-card-name">Detailed</div>
                        <div class="layout-card-desc">Tabel lengkap<br>Qty · Harga · Total</div>
                    </div>
                </div>

                <hr class="rs-divider">

                <div class="rs-label" style="margin-bottom:8px;">Ukuran Kertas</div>
                <div class="paper-btns">
                    <button class="paper-btn {{ $receipt_paper_size === '58mm' ? 'active' : '' }}"
                            wire:click="$set('receipt_paper_size','58mm')">
                        58mm
                    </button>
                    <button class="paper-btn {{ $receipt_paper_size === '80mm' ? 'active' : '' }}"
                            wire:click="$set('receipt_paper_size','80mm')">
                        80mm <span style="font-size:10px;opacity:.7">(standar)</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- ── Visibilitas Elemen ── --}}
        <div class="rs-panel">
            <div class="rs-panel-header">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#6366f1" style="width:18px;height:18px">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span class="rs-panel-title">Tampilkan / Sembunyikan Elemen</span>
            </div>
            <div class="rs-panel-body">

                {{-- Header toko --}}
                <div class="rs-label" style="margin-bottom:8px;color:#6b7280;">Header Toko</div>
                <div class="toggle-grid" style="margin-bottom:16px;">
                    @foreach([
                        ['receipt_show_logo',        'Logo Toko'],
                        ['receipt_show_store_name',  'Nama Toko'],
                        ['receipt_show_address',     'Alamat'],
                        ['receipt_show_phone',       'Telepon'],
                    ] as [$field, $label])
                    <div class="toggle-item">
                        <span class="toggle-item-label">{{ $label }}</span>
                        <label class="ts">
                            <input type="checkbox" wire:model.live="{{ $field }}">
                            <span class="ts-slider"></span>
                        </label>
                    </div>
                    @endforeach
                </div>

                {{-- Info transaksi --}}
                <div class="rs-label" style="margin-bottom:8px;color:#6b7280;">Info Transaksi</div>
                <div class="toggle-grid" style="margin-bottom:16px;">
                    @foreach([
                        ['receipt_show_invoice_number', 'No. Invoice'],
                        ['receipt_show_date',           'Tanggal & Jam'],
                        ['receipt_show_student',        'Nama Siswa'],
                        ['receipt_show_payment_method', 'Metode Bayar'],
                        ['receipt_show_cashier',        'Nama Kasir'],
                    ] as [$field, $label])
                    <div class="toggle-item">
                        <span class="toggle-item-label">{{ $label }}</span>
                        <label class="ts">
                            <input type="checkbox" wire:model.live="{{ $field }}">
                            <span class="ts-slider"></span>
                        </label>
                    </div>
                    @endforeach
                </div>

                {{-- Item & harga --}}
                <div class="rs-label" style="margin-bottom:8px;color:#6b7280;">Item & Harga</div>
                <div class="toggle-grid" style="margin-bottom:16px;">
                    @foreach([
                        ['receipt_show_item_price',        'Harga Satuan'],
                        ['receipt_show_subtotal_per_item', 'Subtotal Item'],
                    ] as [$field, $label])
                    <div class="toggle-item {{ $receipt_layout === 'compact' && $field === 'receipt_show_subtotal_per_item' ? 'disabled' : '' }}">
                        <span class="toggle-item-label">{{ $label }}</span>
                        <label class="ts">
                            <input type="checkbox" wire:model.live="{{ $field }}">
                            <span class="ts-slider"></span>
                        </label>
                    </div>
                    @endforeach
                </div>

                {{-- Ringkasan pembayaran --}}
                <div class="rs-label" style="margin-bottom:8px;color:#6b7280;">Ringkasan Pembayaran</div>
                <div class="toggle-grid" style="margin-bottom:16px;">
                    @foreach([
                        ['receipt_show_change',        'Kembalian Tunai'],
                        ['receipt_show_balance_after', 'Sisa Saldo'],
                    ] as [$field, $label])
                    <div class="toggle-item">
                        <span class="toggle-item-label">{{ $label }}</span>
                        <label class="ts">
                            <input type="checkbox" wire:model.live="{{ $field }}">
                            <span class="ts-slider"></span>
                        </label>
                    </div>
                    @endforeach
                </div>

                {{-- Lainnya --}}
                <div class="rs-label" style="margin-bottom:8px;color:#6b7280;">Lainnya</div>
                <div class="toggle-grid">
                    @foreach([
                        ['receipt_show_footer',  'Pesan Footer'],
                        ['receipt_show_barcode', 'Barcode Invoice'],
                    ] as [$field, $label])
                    <div class="toggle-item">
                        <span class="toggle-item-label">{{ $label }}</span>
                        <label class="ts">
                            <input type="checkbox" wire:model.live="{{ $field }}">
                            <span class="ts-slider"></span>
                        </label>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ── Tombol Simpan ── --}}
        <button class="rs-save-btn" wire:click="save" wire:loading.attr="disabled" wire:target="save">
            <span wire:loading.remove wire:target="save">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:18px;height:18px;display:inline;vertical-align:middle;margin-right:4px">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                </svg>
                Simpan Pengaturan
            </span>
            <span wire:loading wire:target="save">Menyimpan...</span>
        </button>

    </div>

    {{-- ══ PANEL KANAN: LIVE PREVIEW ══════════════════════════════════════ --}}
    <div class="rs-preview-panel">
        <div class="rs-preview-title">
            Preview Struk
            <span style="font-weight:400;text-transform:none;font-size:11px;display:block;margin-top:2px;">
                Tampilan berubah real-time
            </span>
        </div>

        @php $d = $this->getPreviewData(); @endphp

        <div class="receipt-wrap {{ $receipt_paper_size === '58mm' ? 'paper-58' : '' }}">
            <div class="rp {{ $receipt_layout }}">

                {{-- Logo --}}
                @if($receipt_show_logo)
                <div class="rp-logo">LOGO</div>
                @endif

                {{-- Nama toko --}}
                @if($receipt_show_store_name)
                <div class="rp-store-name">{{ $receipt_store_name ?: 'Kantin Sekolah' }}</div>
                @endif

                @if($receipt_show_address && $receipt_store_address)
                <div class="rp-store-info">{{ $receipt_store_address }}</div>
                @endif

                @if($receipt_show_phone && $receipt_store_phone)
                <div class="rp-store-info">Telp: {{ $receipt_store_phone }}</div>
                @endif

                <div class="rp-divider"></div>

                {{-- Info transaksi --}}
                @if($receipt_show_invoice_number)
                <div class="rp-row"><span class="rp-row-label">Invoice</span><span>{{ $d['invoice_number'] }}</span></div>
                @endif

                @if($receipt_show_date)
                <div class="rp-row"><span class="rp-row-label">Tanggal</span><span>{{ $d['created_at'] }}</span></div>
                @endif

                @if($receipt_show_student)
                <div class="rp-row"><span class="rp-row-label">Siswa</span><span>{{ $d['student_name'] }} ({{ $d['student_class'] }})</span></div>
                @endif

                @if($receipt_show_payment_method)
                <div class="rp-row"><span class="rp-row-label">Bayar</span><span>Dompet Siswa</span></div>
                @endif

                @if($receipt_show_cashier)
                <div class="rp-row"><span class="rp-row-label">Kasir</span><span>Admin</span></div>
                @endif

                <div class="rp-divider"></div>

                {{-- Items --}}
                <div class="rp-items">
                    @if($receipt_layout === 'detailed')
                    <div class="rp-items-header">
                        <span>Item</span><span>Qty</span><span>Total</span>
                    </div>
                    @endif

                    @foreach($d['items'] as $item)
                    @if($receipt_layout === 'standard')
                        <div class="rp-item">
                            <div class="rp-item-name">{{ $item['name'] }}</div>
                            @if($receipt_show_item_price)
                            <div class="rp-item-detail">{{ $item['quantity'] }}× Rp {{ number_format($item['price'], 0, ',', '.') }}</div>
                            @endif
                            @if($receipt_show_subtotal_per_item)
                            <div class="rp-item-price" style="text-align:right">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</div>
                            @endif
                        </div>
                    @elseif($receipt_layout === 'compact')
                        <div class="rp-item">
                            <span class="rp-item-name">{{ $item['name'] }}</span>
                            <span style="font-size:10px">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                        </div>
                    @else {{-- detailed --}}
                        <div class="rp-item">
                            <span class="rp-item-name">{{ $item['name'] }}</span>
                            <span class="rp-item-qty">{{ $item['quantity'] }}</span>
                            <span class="rp-item-price">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                        </div>
                    @endif
                    @endforeach
                </div>

                <div class="rp-divider"></div>

                {{-- Total --}}
                <div class="rp-total">
                    <span>TOTAL</span>
                    <span>Rp {{ number_format($d['total_amount'], 0, ',', '.') }}</span>
                </div>

                @if($receipt_show_balance_after)
                <div class="rp-summary-row" style="color:#15803d;">
                    <span>Sisa Saldo</span>
                    <span>Rp {{ number_format($d['balance_after'], 0, ',', '.') }}</span>
                </div>
                @endif

                @if($receipt_show_change)
                <div class="rp-summary-row">
                    <span>Bayar Tunai</span>
                    <span>Rp {{ number_format($d['cash_amount'], 0, ',', '.') }}</span>
                </div>
                <div class="rp-summary-row">
                    <span>Kembalian</span>
                    <span>Rp {{ number_format($d['change_amount'], 0, ',', '.') }}</span>
                </div>
                @endif

                {{-- Footer --}}
                @if($receipt_show_footer && $receipt_footer)
                <div class="rp-divider"></div>
                <div class="rp-footer">
                    @foreach(explode("\n", $receipt_footer) as $line)
                        @if(trim($line))<p>{{ trim($line) }}</p>@endif
                    @endforeach
                </div>
                @endif

                {{-- Barcode --}}
                @if($receipt_show_barcode)
                <div class="rp-barcode">
                    <div style="font-family:monospace;font-size:16px;letter-spacing:-1px;">||| || ||| | || |||</div>
                    <div>{{ $d['invoice_number'] }}</div>
                </div>
                @endif

            </div>
        </div>

        {{-- Label layout aktif --}}
        <div style="text-align:center;margin-top:8px;font-size:11px;color:#6b7280;">
            Layout: <strong>{{ ucfirst($receipt_layout) }}</strong> ·
            Kertas: <strong>{{ $receipt_paper_size }}</strong>
        </div>
    </div>

</div>

</x-filament-panels::page>
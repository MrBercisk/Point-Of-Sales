
    <div>
<style>
/* ══════════════════════════════════════════════════
   RESET & ROOT
═══════════════════════════════════════════════ */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
    --p:        #16a34a;
    --p-dark:   #15803d;
    --p-light:  #dcfce7;
    --p-text:   #166534;
    --p-muted:  #4ade80;
    --p-ring:   rgba(22,163,74,.2);

    --bg:       #f0fdf4;
    --surface:  #ffffff;
    --border:   #e5e7eb;
    --border-s: #d1d5db;

    --tx:       #111827;
    --tx-2:     #374151;
    --tx-3:     #6b7280;
    --tx-4:     #9ca3af;

    --danger:   #ef4444;
    --warn:     #f59e0b;
    --info:     #3b82f6;

    --r:        10px;
    --r-sm:     6px;
    --r-lg:     14px;
    --shadow:   0 1px 3px rgba(0,0,0,.08), 0 1px 2px rgba(0,0,0,.06);
    --shadow-md:0 4px 12px rgba(0,0,0,.1);
}

/* Dark mode overrides */
.dark {
    --bg:      #052e16;
    --surface: #14532d;
    --border:  #166534;
    --border-s:#15803d;
    --tx:      #f0fdf4;
    --tx-2:    #dcfce7;
    --tx-3:    #86efac;
    --tx-4:    #4ade80;
}

html, body { height: 100%; overflow: hidden; background: var(--bg); }

/* ══════════════════════════════════════════════════
   TOP BAR
══════════════════════════════════════════════════ */
.pos-topbar {
    height: 52px;
    background: var(--p);
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 20px;
    flex-shrink: 0;
    box-shadow: 0 2px 8px rgba(0,0,0,.15);
}
.tb-left { display: flex; align-items: center; gap: 12px; }
.tb-brand {
    display: flex; align-items: center; gap: 10px;
    color: #fff; font-weight: 700; font-size: 16px;
}
.tb-logo {
    width: 32px; height: 32px;
    background: rgba(255,255,255,.2);
    border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    font-size: 16px;
}
.tb-divider { width: 1px; height: 24px; background: rgba(255,255,255,.25); }
.tb-register {
    display: flex; align-items: center; gap: 6px;
    color: rgba(255,255,255,.85); font-size: 13px;
}
.tb-dot { width: 7px; height: 7px; background: #86efac; border-radius: 50%; flex-shrink: 0; box-shadow: 0 0 0 2px rgba(134,239,172,.3); }
.tb-right { display: flex; align-items: center; gap: 8px; }
.tb-btn {
    height: 30px; padding: 0 12px;
    border-radius: 6px; border: 1px solid rgba(255,255,255,.25);
    background: rgba(255,255,255,.12);
    color: #fff; font-size: 12px; font-weight: 500;
    cursor: pointer; display: flex; align-items: center; gap: 6px;
    transition: background .15s;
    text-decoration: none;
}
.tb-btn:hover { background: rgba(255,255,255,.22); }
.tb-btn svg { width: 14px; height: 14px; }
.tb-user {
    height: 30px; padding: 0 10px;
    border-radius: 6px; border: none;
    background: rgba(255,255,255,.15);
    color: rgba(255,255,255,.9); font-size: 12px;
    display: flex; align-items: center; gap: 6px;
}
.tb-avatar {
    width: 22px; height: 22px; border-radius: 50%;
    background: rgba(255,255,255,.3);
    display: flex; align-items: center; justify-content: center;
    font-size: 11px; font-weight: 700; color: var(--p-dark);
}

/* ══════════════════════════════════════════════════
   MAIN LAYOUT
══════════════════════════════════════════════════ */
.pos-wrap {
    display: flex;
    height: calc(100vh - 52px);
    overflow: hidden;
}

/* ══════════════════════════════════════════════════
   LEFT — PRODUK
══════════════════════════════════════════════════ */
.pos-left {
    flex: 1;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    background: var(--bg);
}

/* Toolbar */
.prod-toolbar {
    padding: 12px 16px;
    background: var(--surface);
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: center;
    gap: 10px;
    flex-shrink: 0;
}
.search-wrap { flex: 1; position: relative; }
.search-wrap svg {
    position: absolute; left: 10px; top: 50%; transform: translateY(-50%);
    width: 15px; height: 15px; color: var(--tx-4);
}
.search-input {
    width: 100%; padding: 8px 10px 8px 32px;
    border: 1px solid var(--border); border-radius: var(--r-sm);
    background: var(--bg); color: var(--tx);
    font-size: 13px; outline: none;
    transition: border-color .15s, box-shadow .15s;
}
.search-input:focus { border-color: var(--p); box-shadow: 0 0 0 3px var(--p-ring); }
.search-input::placeholder { color: var(--tx-4); }

.barcode-btn {
    width: 36px; height: 36px; flex-shrink: 0;
    border: 1px solid var(--border); border-radius: var(--r-sm);
    background: var(--bg); color: var(--tx-3);
    display: flex; align-items: center; justify-content: center; cursor: pointer;
    transition: all .15s;
}
.barcode-btn:hover { border-color: var(--p); color: var(--p); background: var(--p-light); }
.barcode-btn svg { width: 16px; height: 16px; }

/* Category tabs */
.cat-bar {
    display: flex; align-items: center; gap: 6px;
    padding: 10px 16px;
    background: var(--surface);
    border-bottom: 1px solid var(--border);
    overflow-x: auto; flex-shrink: 0;
    scrollbar-width: none;
}
.cat-bar::-webkit-scrollbar { display: none; }
.cat-pill {
    padding: 5px 14px; border-radius: 99px;
    font-size: 12px; font-weight: 500; white-space: nowrap;
    border: 1px solid var(--border); background: var(--bg);
    color: var(--tx-3); cursor: pointer; transition: all .15s; flex-shrink: 0;
}
.cat-pill:hover { border-color: var(--p); color: var(--p); }
.cat-pill.active {
    background: var(--p); color: #fff; border-color: var(--p);
    box-shadow: 0 2px 6px var(--p-ring);
}

/* Product grid */
.prod-body { flex: 1; overflow-y: auto; padding: 14px 16px; }
.prod-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(148px, 1fr));
    gap: 10px;
    align-content: start;
}

.prod-card {
    background: var(--surface);
    border: 1.5px solid var(--border);
    border-radius: var(--r);
    cursor: pointer;
    overflow: hidden;
    transition: all .15s;
    text-align: left;
    width: 100%;
    position: relative;
}
.prod-card:hover {
    border-color: var(--p);
    box-shadow: 0 0 0 3px var(--p-ring), var(--shadow-md);
    transform: translateY(-1px);
}
.prod-card.in-cart { border-color: var(--p); background: #f0fdf4; }

.pc-img-wrap {
    width: 100%; aspect-ratio: 1;
    background: #f3f4f6; position: relative;
    display: flex; align-items: center; justify-content: center;
    overflow: hidden;
}
.pc-img-wrap img { width: 100%; height: 100%; object-fit: cover; }
.pc-img-placeholder { color: #d1d5db; }
.pc-img-placeholder svg { width: 36px; height: 36px; }

.pc-cart-badge {
    position: absolute; top: 7px; right: 7px;
    width: 20px; height: 20px; border-radius: 50%;
    background: var(--p); color: #fff;
    display: flex; align-items: center; justify-content: center;
    font-size: 11px; font-weight: 700;
    opacity: 0; transition: opacity .15s;
}
.prod-card.in-cart .pc-cart-badge { opacity: 1; }

.pc-body { padding: 9px 10px 11px; }
.pc-name {
    font-size: 12px; font-weight: 600; color: var(--tx);
    line-height: 1.35; margin-bottom: 2px;
    display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
}
.pc-sku { font-size: 10px; color: var(--tx-4); margin-bottom: 4px; }
.pc-stock { font-size: 10px; color: var(--tx-3); margin-bottom: 6px; }
.pc-stock strong { color: var(--p); }
.pc-footer { display: flex; align-items: center; justify-content: space-between; }
.pc-price { font-size: 13px; font-weight: 700; color: var(--tx); }
.pc-add-btn {
    width: 26px; height: 26px; border-radius: 7px;
    background: var(--p); border: none; color: #fff;
    font-size: 18px; line-height: 1; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    transition: background .15s, transform .1s;
}
.pc-add-btn:hover { background: var(--p-dark); }
.pc-add-btn:active { transform: scale(.9); }

.prod-empty {
    grid-column: 1 / -1;
    text-align: center; padding: 60px 20px; color: var(--tx-4);
}
.prod-empty svg { width: 48px; height: 48px; margin: 0 auto 12px; display: block; }
.prod-empty p { font-size: 14px; }

/* Bottom bar */
.prod-footer {
    display: flex; align-items: center; justify-content: space-between;
    padding: 10px 16px;
    background: var(--surface); border-top: 1px solid var(--border);
    flex-shrink: 0;
}
.pf-info { font-size: 12px; color: var(--tx-3); }
.pf-info strong { color: var(--tx); }
.pf-actions { display: flex; gap: 6px; }
.pf-btn {
    height: 30px; padding: 0 12px;
    border: 1px solid var(--border); border-radius: var(--r-sm);
    background: var(--bg); color: var(--tx-3);
    font-size: 12px; font-weight: 500; cursor: pointer;
    display: flex; align-items: center; gap: 5px; transition: all .15s;
}
.pf-btn:hover { border-color: var(--p); color: var(--p); background: var(--p-light); }
.pf-btn svg { width: 13px; height: 13px; }
.pf-btn.hold { color: #b45309; border-color: #fde68a; background: #fffbeb; }
.pf-btn.hold:hover { border-color: var(--warn); }

/* ══════════════════════════════════════════════════
   RIGHT — CART
══════════════════════════════════════════════════ */
.pos-cart {
    width: 360px; min-width: 320px;
    background: var(--surface);
    border-left: 1px solid var(--border);
    display: flex; flex-direction: column;
    overflow: hidden; flex-shrink: 0;
}

/* Cart header */
.cart-header {
    padding: 12px 16px;
    border-bottom: 1px solid var(--border);
    display: flex; align-items: center; justify-content: space-between;
    flex-shrink: 0;
}
.ch-left { display: flex; align-items: center; gap: 8px; }
.ch-title { font-size: 15px; font-weight: 700; color: var(--tx); display: flex; align-items: center; gap: 7px; }
.ch-title svg { width: 18px; height: 18px; color: var(--p); }
.ch-badge {
    background: var(--p); color: #fff;
    font-size: 11px; font-weight: 700;
    padding: 2px 8px; border-radius: 99px;
}
.ch-clear {
    font-size: 12px; color: var(--danger);
    background: none; border: none; cursor: pointer;
    padding: 3px 8px; border-radius: var(--r-sm);
    transition: background .15s;
}
.ch-clear:hover { background: #fef2f2; }

/* Student section */
.cart-student {
    padding: 10px 16px;
    border-bottom: 1px solid var(--border);
    flex-shrink: 0;
}
.section-label {
    font-size: 10px; font-weight: 600; color: var(--tx-4);
    text-transform: uppercase; letter-spacing: .07em; margin-bottom: 7px;
}
.stu-search-wrap { position: relative; }
.stu-input {
    width: 100%; padding: 8px 10px 8px 32px;
    border: 1px solid var(--border); border-radius: var(--r-sm);
    background: #f9fafb; color: var(--tx); font-size: 13px; outline: none;
    transition: border-color .15s, box-shadow .15s;
}
.stu-input:focus { border-color: var(--p); box-shadow: 0 0 0 3px var(--p-ring); background: #fff; }
.stu-input::placeholder { color: var(--tx-4); }
.stu-icon { position: absolute; left: 9px; top: 50%; transform: translateY(-50%); width: 14px; height: 14px; color: var(--tx-4); }
.stu-dropdown {
    position: absolute; top: calc(100% + 4px); left: 0; right: 0; z-index: 50;
    background: #fff; border: 1px solid var(--border); border-radius: var(--r);
    box-shadow: var(--shadow-md);
    max-height: 200px; overflow-y: auto;
}
.stu-option {
    padding: 9px 12px; cursor: pointer; font-size: 13px;
    display: flex; justify-content: space-between; align-items: center;
    transition: background .1s;
}
.stu-option:hover { background: var(--p-light); }
.stu-opt-name { font-weight: 500; color: var(--tx); font-size: 13px; }
.stu-opt-meta { font-size: 11px; color: var(--tx-4); margin-top: 1px; }
.stu-opt-bal { font-size: 12px; font-weight: 600; color: var(--p-dark); flex-shrink: 0; }

/* Student card (setelah dipilih) */
.stu-card {
    background: var(--p-light);
    border: 1px solid #bbf7d0;
    border-radius: var(--r-sm);
    padding: 9px 12px;
    display: flex; align-items: center; gap: 10px;
}
.stu-card-avatar {
    width: 36px; height: 36px; border-radius: 50%;
    background: var(--p); color: #fff;
    display: flex; align-items: center; justify-content: center;
    font-size: 13px; font-weight: 700; flex-shrink: 0;
}
.stu-card-info { flex: 1; min-width: 0; }
.stu-card-name { font-size: 13px; font-weight: 600; color: var(--p-text); }
.stu-card-meta { font-size: 11px; color: #4ade80; }
.stu-card-bal { text-align: right; flex-shrink: 0; }
.stu-card-bal-label { font-size: 10px; color: var(--p-dark); }
.stu-card-bal-amount { font-size: 15px; font-weight: 700; color: var(--p-dark); }
.stu-card-bal-amount.low { color: var(--danger); }
.stu-clear {
    background: none; border: none; cursor: pointer; color: #86efac;
    padding: 4px; border-radius: 4px; transition: color .15s;
}
.stu-clear:hover { color: var(--danger); }
.stu-clear svg { width: 14px; height: 14px; }

/* Payment method */
.cart-payment {
    padding: 10px 16px;
    border-bottom: 1px solid var(--border);
    flex-shrink: 0;
}
.pay-toggle { display: flex; gap: 6px; }
.pay-opt {
    flex: 1; padding: 7px 10px; border-radius: var(--r-sm);
    border: 1.5px solid var(--border); background: var(--bg);
    color: var(--tx-3); font-size: 12px; font-weight: 600;
    cursor: pointer; transition: all .15s; text-align: center;
    display: flex; align-items: center; justify-content: center; gap: 5px;
}
.pay-opt:hover { border-color: var(--p); color: var(--p-text); }
.pay-opt.active {
    background: var(--p); color: #fff; border-color: var(--p);
    box-shadow: 0 2px 6px var(--p-ring);
}
.pay-opt svg { width: 14px; height: 14px; }

/* Cash input */
.cart-cash {
    padding: 8px 16px;
    border-bottom: 1px solid var(--border);
    flex-shrink: 0;
}
.cash-wrap { position: relative; }
.cash-prefix {
    position: absolute; left: 10px; top: 50%; transform: translateY(-50%);
    font-size: 13px; color: var(--tx-3); font-weight: 500; pointer-events: none;
}
.cash-inp {
    width: 100%; padding: 8px 12px 8px 36px;
    border: 1px solid var(--border); border-radius: var(--r-sm);
    background: var(--bg); color: var(--tx); font-size: 14px; font-weight: 600;
    outline: none; transition: border-color .15s, box-shadow .15s;
}
.cash-inp:focus { border-color: var(--p); box-shadow: 0 0 0 3px var(--p-ring); }
.kem-row {
    display: flex; justify-content: space-between;
    font-size: 12px; margin-top: 5px;
}
.kem-label { color: var(--tx-3); }
.kem-amount { font-weight: 700; color: var(--p-dark); }

/* Cart items */
.cart-items { flex: 1; overflow-y: auto; padding: 8px 16px; }
.cart-empty {
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    padding: 48px 0; color: var(--tx-4); gap: 10px;
}
.cart-empty svg { width: 44px; height: 44px; }
.cart-empty p { font-size: 13px; }

.cart-item {
    display: flex; align-items: flex-start; gap: 10px;
    padding: 10px 0; border-bottom: 1px solid #f3f4f6;
}
.cart-item:last-child { border-bottom: none; }
.ci-thumb {
    width: 40px; height: 40px; border-radius: 8px;
    background: #f3f4f6; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center; overflow: hidden;
}
.ci-thumb img { width: 100%; height: 100%; object-fit: cover; }
.ci-thumb svg { width: 18px; height: 18px; color: #d1d5db; }
.ci-info { flex: 1; min-width: 0; }
.ci-name { font-size: 13px; font-weight: 500; color: var(--tx); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.ci-unit { font-size: 11px; color: var(--tx-4); margin-top: 2px; }
.ci-right { display: flex; flex-direction: column; align-items: flex-end; gap: 6px; flex-shrink: 0; }
.ci-qty-row { display: flex; align-items: center; gap: 4px; }
.qty-btn {
    width: 24px; height: 24px; border-radius: 6px;
    border: 1px solid var(--border); background: var(--bg);
    color: var(--tx-3); font-size: 15px;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; transition: all .15s; line-height: 1;
}
.qty-btn:hover { border-color: var(--p); color: var(--p); background: var(--p-light); }
.qty-num { width: 26px; text-align: center; font-size: 13px; font-weight: 700; color: var(--tx); }
.ci-sub { font-size: 12px; font-weight: 600; color: var(--p-dark); }

/* Cart footer */
.cart-footer {
    padding: 14px 16px;
    border-top: 1px solid var(--border);
    flex-shrink: 0;
}
.cf-rows { margin-bottom: 10px; }
.cf-row {
    display: flex; justify-content: space-between; align-items: center;
    font-size: 12px; color: var(--tx-3); padding: 2px 0;
}
.cf-row.total {
    font-size: 16px; font-weight: 700; color: var(--tx);
    padding: 8px 0 0; margin-top: 4px;
    border-top: 1px dashed var(--border);
}
.cf-total-amt { color: var(--p-dark); font-size: 18px; }
.cf-saldo-row {
    display: flex; justify-content: space-between; align-items: center;
    font-size: 12px; padding: 5px 8px; border-radius: var(--r-sm);
    background: var(--p-light); margin-top: 5px;
}
.cf-saldo-row.low { background: #fef2f2; }
.cf-saldo-label { color: var(--p-text); }
.cf-saldo-label.low { color: #b91c1c; }
.cf-saldo-val { font-weight: 600; color: var(--p-dark); }
.cf-saldo-val.low { color: var(--danger); }

.saldo-warn {
    background: #fef2f2; border: 1px solid #fecaca;
    border-radius: var(--r-sm); padding: 7px 10px;
    font-size: 12px; color: #b91c1c; text-align: center; margin-bottom: 8px;
}

.btn-pay {
    width: 100%; padding: 13px; border-radius: var(--r);
    border: none; background: var(--p); color: #fff;
    font-size: 14px; font-weight: 700; cursor: pointer;
    display: flex; align-items: center; justify-content: center; gap: 8px;
    transition: background .15s, opacity .15s;
    box-shadow: 0 3px 10px var(--p-ring);
}
.btn-pay:hover:not(:disabled) { background: var(--p-dark); }
.btn-pay:disabled { background: #d1d5db; cursor: not-allowed; box-shadow: none; }
.btn-pay svg { width: 18px; height: 18px; }

@keyframes spin { to { transform: rotate(360deg); } }
.spin { animation: spin .7s linear infinite; display: inline-block; }

/* ══════════════════════════════════════════════════
   MODAL STRUK
══════════════════════════════════════════════════ */
.modal-overlay {
    position: fixed; inset: 0; z-index: 9999;
    background: rgba(0,0,0,.5);
    display: flex; align-items: center; justify-content: center; padding: 16px;
    backdrop-filter: blur(2px);
}
.modal-box {
    background: #fff; border-radius: 18px;
    width: 100%; overflow: hidden;
    box-shadow: 0 24px 60px rgba(0,0,0,.25);
    animation: modal-in .2s ease-out;
}
@keyframes modal-in { from { transform: scale(.95); opacity: 0; } to { transform: scale(1); opacity: 1; } }
.modal-box.w-80 { max-width: 380px; }
.modal-box.w-58 { max-width: 295px; }

.modal-head {
    background: var(--p); padding: 14px 18px;
    display: flex; align-items: center; justify-content: space-between;
}
.mh-left { display: flex; align-items: center; gap: 8px; color: #fff; }
.mh-left span { font-weight: 700; font-size: 15px; }
.mh-left svg { width: 20px; height: 20px; }
.mh-close {
    background: rgba(255,255,255,.15); border: none; cursor: pointer;
    color: #fff; width: 28px; height: 28px; border-radius: 6px;
    display: flex; align-items: center; justify-content: center;
    transition: background .15s;
}
.mh-close:hover { background: rgba(255,255,255,.3); }
.mh-close svg { width: 16px; height: 16px; }

.modal-body {
    padding: 16px 20px;
    max-height: 58vh; overflow-y: auto;
    font-family: 'Courier New', Courier, monospace;
    font-size: 12px; color: #111;
    line-height: 1.5;
}

/* Struk styles */
.rp-store-name { font-size: 14px; font-weight: 700; text-align: center; }
.rp-store-info { font-size: 11px; text-align: center; color: #555; }
.rp-divider { border: none; border-top: 1px dashed #ccc; margin: 7px 0; }
.rp-row { display: flex; justify-content: space-between; padding: 1px 0; font-size: 11px; }
.rp-row-label { color: #666; }
.rp-item-std .rp-item-name { font-weight: 600; font-size: 12px; }
.rp-item-std .rp-item-detail { font-size: 10px; color: #666; }
.rp-item-std .rp-item-sub { text-align: right; font-size: 11px; }
.rp-compact { display: flex; justify-content: space-between; font-size: 11px; padding: 1px 0; }
.rp-detailed { display: grid; grid-template-columns: 1fr 28px 70px; gap: 4px; font-size: 11px; padding: 1px 0; }
.rp-det-head { display: grid; grid-template-columns: 1fr 28px 70px; gap: 4px; font-size: 10px; font-weight: 700; color: #666; border-bottom: 1px solid #eee; padding-bottom: 3px; margin-bottom: 3px; }
.rp-total { display: flex; justify-content: space-between; font-weight: 700; font-size: 14px; margin-top: 4px; }
.rp-total-amt { color: var(--p-dark); }
.rp-summary { display: flex; justify-content: space-between; font-size: 11px; padding: 3px 8px; border-radius: 4px; margin-top: 3px; }
.rp-saldo { background: #f0fdf4; color: var(--p-dark); }
.rp-bayar { background: #f9fafb; color: #374151; }
.rp-kembalian { background: #eff6ff; color: #1d4ed8; }
.rp-footer { text-align: center; font-size: 10px; color: #666; margin-top: 6px; line-height: 1.5; }
.rp-barcode { text-align: center; border: 1px dashed #ccc; padding: 5px; border-radius: 4px; margin-top: 6px; font-size: 9px; color: #555; }

.modal-actions {
    display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 8px;
    padding: 12px 18px; background: #f9fafb; border-top: 1px solid #f3f4f6;
}
.modal-act-btn {
    display: flex; flex-direction: column; align-items: center; gap: 4px;
    padding: 9px 6px; border-radius: var(--r); border: none; cursor: pointer;
    font-size: 11px; font-weight: 600; line-height: 1.3; text-align: center;
    text-decoration: none; transition: all .15s;
}
.modal-act-btn svg { width: 18px; height: 18px; }
.btn-pdf   { background: #eff6ff; color: #1d4ed8; }
.btn-pdf:hover { background: #dbeafe; }
.btn-print { background: #f5f3ff; color: #6d28d9; }
.btn-print:hover:not(:disabled) { background: #ede9fe; }
.btn-print:disabled { background: #f3f4f6; color: #9ca3af; cursor: not-allowed; }
.btn-bt    { background: #fff7ed; color: #c2410c; }
.btn-bt:hover { background: #fed7aa; }
.btn-bt-on { background: #f0fdf4; color: var(--p-dark); }
.btn-bt-on:hover { background: var(--p-light); }
.bt-status {
    padding: 7px 18px; text-align: center; font-size: 11px;
}
.bt-ok  { background: #f0fdf4; color: var(--p-dark); }
.bt-err { background: #fff7ed; color: #c2410c; }

/* ══════════════════════════════════════════════════
   MODAL CHECKOUT (Stocky-style)
══════════════════════════════════════════════════ */
.co-overlay {
    position: fixed; inset: 0; z-index: 9998;
    background: rgba(0,0,0,.55);
    display: flex; align-items: center; justify-content: center; padding: 16px;
    backdrop-filter: blur(3px);
}
.co-box {
    background: #fff; border-radius: 20px;
    width: 100%; max-width: 980px;
    box-shadow: 0 32px 80px rgba(0,0,0,.25);
    overflow: hidden;
    animation: modal-in .2s ease-out;
    display: flex; flex-direction: column;
    max-height: 92vh;
}
.co-head {
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    padding: 16px 22px;
    display: flex; align-items: center; justify-content: space-between;
    flex-shrink: 0;
}
.co-head-left { display: flex; align-items: center; gap: 12px; }
.co-head-icon {
    width: 38px; height: 38px; border-radius: 10px;
    background: rgba(255,255,255,.2);
    display: flex; align-items: center; justify-content: center;
}
.co-head-icon svg { width: 20px; height: 20px; color: #fff; }
.co-head-title { font-size: 17px; font-weight: 700; color: #fff; }
.co-head-close {
    width: 32px; height: 32px; border-radius: 8px;
    background: rgba(255,255,255,.15); border: none;
    color: #fff; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    transition: background .15s;
}
.co-head-close:hover { background: rgba(255,255,255,.3); }
.co-head-close svg { width: 16px; height: 16px; }

.co-body {
    display: grid;
    grid-template-columns: 260px 1fr 200px;
    gap: 0;
    overflow: hidden;
    flex: 1;
}

/* LEFT — Transaction Summary */
.co-summary {
    background: #f8f9ff;
    border-right: 1px solid #e8eaf6;
    padding: 20px 18px;
    display: flex; flex-direction: column; gap: 16px;
    overflow-y: auto;
}
.co-sum-label {
    font-size: 10px; font-weight: 700; color: #6366f1;
    text-transform: uppercase; letter-spacing: .1em;
    display: flex; align-items: center; gap: 6px;
}
.co-sum-label svg { width: 13px; height: 13px; }
.co-total-box {
    text-align: center; padding: 16px 10px;
    background: #fff; border-radius: 12px;
    border: 1px solid #e8eaf6;
    box-shadow: 0 2px 8px rgba(99,102,241,.08);
}
.co-total-lbl { font-size: 11px; color: #6b7280; margin-bottom: 4px; font-weight: 500; }
.co-total-amt { font-size: 28px; font-weight: 800; color: #1f2937; letter-spacing: -.5px; }

.co-breakdown { display: flex; flex-direction: column; gap: 8px; }
.co-bd-item {
    display: flex; align-items: center; gap: 10px;
    padding: 10px 12px; border-radius: 10px;
    background: #fff; border: 1px solid #e8eaf6;
}
.co-bd-icon {
    width: 32px; height: 32px; border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; font-size: 15px;
}
.co-bd-icon.green { background: #dcfce7; }
.co-bd-icon.orange { background: #fff7ed; }
.co-bd-icon.blue { background: #eff6ff; }
.co-bd-info { flex: 1; min-width: 0; }
.co-bd-lbl { font-size: 10px; color: #9ca3af; font-weight: 600; text-transform: uppercase; letter-spacing: .06em; }
.co-bd-val { font-size: 15px; font-weight: 700; color: #1f2937; }
.co-bd-val.green { color: #16a34a; }
.co-bd-val.orange { color: #d97706; }
.co-bd-val.blue { color: #2563eb; }

/* Items list */
.co-items { display: flex; flex-direction: column; gap: 6px; }
.co-item {
    display: flex; justify-content: space-between; align-items: center;
    font-size: 12px; padding: 6px 0;
    border-bottom: 1px dashed #e5e7eb;
}
.co-item:last-child { border-bottom: none; }
.co-item-name { color: #374151; font-weight: 500; flex: 1; }
.co-item-qty { color: #9ca3af; margin: 0 8px; font-size: 11px; }
.co-item-price { color: #1f2937; font-weight: 600; }

/* MIDDLE — Payment */
.co-payment {
    padding: 20px 22px;
    display: flex; flex-direction: column; gap: 16px;
    overflow-y: auto;
    border-right: 1px solid #e8eaf6;
}
.co-pay-num {
    display: flex; align-items: center; gap: 10px;
}
.co-pay-badge {
    width: 28px; height: 28px; border-radius: 50%;
    background: #6366f1; color: #fff;
    font-size: 13px; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.co-pay-title { font-size: 15px; font-weight: 700; color: #1f2937; }

/* Student select */
.co-student-section { display: flex; flex-direction: column; gap: 6px; }
.co-field-label { font-size: 11px; font-weight: 600; color: #6b7280; margin-bottom: 4px; }
.co-stu-selected {
    display: flex; align-items: center; gap: 10px;
    padding: 10px 14px; border-radius: 10px;
    background: #f0fdf4; border: 1.5px solid #86efac;
}
.co-stu-avatar {
    width: 36px; height: 36px; border-radius: 50%;
    background: #16a34a; color: #fff;
    display: flex; align-items: center; justify-content: center;
    font-size: 14px; font-weight: 700; flex-shrink: 0;
}
.co-stu-info { flex: 1; }
.co-stu-name { font-size: 13px; font-weight: 600; color: #14532d; }
.co-stu-meta { font-size: 11px; color: #4ade80; }
.co-stu-bal { font-size: 13px; font-weight: 700; color: #16a34a; }

/* Amount input */
.co-amount-wrap { position: relative; }
.co-amount-prefix {
    position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
    font-size: 14px; color: #6b7280; font-weight: 600; pointer-events: none;
}
.co-amount-inp {
    width: 100%; padding: 12px 14px 12px 50px;
    border: 1.5px solid #e5e7eb; border-radius: 10px;
    font-size: 20px; font-weight: 700; color: #1f2937;
    outline: none; background: #f9fafb;
    transition: border-color .15s, box-shadow .15s;
}
.co-amount-inp:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,.15); background: #fff; }

/* Payment method grid */
.co-method-grid {
    display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px;
}
.co-method-btn {
    display: flex; flex-direction: column; align-items: center; gap: 6px;
    padding: 12px 8px; border-radius: 12px;
    border: 2px solid #e5e7eb; background: #f9fafb;
    cursor: pointer; transition: all .15s; position: relative;
    font-size: 12px; font-weight: 600; color: #374151;
}
.co-method-btn:hover { border-color: #6366f1; background: #eef2ff; }
.co-method-btn.active { border-color: #6366f1; background: #eef2ff; color: #4338ca; }
.co-method-check {
    position: absolute; top: 6px; right: 6px;
    width: 18px; height: 18px; border-radius: 50%;
    background: #6366f1; color: #fff;
    display: flex; align-items: center; justify-content: center;
    font-size: 10px; opacity: 0; transition: opacity .15s;
}
.co-method-btn.active .co-method-check { opacity: 1; }
.co-method-icon {
    width: 40px; height: 40px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 20px;
}
.co-method-icon.indigo { background: #eef2ff; }
.co-method-icon.green  { background: #f0fdf4; }

/* Payment date */
.co-date-inp {
    width: 100%; padding: 10px 14px;
    border: 1.5px solid #e5e7eb; border-radius: 10px;
    font-size: 13px; color: #1f2937; background: #f9fafb;
    outline: none; transition: border-color .15s;
}
.co-date-inp:focus { border-color: #6366f1; background: #fff; }

/* RIGHT — Quick amounts */
.co-quick {
    background: #f8f9ff;
    border-left: 1px solid #e8eaf6;
    padding: 20px 14px;
    display: flex; flex-direction: column; gap: 8px;
    overflow-y: auto;
}
.co-quick-label {
    font-size: 10px; font-weight: 700; color: #6366f1;
    text-transform: uppercase; letter-spacing: .1em; margin-bottom: 4px;
}
.co-quick-btn {
    width: 100%; padding: 11px;
    border: 1.5px solid #e8eaf6; border-radius: 10px;
    background: #fff; color: #1f2937;
    font-size: 14px; font-weight: 600;
    cursor: pointer; transition: all .15s; text-align: center;
}
.co-quick-btn:hover { border-color: #6366f1; color: #4338ca; background: #eef2ff; }
.co-quick-btn.exact { border-color: #16a34a; color: #16a34a; background: #f0fdf4; }
.co-quick-btn.exact:hover { background: #dcfce7; }

/* Footer */
.co-footer {
    display: grid; grid-template-columns: 1fr 1fr;
    gap: 10px; padding: 14px 22px;
    border-top: 1px solid #e5e7eb;
    flex-shrink: 0; background: #fff;
}
.co-btn-cancel {
    padding: 13px; border-radius: 10px;
    border: 1.5px solid #e5e7eb; background: #fff;
    color: #6b7280; font-size: 14px; font-weight: 600;
    cursor: pointer; transition: all .15s;
    display: flex; align-items: center; justify-content: center; gap: 6px;
}
.co-btn-cancel:hover { border-color: #ef4444; color: #ef4444; background: #fef2f2; }
.co-btn-complete {
    padding: 13px; border-radius: 10px;
    border: none; background: linear-gradient(135deg, #16a34a, #15803d);
    color: #fff; font-size: 14px; font-weight: 700;
    cursor: pointer; transition: all .15s;
    display: flex; align-items: center; justify-content: center; gap: 6px;
    box-shadow: 0 4px 14px rgba(22,163,74,.35);
}
.co-btn-complete:hover:not(:disabled) { background: linear-gradient(135deg, #15803d, #14532d); }
.co-btn-complete:disabled { background: #d1d5db; box-shadow: none; cursor: not-allowed; }
.co-btn-complete svg, .co-btn-cancel svg { width: 17px; height: 17px; }
</style>

{{-- ══ TOP BAR ══════════════════════════════════════════════════════ --}}
<div class="pos-topbar">
    <div class="tb-left">
        <div class="tb-brand">
            <div class="tb-logo">🛒</div>
            POS Cashier
        </div>
        <div class="tb-divider"></div>
        <div class="tb-register">
            <span class="tb-dot"></span>
            Kasir 1 &middot; Online
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

{{-- ══ MODAL STRUK ══════════════════════════════════════════════════ --}}
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
            @if($o['show_store_name'])
            <div class="rp-store-name">{{ $o['store_name'] }}</div>
            @endif
            @if($o['show_address'] && $o['store_address'])
            <div class="rp-store-info">{{ $o['store_address'] }}</div>
            @endif
            @if($o['show_phone'] && $o['store_phone'])
            <div class="rp-store-info">Telp: {{ $o['store_phone'] }}</div>
            @endif

            <hr class="rp-divider">

            @if($o['show_invoice_number'])
            <div class="rp-row"><span class="rp-row-label">Invoice</span><span>{{ $o['invoice_number'] }}</span></div>
            @endif
            @if($o['show_date'])
            <div class="rp-row"><span class="rp-row-label">Tanggal</span><span>{{ $o['created_at'] }}</span></div>
            @endif
            @if($o['show_student'] && $o['student_name'])
            <div class="rp-row"><span class="rp-row-label">Siswa</span><span>{{ $o['student_name'] }} (Kls {{ $o['student_class'] }})</span></div>
            @endif
            @if($o['show_payment_method'])
            <div class="rp-row"><span class="rp-row-label">Bayar</span><span>{{ $o['payment_method'] === 'wallet' ? 'Dompet Siswa' : 'Tunai' }}</span></div>
            @endif
            @if($o['show_cashier'])
            <div class="rp-row"><span class="rp-row-label">Kasir</span><span>{{ $o['cashier_name'] }}</span></div>
            @endif

            <hr class="rp-divider">

            @if($o['layout'] === 'detailed')
            <div class="rp-det-head"><span>Item</span><span style="text-align:center">Qty</span><span style="text-align:right">Total</span></div>
            @endif

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

            <div class="rp-total">
                <span>TOTAL</span>
                <span class="rp-total-amt">Rp {{ number_format($o['total_amount'], 0, ',', '.') }}</span>
            </div>

            @if($o['show_balance_after'] && $o['payment_method'] === 'wallet' && isset($o['balance_after']))
            <div class="rp-summary rp-saldo"><span>Sisa Saldo</span><span>Rp {{ number_format($o['balance_after'], 0, ',', '.') }}</span></div>
            @endif
            @if($o['show_change'] && $o['payment_method'] === 'cash')
            <div class="rp-summary rp-bayar"><span>Bayar</span><span>Rp {{ number_format($o['cash_amount'], 0, ',', '.') }}</span></div>
            <div class="rp-summary rp-kembalian"><span>Kembalian</span><span>Rp {{ number_format($o['change_amount'], 0, ',', '.') }}</span></div>
            @endif

            @if($o['show_footer'] && $o['store_footer'])
            <hr class="rp-divider">
            <div class="rp-footer">
                @foreach(explode("\n", $o['store_footer']) as $line)
                    @if(trim($line))<p>{{ trim($line) }}</p>@endif
                @endforeach
            </div>
            @endif

            @if($o['show_barcode'])
            <div class="rp-barcode">
                <div style="font-family:monospace;font-size:16px;letter-spacing:-1px">||| || ||| | || ||| ||</div>
                <div>{{ $o['invoice_number'] }}</div>
            </div>
            @endif
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

{{-- ══ MAIN POS ══════════════════════════════════════════════════════ --}}
<div class="pos-wrap">

    {{-- ─ LEFT: Produk ─ --}}
    <div class="pos-left">

        {{-- Toolbar --}}
        <div class="prod-toolbar">
            <div class="search-wrap">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                <input
                    wire:model.live.debounce.300ms="searchProduct"
                    type="text"
                    placeholder="Scan / cari produk atau barcode..."
                    class="search-input"
                />
            </div>
            <div class="barcode-btn" title="Scan Barcode">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 6.75h.75v.75h-.75v-.75zM6.75 16.5h.75v.75h-.75v-.75zM16.5 6.75h.75v.75h-.75v-.75zM13.5 13.5h.75v.75h-.75v-.75zM13.5 18.75h.75v.75h-.75v-.75zM18.75 13.5h.75v.75h-.75v-.75zM18.75 18.75h.75v.75h-.75v-.75zM16.5 16.5h.75v.75h-.75v-.75z"/></svg>
            </div>
        </div>

        {{-- Category tabs --}}
        <div class="cat-bar">
            <button
                wire:click="selectCategory(null)"
                class="cat-pill {{ is_null($selectedCategory) ? 'active' : '' }}"
            >Semua</button>
            @foreach($this->categories as $cat)
            <button
                wire:click="selectCategory({{ $cat->id }})"
                class="cat-pill {{ $selectedCategory === $cat->id ? 'active' : '' }}"
            >{{ $cat->name }} <span style="opacity:.7">({{ $cat->products_count }})</span></button>
            @endforeach
        </div>

        {{-- Product grid --}}
        <div class="prod-body" wire:poll.15s="refreshProducts">
            <div class="prod-grid">
                @forelse($this->products as $product)
                @php
                    $inCart = $cart->contains('product_id', $product->id);
                    $cartQty = $cart->firstWhere('product_id', $product->id)['quantity'] ?? 0;
                @endphp
                <button class="prod-card {{ $inCart ? 'in-cart' : '' }}" wire:click="addToCart({{ $product->id }})" wire:key="prod-{{ $product->id }}">
                    <div class="pc-img-wrap">
                        @if($product->image)
                            <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" loading="lazy">
                        @else
                            <div class="pc-img-placeholder">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                            </div>
                        @endif
                        <div class="pc-cart-badge">{{ $cartQty ?: '✓' }}</div>
                    </div>
                    <div class="pc-body">
                        <div class="pc-name">{{ $product->name }}</div>
                        @if($product->barcode)
                        <div class="pc-sku">{{ $product->barcode }}</div>
                        @endif
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
                <button class="pf-btn" onclick="alert('Fitur draft belum tersedia')">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:13px;height:13px"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Draft
                </button>
                <button class="pf-btn hold">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:13px;height:13px"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25v13.5m-7.5-13.5v13.5"/></svg>
                    Tahan
                </button>
            </div>
        </div>
    </div>

    {{-- ─ RIGHT: Keranjang ─ --}}
    <div class="pos-cart">

        {{-- Header --}}
        <div class="cart-header">
            <div class="ch-left">
                <div class="ch-title">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/></svg>
                    Keranjang
                    @if($this->cartItemsCount() > 0)
                    <span class="ch-badge">{{ $this->cartItemsCount() }}</span>
                    @endif
                </div>
            </div>
            @if($cart->isNotEmpty())
            <button class="ch-clear" wire:click="clearCart" wire:confirm="Hapus semua item dari keranjang?">
                Hapus semua
            </button>
            @endif
        </div>

        {{-- Student --}}
        <div class="cart-student">
            <div class="section-label">Siswa</div>
            @if($selectedStudentId)
            <div class="stu-card">
                <div class="stu-card-avatar">{{ strtoupper(substr($selectedStudentName, 0, 1)) }}</div>
                <div class="stu-card-info">
                    <div class="stu-card-name">{{ $selectedStudentName }}</div>
                    <div class="stu-card-meta">Kelas {{ $selectedStudentClass }}</div>
                </div>
                <div class="stu-card-bal">
                    <div class="stu-card-bal-label">Saldo</div>
                    <div class="stu-card-bal-amount {{ $selectedStudentBalance <= 5000 ? 'low' : '' }}">
                        Rp {{ number_format($selectedStudentBalance, 0, ',', '.') }}
                    </div>
                </div>
                <button class="stu-clear" wire:click="clearStudent">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            @else
            <div class="stu-search-wrap" x-data="{}">
                <svg class="stu-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                <input
                    wire:model.live.debounce.300ms="searchStudent"
                    type="text"
                    placeholder="Cari nama / kelas / NISN..."
                    class="stu-input"
                    @focus="$wire.set('showStudentDropdown', $wire.searchStudent.length > 0)"
                    autocomplete="off"
                />
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
            @endif
        </div>

        {{-- Cash input --}}
        @if($paymentMethod === 'cash')
            <div class="cart-cash" x-data="{
                cash: {{ (int)$cashAmount }},
                total: {{ (int)$this->cartTotal() }},
                get kem(){ return Math.max(0, this.cash - this.total) },
                fmt(n){ return 'Rp ' + Number(n).toLocaleString('id-ID') }
            }">
                <div class="cash-wrap">
                    <span class="cash-prefix">Rp</span>
                    <input
                        x-model.number="cash"
                        @change="$wire.set('cashAmount', cash)"
                        type="number"
                        placeholder="Nominal yang dibayar"
                        class="cash-inp"
                        min="0"
                    />
                </div>
                <template x-if="cash > 0 && total > 0">
                    <div class="kem-row">
                        <span class="kem-label">Kembalian</span>
                        <span class="kem-amount" x-text="fmt(kem)"></span>
                    </div>
                </template>
            </div>
            @endif

        {{-- Cart items --}}
        <div class="cart-items">
            @forelse($cart as $index => $item)
            <div class="cart-item" wire:key="cart-{{ $index }}">
                <div class="ci-thumb">
                    @if($item['image'])
                        <img src="{{ Storage::url($item['image']) }}" alt="{{ $item['name'] }}">
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                    @endif
                </div>
                <div class="ci-info">
                    <div class="ci-name">{{ $item['name'] }}</div>
                    <div class="ci-unit">Rp {{ number_format($item['price'], 0, ',', '.') }} / pcs</div>
                </div>
                <div class="ci-right">
                    <div class="ci-qty-row">
                        <button class="qty-btn" wire:click="decrementQty({{ $index }})">−</button>
                        <span class="qty-num">{{ $item['quantity'] }}</span>
                        <button class="qty-btn" wire:click="incrementQty({{ $index }})">+</button>
                    </div>
                    <span class="ci-sub">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                </div>
            </div>
            @empty
            <div class="cart-empty">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.2" stroke="currentColor" style="color:#d1d5db"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/></svg>
                <p>Keranjang masih kosong</p>
            </div>
            @endforelse
        </div>

        {{-- Cart footer --}}
        <div class="cart-footer">
            <div class="cf-rows">
                <div class="cf-row"><span>Subtotal</span><span>Rp {{ number_format($this->cartTotal(), 0, ',', '.') }}</span></div>
                <div class="cf-row total">
                    <span>Total</span>
                    <span class="cf-total-amt">Rp {{ number_format($this->cartTotal(), 0, ',', '.') }}</span>
                </div>
            </div>

            @if($paymentMethod === 'wallet' && $selectedStudentId && $cart->isNotEmpty())
            <div class="cf-saldo-row {{ !$this->saldoCukup() ? 'low' : '' }}">
                <span class="cf-saldo-label {{ !$this->saldoCukup() ? 'low' : '' }}">Sisa saldo</span>
                <span class="cf-saldo-val {{ !$this->saldoCukup() ? 'low' : '' }}">Rp {{ number_format($this->saldoSetelahBayar(), 0, ',', '.') }}</span>
            </div>
            @endif

            @if($paymentMethod === 'wallet' && $selectedStudentId && $cart->isNotEmpty() && !$this->saldoCukup())
            <div class="saldo-warn" style="margin-top:6px">
                ⚠ Saldo tidak cukup — kurang Rp {{ number_format($this->cartTotal() - $selectedStudentBalance, 0, ',', '.') }}
            </div>
            @endif

            <button
                class="btn-pay"
                @click="$wire.openCheckout()"
                @disabled($cart->isEmpty())
                style="margin-top:10px"
            >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:18px;height:18px"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/></svg>
                Pay Now
            </button>
        </div>
    </div>
    {{-- ══ MODAL CHECKOUT (Stocky-style) ══════════════════════════════ --}}
@if($showCheckoutModal)
<div class="co-overlay" x-data="checkoutModal()" x-init="init()">
    <div class="co-box">

        {{-- Head --}}
        <div class="co-head">
            <div class="co-head-left">
                <div class="co-head-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/></svg>
                </div>
                <span class="co-head-title">Payment Checkout</span>
            </div>
            <button class="co-head-close" wire:click="closeCheckoutModal">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Body --}}
        <div class="co-body">

            {{-- LEFT: Summary --}}
            <div class="co-summary">
                <div class="co-sum-label">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z"/></svg>
                    Transaction Summary
                </div>

                <div class="co-total-box">
                    <div class="co-total-lbl">TOTAL AMOUNT</div>
                    <div class="co-total-amt">Rp {{ number_format($this->cartTotal(), 0, ',', '.') }}</div>
                </div>

                {{-- Breakdown --}}
                <div class="co-breakdown">
                    <div style="font-size:10px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.06em">Payment Breakdown</div>
                    <div class="co-bd-item">
                        <div class="co-bd-icon green">✅</div>
                        <div class="co-bd-info">
                            <div class="co-bd-lbl">Paying</div>
                            <div class="co-bd-val">Rp {{ number_format($this->cartTotal(), 0, ',', '.') }}</div>
                        </div>
                    </div>
                    <div class="co-bd-item">
                        <div class="co-bd-icon orange">💰</div>
                        <div class="co-bd-info">
                            <div class="co-bd-lbl">Balance / Kembalian</div>
                            <div class="co-bd-val orange" id="co-balance-display">Rp 0</div>
                        </div>
                    </div>
                    <div class="co-bd-item">
                        <div class="co-bd-icon blue">🔄</div>
                        <div class="co-bd-info">
                            <div class="co-bd-lbl">Change</div>
                            <div class="co-bd-val blue" id="co-change-display">Rp 0</div>
                        </div>
                    </div>
                </div>

                {{-- Items --}}
                <div>
                    <div style="font-size:10px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.06em;margin-bottom:8px">Items</div>
                    <div class="co-items">
                        @foreach($cart as $item)
                        <div class="co-item">
                            <span class="co-item-name">{{ $item['name'] }}</span>
                            <span class="co-item-qty">×{{ $item['quantity'] }}</span>
                            <span class="co-item-price">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- MIDDLE: Payment --}}
            <div class="co-payment">
                <div class="co-pay-num">
                    <div class="co-pay-badge">1</div>
                    <div class="co-pay-title">Payment #1</div>
                </div>

                {{-- Amount --}}
                <div>
                    <div class="co-field-label">Amount</div>
                    <div class="co-amount-wrap">
                        <span class="co-amount-prefix">Rp</span>
                        <input
                            type="number"
                            class="co-amount-inp"
                            x-model.number="cashInput"
                            @input="updateDisplay()"
                            placeholder="0"
                            id="co-cash-input"
                        />
                    </div>
                </div>

                {{-- Student (wallet) --}}
                <div class="co-student-section" id="co-student-section" style="display:none">
                    <div class="co-field-label">Siswa</div>
                    @if($selectedStudentId)
                    <div class="co-stu-selected">
                        <div class="co-stu-avatar">{{ strtoupper(substr($selectedStudentName ?? 'S', 0, 1)) }}</div>
                        <div class="co-stu-info">
                            <div class="co-stu-name">{{ $selectedStudentName }}</div>
                            <div class="co-stu-meta">Kelas {{ $selectedStudentClass }}</div>
                        </div>
                        <div class="co-stu-bal">Rp {{ number_format($selectedStudentBalance, 0, ',', '.') }}</div>
                    </div>
                    @else
                    <div style="padding:10px 14px;border-radius:10px;border:1.5px dashed #d1d5db;text-align:center;font-size:13px;color:#9ca3af">
                        ⚠ Pilih siswa di keranjang terlebih dahulu
                    </div>
                    @endif
                </div>

                {{-- Method grid --}}
                <div>
                    <div class="co-field-label">Metode Pembayaran</div>
                    <div class="co-method-grid">
                        <button class="co-method-btn active" @click="selectMethod('cash', $el)">
                            <div class="co-method-check">✓</div>
                            <div class="co-method-icon indigo">🏧</div>
                            <span>Tunai</span>
                        </button>
                        <button class="co-method-btn" @click="selectMethod('wallet', $el)">
                            <div class="co-method-check">✓</div>
                            <div class="co-method-icon green">👛</div>
                            <span>Dompet Siswa</span>
                        </button>
                        <button class="co-method-btn" @click="selectMethod('transfer', $el)">
                            <div class="co-method-check">✓</div>
                            <div class="co-method-icon indigo">🏦</div>
                            <span>Transfer</span>
                        </button>
                    </div>
                </div>

                {{-- Date --}}
                <div>
                    <div class="co-field-label">Payment Date</div>
                    <input type="date" class="co-date-inp" value="{{ now()->format('Y-m-d') }}" readonly>
                </div>
            </div>

            {{-- RIGHT: Quick amounts --}}
            <div class="co-quick">
                <div class="co-quick-label">Quick Amount</div>
                @php
                    $total = $this->cartTotal();
                    $quickAmounts = [
                        $total,
                        2000, 5000, 10000, 20000, 50000, 100000
                    ];
                    $quickAmounts = array_unique($quickAmounts);
                    sort($quickAmounts);
                @endphp
                @foreach($quickAmounts as $qa)
                <button
                    class="co-quick-btn {{ $qa == $total ? 'exact' : '' }}"
                    @click="setQuickAmount({{ $qa }})"
                >
                    {{ $qa == $total ? '✓ Exact ' : '' }}Rp {{ number_format($qa, 0, ',', '.') }}
                </button>
                @endforeach
            </div>
        </div>

        {{-- Footer --}}
        <div class="co-footer">
            <button class="co-btn-cancel" wire:click="closeCheckoutModal">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                Cancel
            </button>
            <button
                class="co-btn-complete"
                @click="completePayment()"
                wire:loading.attr="disabled"
                wire:target="checkoutFromModal"
            >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Complete Payment
            </button>
        </div>
    </div>
</div>
@endif
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
                this.btStatus = '⚠️ Bluetooth tidak tersedia. Gunakan Chrome/Edge.';
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
                    ]
                });
                this.btStatus = 'Menghubungkan ke ' + device.name + '...';
                const server = await device.gatt.connect();
                let svc;
                try { svc = await server.getPrimaryService('000018f0-0000-1000-8000-00805f9b34fb'); }
                catch { svc = await server.getPrimaryService('0000ff00-0000-1000-8000-00805f9b34fb'); }
                try { this.btCharacteristic = await svc.getCharacteristic('00002af1-0000-1000-8000-00805f9b34fb'); }
                catch { this.btCharacteristic = await svc.getCharacteristic('0000ff01-0000-1000-8000-00805f9b34fb'); }
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
            if (!this.btCharacteristic) { this.btStatus = '❌ Hubungkan printer terlebih dahulu'; return; }
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
                this.btStatus = '❌ ' + err.message;
            }
        },

        buildEscPos() {
            const o = this.order;
            const ESC = 0x1B, GS = 0x1D;
            const b = [];
            const push = (...x) => x.forEach(v => b.push(v));
            const text = s => { for (const c of String(s)) b.push(c.charCodeAt(0) > 127 ? 63 : c.charCodeAt(0)); };
            const line = (s = '') => { text(s); push(10); };
            const div = () => line('--------------------------------');
            const pad = (l, r, w = 32) => String(l) + ' '.repeat(Math.max(w - String(l).length - String(r).length, 1)) + String(r);
            const fmt = n => 'Rp ' + Number(n).toLocaleString('id-ID');

            push(ESC, 0x40);
            if (o.show_store_name) { push(ESC, 0x61, 1, ESC, 0x45, 1, GS, 0x21, 0x11); line(o.store_name); push(GS, 0x21, 0, ESC, 0x45, 0); }
            if (o.show_address && o.store_address) { push(ESC, 0x61, 1); line(o.store_address.substring(0, 32)); }
            if (o.show_phone && o.store_phone) { push(ESC, 0x61, 1); line('Telp: ' + o.store_phone); }
            push(ESC, 0x61, 0); div();
            if (o.show_invoice_number) line(pad('Invoice:', o.invoice_number));
            if (o.show_date) line(pad('Tanggal:', o.created_at));
            if (o.show_student && o.student_name) line(pad('Siswa:', (o.student_name + ' / Kls ' + o.student_class).substring(0, 20)));
            if (o.show_payment_method) line(pad('Bayar:', o.payment_method === 'wallet' ? 'Dompet Siswa' : 'Tunai'));
            if (o.show_cashier) line(pad('Kasir:', o.cashier_name || 'Kasir'));
            div();
            for (const item of o.items) {
                if (o.layout === 'compact') {
                    line(pad(item.name.substring(0, 20), fmt(item.price * item.quantity)));
                } else if (o.layout === 'detailed') {
                    line(pad(item.name.substring(0, 20) + '  ' + item.quantity + 'x', fmt(item.price * item.quantity)));
                } else {
                    line(item.name.substring(0, 32));
                    if (o.show_item_price) line('  ' + item.quantity + 'x ' + fmt(item.price));
                    if (o.show_subtotal_per_item) line(pad('', fmt(item.price * item.quantity)));
                }
            }
            div();
            push(ESC, 0x45, 1, GS, 0x21, 1);
            line(pad('TOTAL:', fmt(o.total_amount)));
            push(GS, 0x21, 0, ESC, 0x45, 0);
            if (o.show_balance_after && o.payment_method === 'wallet' && o.balance_after !== null)
                line(pad('Sisa Saldo:', fmt(o.balance_after)));
            if (o.show_change && o.payment_method === 'cash') {
                line(pad('Bayar:', fmt(o.cash_amount)));
                line(pad('Kembalian:', fmt(o.change_amount)));
            }
            if (o.show_footer && o.store_footer) {
                div(); push(ESC, 0x61, 1);
                for (const fl of o.store_footer.split('\n')) {
                    if (fl.trim()) line(fl.trim().substring(0, 32));
                }
            }
            push(10, 10, 10, 10, GS, 0x56, 0x41, 0x10);
            return new Uint8Array(b);
        }
    }));

    // ── Checkout Modal ──────────────────────────────────────────────
    Alpine.data('checkoutModal', () => ({
        cashInput: {{ (int)$this->cartTotal() }},
        coMethod: 'cash',
        coTotal: {{ (int)$this->cartTotal() }},
        coSaldo: {{ (int)$selectedStudentBalance }},

        init() {
            this.updateDisplay();
        },

        fmt(n) {
            return 'Rp ' + Math.max(0, n).toLocaleString('id-ID');
        },

        selectMethod(method, el) {
            this.coMethod = method;
            document.querySelectorAll('.co-method-btn').forEach(b => b.classList.remove('active'));
            el.classList.add('active');
            const stuSection = document.getElementById('co-student-section');
            if (stuSection) stuSection.style.display = method === 'wallet' ? 'block' : 'none';
            if (method === 'wallet') this.cashInput = this.coTotal;
            this.updateDisplay();
            // ← TIDAK ada wire().set() di sini
        },

        setQuickAmount(amount) {
            this.cashInput = amount;
            this.updateDisplay();
            // ← TIDAK ada wire().set() di sini
        },

        updateDisplay() {
            // ← TIDAK ada wire().set() di sini, hanya update DOM
            const cash = parseFloat(this.cashInput) || 0;
            const balEl = document.getElementById('co-balance-display');
            const chEl  = document.getElementById('co-change-display');
            if (this.coMethod === 'cash') {
                if (balEl) balEl.textContent = this.fmt(cash);
                if (chEl)  chEl.textContent  = this.fmt(cash - this.coTotal);
            } else {
                if (balEl) balEl.textContent = this.fmt(this.coSaldo - this.coTotal);
                if (chEl)  chEl.textContent  = 'Rp 0';
            }
        },

        // ← Semua data dikirim ke Livewire hanya saat ini
        completePayment() {
            const wire = Livewire.find(document.querySelector('[wire\\:id]')?.getAttribute('wire:id'));
            wire?.call('checkoutFromModal', this.coMethod, parseFloat(this.cashInput) || 0);
        },
    }));
});

// ── Beep scanner ────────────────────────────────────────────────────
function playBeep() {
    const ctx = new (window.AudioContext || window.webkitAudioContext)();
    function tone(freq, start, duration) {
        const osc = ctx.createOscillator();
        const gain = ctx.createGain();
        osc.connect(gain);
        gain.connect(ctx.destination);
        osc.type = 'sine';
        osc.frequency.setValueAtTime(freq, ctx.currentTime + start);
        gain.gain.setValueAtTime(0, ctx.currentTime + start);
        gain.gain.linearRampToValueAtTime(0.4, ctx.currentTime + start + 0.005);
        gain.gain.setValueAtTime(0.4, ctx.currentTime + start + duration - 0.01);
        gain.gain.linearRampToValueAtTime(0, ctx.currentTime + start + duration);
        osc.start(ctx.currentTime + start);
        osc.stop(ctx.currentTime + start + duration);
    }
    tone(1200, 0, 0.06);
    tone(1800, 0.07, 0.09);
}

document.addEventListener('livewire:initialized', () => {
    Livewire.on('product-added', () => playBeep());
});
</script>
@endpush

</div>

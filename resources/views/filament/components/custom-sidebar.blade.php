{{-- resources/views/filament/components/custom-sidebar.blade.php --}}
<style>
/* ══════════════════════════════════════════════════════════════
   STOCKY-STYLE SIDEBAR  –  Responsive (mobile + tablet + desktop)
══════════════════════════════════════════════════════════════ */

/* ── DESKTOP (>= 1024px) ─────────────────────────────────── */
@media (min-width: 1024px) {
    nav[class*="fi-sidebar"] {
        width: 72px !important;
        min-width: 72px !important;
        max-width: 72px !important;
        overflow: visible !important;
        background: #fff;
        border-right: 1px solid #f3f4f6;
        z-index: 40;
        position: fixed !important;
        top: 0;
        left: 0;
        height: 100vh;
    }

    /* Sembunyikan label teks */
    nav[class*="fi-sidebar"] [class*="fi-sidebar-item-label"],
    nav[class*="fi-sidebar"] [class*="fi-sidebar-group-label"],
    nav[class*="fi-sidebar"] [class*="fi-sidebar-group-collapse-btn"],
    nav[class*="fi-sidebar"] svg.fi-sidebar-group-collapse-icon,
    nav[class*="fi-sidebar"] .fi-logo-text {
        display: none !important;
    }

    /* Nav item */
    nav[class*="fi-sidebar"] .fi-sidebar-item-button,
    nav[class*="fi-sidebar"] a[class*="fi-sidebar-item"] {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        width: 52px !important;
        height: 52px !important;
        margin: 2px auto !important;
        border-radius: 12px !important;
        padding: 0 !important;
        position: relative;
        transition: background .15s, color .15s;
    }

    nav[class*="fi-sidebar"] .fi-sidebar-item-button:hover,
    nav[class*="fi-sidebar"] a[class*="fi-sidebar-item"]:hover {
        background: #f0fdf4 !important;
        color: #16a34a !important;
    }

    nav[class*="fi-sidebar"] .fi-sidebar-item-button.fi-active,
    nav[class*="fi-sidebar"] a[class*="fi-sidebar-item"][aria-current] {
        background: #dcfce7 !important;
        color: #16a34a !important;
    }

    nav[class*="fi-sidebar"] [class*="fi-sidebar-group"] {
        position: relative !important;
        overflow: visible !important;
    }

    nav[class*="fi-sidebar"] .fi-sidebar-nav {
        overflow-y: auto;
        overflow-x: visible !important;
        scrollbar-width: none;
    }
    nav[class*="fi-sidebar"] .fi-sidebar-nav::-webkit-scrollbar { display: none; }

    /* Main content geser ke kanan */
    .fi-main, [class*="fi-layout-sidebar"] > div:last-child {
        margin-left: 72px !important;
    }
}

/* ── TABLET (768px - 1023px) ─────────────────────────────── */
@media (min-width: 768px) and (max-width: 1023px) {
    nav[class*="fi-sidebar"] {
        width: 64px !important;
        min-width: 64px !important;
        max-width: 64px !important;
        overflow: visible !important;
        background: #fff;
        border-right: 1px solid #f3f4f6;
        z-index: 40;
        position: fixed !important;
        top: 0;
        left: 0;
        height: 100vh;
    }

    nav[class*="fi-sidebar"] [class*="fi-sidebar-item-label"],
    nav[class*="fi-sidebar"] [class*="fi-sidebar-group-label"],
    nav[class*="fi-sidebar"] [class*="fi-sidebar-group-collapse-btn"],
    nav[class*="fi-sidebar"] svg.fi-sidebar-group-collapse-icon,
    nav[class*="fi-sidebar"] .fi-logo-text {
        display: none !important;
    }

    nav[class*="fi-sidebar"] .fi-sidebar-item-button,
    nav[class*="fi-sidebar"] a[class*="fi-sidebar-item"] {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        width: 46px !important;
        height: 46px !important;
        margin: 2px auto !important;
        border-radius: 10px !important;
        padding: 0 !important;
    }

    nav[class*="fi-sidebar"] .fi-sidebar-item-button:hover,
    nav[class*="fi-sidebar"] a[class*="fi-sidebar-item"]:hover {
        background: #f0fdf4 !important;
        color: #16a34a !important;
    }

    nav[class*="fi-sidebar"] .fi-sidebar-item-button.fi-active,
    nav[class*="fi-sidebar"] a[class*="fi-sidebar-item"][aria-current] {
        background: #dcfce7 !important;
        color: #16a34a !important;
    }

    nav[class*="fi-sidebar"] [class*="fi-sidebar-group"] {
        position: relative !important;
        overflow: visible !important;
    }

    .fi-main, [class*="fi-layout-sidebar"] > div:last-child {
        margin-left: 64px !important;
    }
}

/* ── MOBILE (< 768px) ────────────────────────────────────── */
@media (max-width: 767px) {
    /* Mobile: sidebar bottom tab bar */
    nav[class*="fi-sidebar"] {
        position: fixed !important;
        bottom: 0 !important;
        left: 0 !important;
        top: auto !important;
        width: 100% !important;
        max-width: 100% !important;
        height: 60px !important;
        min-height: 60px !important;
        border-right: none !important;
        border-top: 1px solid #f3f4f6 !important;
        background: #fff !important;
        z-index: 50 !important;
        overflow: visible !important;
        display: flex !important;
        align-items: center !important;
    }

    nav[class*="fi-sidebar"] .fi-sidebar-header {
        display: none !important;
    }

    nav[class*="fi-sidebar"] .fi-sidebar-nav {
        display: flex !important;
        flex-direction: row !important;
        align-items: center !important;
        justify-content: space-around !important;
        width: 100% !important;
        height: 100% !important;
        overflow-x: auto !important;
        overflow-y: visible !important;
        scrollbar-width: none;
        padding: 0 8px;
    }
    nav[class*="fi-sidebar"] .fi-sidebar-nav::-webkit-scrollbar { display: none; }

    nav[class*="fi-sidebar"] .fi-sidebar-nav-groups {
        display: flex !important;
        flex-direction: row !important;
        align-items: center !important;
        gap: 0 !important;
        width: 100% !important;
    }

    nav[class*="fi-sidebar"] [class*="fi-sidebar-group"] {
        position: relative !important;
        overflow: visible !important;
        flex: 1;
    }

    nav[class*="fi-sidebar"] [class*="fi-sidebar-group-label"],
    nav[class*="fi-sidebar"] [class*="fi-sidebar-group-collapse-btn"],
    nav[class*="fi-sidebar"] [class*="fi-sidebar-item-label"],
    nav[class*="fi-sidebar"] .fi-logo-text {
        display: none !important;
    }

    nav[class*="fi-sidebar"] .fi-sidebar-item-button,
    nav[class*="fi-sidebar"] a[class*="fi-sidebar-item"] {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        width: 48px !important;
        height: 48px !important;
        margin: 0 auto !important;
        border-radius: 12px !important;
        padding: 0 !important;
    }

    nav[class*="fi-sidebar"] .fi-sidebar-item-button:hover,
    nav[class*="fi-sidebar"] a[class*="fi-sidebar-item"]:hover {
        background: #f0fdf4 !important;
        color: #16a34a !important;
    }

    nav[class*="fi-sidebar"] .fi-sidebar-item-button.fi-active,
    nav[class*="fi-sidebar"] a[class*="fi-sidebar-item"][aria-current] {
        background: #dcfce7 !important;
        color: #16a34a !important;
    }

    /* Main content padding bottom supaya tidak ketutup tab bar */
    .fi-main {
        padding-bottom: 70px !important;
        margin-left: 0 !important;
    }

    /* Flyout mobile: muncul ke atas */
    .stocky-flyout {
        bottom: 68px !important;
        top: auto !important;
        left: 0 !important;
        right: auto !important;
        min-width: 180px;
        transform: translateY(8px);
    }
    .stocky-flyout.visible {
        transform: translateY(0);
    }
}

/* ══════════════════════════════════════════════════════════════
   FLYOUT (semua ukuran layar)
══════════════════════════════════════════════════════════════ */
.stocky-flyout {
    display: none;
    position: fixed;
    left: 76px;
    min-width: 200px;
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    box-shadow: 0 8px 30px rgba(0,0,0,.12);
    padding: 6px 0;
    z-index: 9999;
    pointer-events: none;
    opacity: 0;
    transform: translateX(-8px);
    transition: opacity .15s, transform .15s;
}

.stocky-flyout.visible {
    display: block;
    pointer-events: auto;
    opacity: 1;
    transform: translateX(0);
}

.stocky-flyout-title {
    font-size: 11px;
    font-weight: 700;
    color: #9ca3af;
    text-transform: uppercase;
    letter-spacing: .05em;
    padding: 6px 16px 4px;
}

.stocky-flyout a,
.stocky-flyout button {
    display: flex;
    align-items: center;
    gap: 10px;
    width: 100%;
    padding: 9px 16px;
    font-size: 13px;
    font-weight: 500;
    color: #374151;
    text-decoration: none;
    background: none;
    border: none;
    cursor: pointer;
    transition: background .12s, color .12s;
    white-space: nowrap;
}

.stocky-flyout a:hover,
.stocky-flyout button:hover {
    background: #f0fdf4;
    color: #16a34a;
}

.stocky-flyout a.active-flyout-item {
    background: #dcfce7;
    color: #16a34a;
    font-weight: 600;
}

.stocky-flyout a svg,
.stocky-flyout button svg {
    width: 16px;
    height: 16px;
    flex-shrink: 0;
}

/* ── Tooltip ─────────────────────────────────────────────── */
.stocky-tooltip {
    display: none;
    position: fixed;
    left: 80px;
    background: #1f2937;
    color: #fff;
    font-size: 12px;
    font-weight: 500;
    padding: 5px 10px;
    border-radius: 6px;
    white-space: nowrap;
    z-index: 9999;
    pointer-events: none;
    opacity: 0;
    transition: opacity .15s;
}
.stocky-tooltip.visible { display: block; opacity: 1; }
.stocky-tooltip::before {
    content: '';
    position: absolute;
    left: -5px; top: 50%;
    transform: translateY(-50%);
    border: 5px solid transparent;
    border-right-color: #1f2937;
    border-left: 0;
}

/* ── Dark mode ───────────────────────────────────────────── */
.dark nav[class*="fi-sidebar"] { background: #111827; border-color: #1f2937; }
.dark .stocky-flyout { background: #1f2937; border-color: #374151; }
.dark .stocky-flyout a, .dark .stocky-flyout button { color: #d1d5db; }
.dark .stocky-flyout a:hover, .dark .stocky-flyout button:hover { background: #14532d; color: #86efac; }
.dark nav[class*="fi-sidebar"] .fi-sidebar-item-button:hover { background: #14532d !important; }

@media (max-width: 767px) {
    .dark nav[class*="fi-sidebar"] { border-top-color: #1f2937; }
}
</style>

<div id="stocky-flyout-container"></div>
<div id="stocky-tooltip" class="stocky-tooltip"></div>

<script>
document.addEventListener('DOMContentLoaded', initStockySidebar);
document.addEventListener('livewire:navigated', initStockySidebar);

function initStockySidebar() {
    const sidebar = document.querySelector('nav[class*="fi-sidebar"]');
    if (!sidebar) return;

    const isMobile = () => window.innerWidth < 768;

    let activeFlyout = null;
    let hideTimer    = null;
    const container  = document.getElementById('stocky-flyout-container');
    const tooltip    = document.getElementById('stocky-tooltip');

    // Bersihkan flyout lama supaya tidak duplikat saat navigasi
    container.innerHTML = '';

    const groups = sidebar.querySelectorAll('[class*="fi-sidebar-group"]');

    groups.forEach(group => {
        const labelEl    = group.querySelector('[class*="fi-sidebar-group-label"]');
        const groupLabel = labelEl ? labelEl.textContent.trim() : '';
        const items      = group.querySelectorAll('.fi-sidebar-item-button, a[class*="fi-sidebar-item"]');
        if (items.length === 0) return;

        // Buat flyout
        const flyout = document.createElement('div');
        flyout.className = 'stocky-flyout';

        if (groupLabel) {
            const title = document.createElement('div');
            title.className = 'stocky-flyout-title';
            title.textContent = groupLabel;
            flyout.appendChild(title);
        }

        items.forEach(item => {
            const label   = item.querySelector('[class*="fi-sidebar-item-label"]');
            const icon    = item.querySelector('svg');
            const href    = item.getAttribute('href') || item.closest('a')?.getAttribute('href');
            const isActive = item.classList.contains('fi-active') ||
                             item.getAttribute('aria-current') === 'page';

            const link = document.createElement(href ? 'a' : 'button');
            if (href) link.href = href;
            if (isActive) link.classList.add('active-flyout-item');

            if (icon) {
                const iconClone = icon.cloneNode(true);
                iconClone.style.cssText = 'width:16px;height:16px;flex-shrink:0';
                link.appendChild(iconClone);
            }

            const span = document.createElement('span');
            span.textContent = label ? label.textContent.trim() : item.textContent.trim();
            link.appendChild(span);

            link.addEventListener('click', () => hideFlyout());
            flyout.appendChild(link);
        });

        container.appendChild(flyout);

        // ── Show / hide logic ───────────────────────────────────
        const showFlyout = () => {
            clearTimeout(hideTimer);
            if (activeFlyout && activeFlyout !== flyout) {
                activeFlyout.classList.remove('visible');
            }
            const rect = group.getBoundingClientRect();

            if (isMobile()) {
                // Mobile: flyout muncul di atas item, posisi horizontal ikut item
                flyout.style.left   = Math.max(8, rect.left) + 'px';
                flyout.style.bottom = (window.innerHeight - rect.top + 8) + 'px';
                flyout.style.top    = 'auto';
            } else {
                // Desktop/tablet: flyout dari kiri
                flyout.style.top    = Math.max(8, rect.top) + 'px';
                flyout.style.bottom = 'auto';
                flyout.style.left   = (isMobile() ? rect.left : 76) + 'px';
            }

            flyout.classList.add('visible');
            activeFlyout = flyout;
        };

        const scheduleHide = () => {
            hideTimer = setTimeout(hideFlyout, 150);
        };

        // Desktop: hover | Mobile: tap
        group.addEventListener('mouseenter', showFlyout);
        group.addEventListener('mouseleave', scheduleHide);
        group.addEventListener('click', (e) => {
            if (isMobile()) {
                e.stopPropagation();
                if (activeFlyout === flyout && flyout.classList.contains('visible')) {
                    hideFlyout();
                } else {
                    showFlyout();
                }
            }
        });

        flyout.addEventListener('mouseenter', () => clearTimeout(hideTimer));
        flyout.addEventListener('mouseleave', scheduleHide);
    });

    // ── Tooltip untuk item solo ─────────────────────────────
    sidebar.querySelectorAll('.fi-sidebar-item').forEach(item => {
        const label = item.querySelector('[class*="fi-sidebar-item-label"]');
        if (!label) return;
        const text = label.textContent.trim();

        item.addEventListener('mouseenter', () => {
            if (isMobile()) return; // tidak perlu tooltip di mobile
            const rect = item.getBoundingClientRect();
            tooltip.textContent = text;
            tooltip.style.top = (rect.top + rect.height / 2 - 12) + 'px';
            tooltip.classList.add('visible');
        });
        item.addEventListener('mouseleave', () => tooltip.classList.remove('visible'));
    });

    function hideFlyout() {
        if (activeFlyout) {
            activeFlyout.classList.remove('visible');
            activeFlyout = null;
        }
    }

    // Tutup flyout klik di luar
    document.addEventListener('click', (e) => {
        if (!e.target.closest('nav[class*="fi-sidebar"]') &&
            !e.target.closest('.stocky-flyout')) {
            hideFlyout();
        }
    });

    // Update posisi saat resize
    window.addEventListener('resize', hideFlyout);
}
</script>
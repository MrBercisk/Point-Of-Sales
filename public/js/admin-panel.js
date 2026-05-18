(function () {
    function initSidebarToggle() {
        // ── Guard: jangan inject di login/auth page (tidak ada .fi-sidebar) ──
        if (!document.querySelector('.fi-sidebar')) {
            const old = document.getElementById('sidebar-toggle-btn');
            if (old) old.remove();
            return;
        }

        if (document.getElementById('sidebar-toggle-btn')) return;

        const btn = document.createElement('div');
        btn.id = 'sidebar-toggle-btn';
        btn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6"/></svg>`;
        document.body.appendChild(btn);

        function getSidebar() {
            return document.querySelector('.fi-sidebar');
        }

        function clickFilamentBtn() {
            const filamentBtn = document.querySelector('.fi-sidebar-collapse-btn');
            if (!filamentBtn) return false;
            filamentBtn.style.cssText = 'position:fixed!important;top:-9999px!important;left:-9999px!important;width:1px!important;height:1px!important;opacity:0!important;pointer-events:auto!important;display:block!important;visibility:visible!important;';
            filamentBtn.click();
            setTimeout(() => {
                filamentBtn.style.cssText = 'position:fixed!important;top:-9999px!important;left:-9999px!important;width:1px!important;height:1px!important;opacity:0!important;pointer-events:none!important;';
            }, 100);
            return true;
        }

        function tryAlpineToggle() {
            if (window.Alpine) {
                try {
                    const store = Alpine.store('sidebar');
                    if (store && typeof store.toggle === 'function') { store.toggle(); return true; }
                    if (store && typeof store.isOpen !== 'undefined') { store.isOpen = !store.isOpen; return true; }
                } catch(e) {}
            }
            const sidebar = getSidebar();
            if (sidebar && sidebar._x_dataStack) {
                try {
                    const data = sidebar._x_dataStack[0];
                    if (typeof data.toggle === 'function') { data.toggle(); return true; }
                    if (typeof data.close === 'function' && typeof data.open === 'function') {
                        data.isOpen ? data.close() : data.open(); return true;
                    }
                    if (typeof data.isOpen !== 'undefined') { data.isOpen = !data.isOpen; return true; }
                    if (typeof data.collapsed !== 'undefined') { data.collapsed = !data.collapsed; return true; }
                } catch(e) {}
            }
            return false;
        }

        function updateBtn() {
            const sidebar = getSidebar();
            if (!sidebar) return;
            const w = sidebar.offsetWidth;
            const isCollapsed = w < 100;
            btn.classList.toggle('collapsed', isCollapsed);
            btn.style.setProperty('left', w + 'px', 'important');
        }

        btn.addEventListener('click', function () {
            if (clickFilamentBtn()) { setTimeout(updateBtn, 350); return; }
            if (tryAlpineToggle()) { setTimeout(updateBtn, 350); return; }
            document.body.dispatchEvent(new CustomEvent('sidebar-toggle', { bubbles: true }));
            setTimeout(updateBtn, 350);
        });

        const sidebar = getSidebar();
        if (sidebar) {
            new ResizeObserver(() => updateBtn()).observe(sidebar);
            new MutationObserver(() => updateBtn()).observe(sidebar, {
                attributes: true,
                attributeFilter: ['class', 'style'],
            });
        }

        updateBtn();

        // ── Sembunyikan toggle saat modal Filament aktif ──
        const bodyObserver = new MutationObserver(() => {
            const hasModal = !!document.querySelector('.fi-modal-window, .fi-overlay, [wire\\:id] [role="dialog"]');
            btn.style.opacity = hasModal ? '0' : '';
            btn.style.pointerEvents = hasModal ? 'none' : '';
        });
        bodyObserver.observe(document.body, {
            childList: true,
            subtree: true,
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initSidebarToggle);
    } else {
        initSidebarToggle();
    }

    document.addEventListener('livewire:navigated', function () {
        const old = document.getElementById('sidebar-toggle-btn');
        if (old) old.remove();
        setTimeout(initSidebarToggle, 100);
    });
})();
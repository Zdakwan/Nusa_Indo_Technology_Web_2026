(() => {
    'use strict';

    /**
     * Bungkus semua tabel agar bisa di-scroll horizontal di HP + petunjuk geser.
     */
    function enhanceTables() {
        document.querySelectorAll('table').forEach((table) => {
            if (table.dataset.mobileEnhanced === 'true') return;
            table.dataset.mobileEnhanced = 'true';

            const parent = table.parentElement;
            if (!parent) return;

            let wrapper = parent;
            const hasScrollWrapper =
                parent.classList.contains('overflow-x-auto') ||
                parent.classList.contains('responsive-table-wrap') ||
                parent.classList.contains('table-responsive');

            if (!hasScrollWrapper) {
                wrapper = document.createElement('div');
                wrapper.className = 'responsive-table-wrap';
                parent.insertBefore(wrapper, table);
                wrapper.appendChild(table);
            }

            const hint = document.createElement('div');
            hint.className = 'table-scroll-hint';
            hint.setAttribute('aria-hidden', 'true');
            hint.innerHTML = '<span>Geser tabel</span><i class="fa-solid fa-arrow-right-long"></i>';
            wrapper.parentElement?.insertBefore(hint, wrapper);

            const checkOverflow = () => {
                const isOverflowing = table.scrollWidth > wrapper.clientWidth + 2;
                hint.classList.toggle('is-overflowing', isOverflowing);
            };

            checkOverflow();
            window.addEventListener('resize', checkOverflow, { passive: true });
        });
    }

    /**
     * Bungkus iframe (YouTube, Google Maps, dll.) agar responsif 16:9.
     */
    function enhanceIframes() {
        document.querySelectorAll('iframe').forEach((iframe) => {
            if (iframe.dataset.mobileEnhanced === 'true') return;
            if (iframe.closest('.responsive-embed-wrap, .aspect-video')) return;
            const src = (iframe.getAttribute('src') || '').toLowerCase();
            if (src.includes('google.com/maps') || src.includes('maps.google') || iframe.classList.contains('h-full')) return;

            iframe.dataset.mobileEnhanced = 'true';
            const wrapper = document.createElement('div');
            wrapper.className = 'responsive-embed-wrap';
            iframe.parentElement?.insertBefore(wrapper, iframe);
            wrapper.appendChild(iframe);
        });
    }

    /**
     * Aktifkan drawer mobile otomatis pada layout Admin/Client yang masih
     * menyembunyikan <aside> (hidden md:flex) tanpa handler tombol hamburger.
     */
    function enhanceLegacySidebarDrawer() {
        const aside = document.querySelector('aside');
        if (!aside || aside.id === 'mobileSidebar' || aside.dataset.drawerBound === 'true') return;

        const isHiddenDesktopSidebar =
            aside.classList.contains('hidden') &&
            (aside.classList.contains('md:flex') || aside.classList.contains('lg:flex'));

        if (!isHiddenDesktopSidebar) return;

        const header = document.querySelector('header');
        if (!header) return;

        // Cari tombol hamburger yang belum memiliki onclick/handler khusus
        let toggleBtn = Array.from(header.querySelectorAll('button')).find((btn) =>
            !btn.getAttribute('onclick') &&
            btn.id !== 'publicMobileMenuBtn' &&
            btn.id !== 'sidebarToggleBtn' &&
            Boolean(btn.querySelector('.fa-bars, svg'))
        );

        // Jika header belum punya tombol menu mobile, sisipkan otomatis di sisi kiri header
        if (!toggleBtn) {
            toggleBtn = document.createElement('button');
            toggleBtn.type = 'button';
            toggleBtn.setAttribute('aria-label', 'Buka menu navigasi');
            toggleBtn.className = 'md:hidden inline-flex items-center justify-center w-10 h-10 -ml-2 mr-2 rounded-lg text-gray-600 hover:bg-gray-100 focus:outline-none';
            toggleBtn.innerHTML = '<i class="fas fa-bars text-xl"></i>';
            header.insertBefore(toggleBtn, header.firstChild);
        }

        aside.dataset.drawerBound = 'true';
        aside.classList.add('nusa-auto-drawer');

        const backdrop = document.createElement('div');
        backdrop.className = 'nusa-mobile-backdrop md:hidden';
        backdrop.setAttribute('aria-hidden', 'true');
        document.body.appendChild(backdrop);

        const setDrawerOpen = (isOpen) => {
            aside.classList.toggle('is-open', isOpen);
            backdrop.classList.toggle('is-open', isOpen);
            toggleBtn.setAttribute('aria-expanded', String(isOpen));
        };

        toggleBtn.addEventListener('click', (event) => {
            event.stopPropagation();
            setDrawerOpen(!aside.classList.contains('is-open'));
        });

        backdrop.addEventListener('click', () => setDrawerOpen(false));
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') setDrawerOpen(false);
        });
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 768) setDrawerOpen(false);
        });
    }

    /**
     * Cegah double-submit saat koneksi HP lambat.
     */
    function guardFormSubmissions() {
        document.querySelectorAll('form').forEach((form) => {
            const method = (form.getAttribute('method') || 'GET').toUpperCase();
            if (method === 'GET' || form.hasAttribute('data-no-loading')) return;

            form.addEventListener('submit', (event) => {
                if (typeof form.checkValidity === 'function' && !form.checkValidity()) return;

                if (form.dataset.submitting === 'true') {
                    event.preventDefault();
                    return;
                }

                form.dataset.submitting = 'true';
                const submitBtn = form.querySelector('button[type="submit"]:not([disabled])');

                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
                }

                window.setTimeout(() => {
                    form.dataset.submitting = 'false';
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.classList.remove('opacity-75', 'cursor-not-allowed');
                    }
                }, 8000);
            });
        });
    }

    /**
     * Tombol Kembali ke Atas untuk halaman dengan scroll panjang.
     */
    function initScrollToTop() {
        if (window.getComputedStyle(document.body).overflowY === 'hidden') return;

        const btn = document.createElement('button');
        btn.id = 'nusaScrollTopBtn';
        btn.type = 'button';
        btn.setAttribute('aria-label', 'Kembali ke atas');
        btn.innerHTML = '<i class="fa-solid fa-chevron-up text-sm"></i>';
        document.body.appendChild(btn);

        const updateVisibility = () => {
            btn.classList.toggle('is-visible', window.scrollY > 320);
        };

        btn.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });

        window.addEventListener('scroll', updateVisibility, { passive: true });
        updateVisibility();
    }

    document.addEventListener('DOMContentLoaded', () => {
        try {
            enhanceTables();
            enhanceIframes();
            enhanceLegacySidebarDrawer();
            guardFormSubmissions();
            initScrollToTop();
        } catch (error) {
            console.error('[MobileUX] Inisialisasi gagal:', error);
        }
    });
})();

/* === Dynamic Responsive UX Enhancements === */
document.addEventListener('DOMContentLoaded', () => {
    // 1. Bungkus semua <table> yang belum memiliki container overflow-x-auto
    document.querySelectorAll('table').forEach((table) => {
        const parent = table.parentElement;
        if (!parent) return;
        const style = window.getComputedStyle(parent);
        if (style.overflowX === 'auto' || style.overflowX === 'scroll' || parent.classList.contains('table-responsive-auto')) {
            return;
        }
        const wrapper = document.createElement('div');
        wrapper.className = 'table-responsive-auto';
        parent.insertBefore(wrapper, table);
        wrapper.appendChild(table);
    });

    // 2. Auto-dismiss alert sukses setelah 4.5 detik agar UI tetap bersih
    document.querySelectorAll('[role="alert"], .bg-green-50, .bg-emerald-50').forEach((alertEl) => {
        if (alertEl.dataset.persist === 'true') return;
        setTimeout(() => {
            alertEl.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
            alertEl.style.opacity = '0';
            alertEl.style.transform = 'translateY(-6px)';
            setTimeout(() => alertEl.remove(), 400);
        }, 4500);
    });

    // 3. Resize grafik Chart.js secara dinamis saat ukuran layar / orientasi berubah
    let resizeTimer;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => {
            if (typeof Chart !== 'undefined' && Chart.instances) {
                Object.values(Chart.instances).forEach((chart) => chart.resize());
            }
        }, 150);
    });
});

/* === Redirect Tombol Back ke Halaman Publik Setelah Logout === */
(function () {
    const path = window.location.pathname.replace(/\/+$/, '');
    const isLoginPage = path === '/admin/login' || path === '/login';

    if (isLoginPage && window.history && window.history.pushState) {
        // Sisipkan state agar klik Back di HP/Laptop langsung ke halaman publik (/)
        window.history.pushState({ fromLoginGuard: true }, '', window.location.href);
        window.addEventListener('popstate', () => {
            window.location.replace('/');
        });
    }

    // Jika browser memulihkan halaman dari bfcache saat klik Back, paksa reload
    window.addEventListener('pageshow', (event) => {
        if (event.persisted) {
            window.location.reload();
        }
    });
})();
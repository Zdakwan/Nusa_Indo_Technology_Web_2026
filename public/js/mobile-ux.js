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

/* === Auto-Refresh CSRF Token (Cegah 419 Page Expired di Form) === */
(function () {
    const refreshCsrfToken = async () => {
        const tokenInputs = document.querySelectorAll('input[name="_token"]');
        if (!tokenInputs.length) return;

        try {
            const res = await fetch('/csrf-token', {
                method: 'GET',
                credentials: 'same-origin',
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                cache: 'no-store'
            });
            if (!res.ok) return;

            const data = await res.json();
            if (!data.token) return;

            tokenInputs.forEach((input) => { input.value = data.token; });
            const meta = document.querySelector('meta[name="csrf-token"]');
            if (meta) meta.setAttribute('content', data.token);
        } catch (_) {
            // Abaikan jika sedang offline
        }
    };

    // Perbarui token setiap 10 menit dan saat tab kembali aktif
    setInterval(refreshCsrfToken, 10 * 60 * 1000);
    document.addEventListener('visibilitychange', () => {
        if (document.visibilityState === 'visible') refreshCsrfToken();
    });
})();

/* === Perbaikan Notifikasi Admin & Pop-up Konfirmasi Logout (Admin & Client) === */
document.addEventListener('DOMContentLoaded', () => {
    const NOTIF_STORAGE_KEY = 'nit_admin_notif_read_signature';

    // 1. Cari elemen badge lonceng & dropdown notifikasi
    const findNotifElements = () => {
        const bellBtn = document.querySelector('button:has(svg), a:has(svg), [id*="notif"], [class*="notif"]');
        const badges = Array.from(document.querySelectorAll('header span, nav span')).filter((el) => {
            const txt = (el.textContent || '').trim();
            return /^\d+$/.test(txt) && el.closest('button, a, div');
        });
        return { bellBtn, badges };
    };

    const getNotifSignature = () => {
        const itemText = Array.from(document.querySelectorAll('div, p, li'))
            .map((el) => (el.textContent || '').trim())
            .find((t) => /Ada\s+\d+\s+permintaan/i.test(t));
        return itemText || 'default-notif';
    };

    // Sembunyikan badge jika notifikasi dengan signature yang sama sudah ditandai dibaca
    const applyReadState = () => {
        const saved = localStorage.getItem(NOTIF_STORAGE_KEY);
        if (saved && saved === getNotifSignature()) {
            const { badges } = findNotifElements();
            badges.forEach((b) => { b.style.display = 'none'; });
        }
    };
    applyReadState();

    // Handler klik "Tandai dibaca" & "Lihat Semua"
    document.addEventListener('click', (e) => {
        const target = e.target.closest('a, button, span');
        if (!target) return;

        const text = (target.textContent || '').trim().toLowerCase();

        // A. Tombol "Tandai dibaca" / "Tandai telah dibaca"
        if (text === 'tandai dibaca' || text === 'tandai telah dibaca') {
            const href = target.getAttribute('href');
            if (!href || href === '#' || href.startsWith('javascript:')) {
                e.preventDefault();
            }

            localStorage.setItem(NOTIF_STORAGE_KEY, getNotifSignature());

            // Sembunyikan semua badge angka di header
            const { badges } = findNotifElements();
            badges.forEach((b) => { b.style.display = 'none'; });

            // Beri umpan balik visual pada tombol & item notifikasi
            target.textContent = 'Sudah dibaca';
            target.style.opacity = '0.6';
            target.style.pointerEvents = 'none';

            const dropdown = target.closest('div[class*="shadow"], div[class*="rounded"]');
            if (dropdown) {
                dropdown.querySelectorAll('a, div').forEach((item) => {
                    if (/Konsultasi Baru/i.test(item.textContent || '')) {
                        item.style.opacity = '0.55';
                    }
                });
            }
            return;
        }

        // B. Tombol "Lihat Semua" pada dropdown notifikasi
        if (text === 'lihat semua') {
            const href = target.getAttribute('href');
            if (!href || href === '#' || href.startsWith('javascript:')) {
                e.preventDefault();

                // Tutup dropdown jika terbuka
                const dropdown = target.closest('div[class*="shadow"], div[class*="absolute"]');
                if (dropdown && dropdown.classList.contains('absolute')) {
                    dropdown.classList.add('hidden');
                }

                // Jika sedang di dashboard admin, scroll halus ke tabel daftar aktivitas
                const tableSection = Array.from(document.querySelectorAll('h2, h3, div'))
                    .find((el) => /Daftar Aktivitas Klien Terbaru/i.test(el.textContent || ''));

                if (tableSection && window.location.pathname.includes('/admin/dashboard')) {
                    tableSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                } else {
                    window.location.href = '/admin/dashboard';
                }
            }
        }
    });

    // 2. Pop-up Konfirmasi Logout ("Yakin ingin keluar?") untuk Admin & Client
    let pendingLogoutAction = null;

    const createLogoutModal = () => {
        if (document.getElementById('nitLogoutConfirmModal')) {
            return document.getElementById('nitLogoutConfirmModal');
        }

        const modal = document.createElement('div');
        modal.id = 'nitLogoutConfirmModal';
        modal.setAttribute('role', 'dialog');
        modal.setAttribute('aria-modal', 'true');
        modal.style.cssText = [
            'position:fixed', 'inset:0', 'z-index:99999', 'display:none',
            'align-items:center', 'justify-content:center', 'padding:1rem',
            'background:rgba(15, 23, 42, 0.55)', 'backdrop-filter:blur(2px)'
        ].join(';');

        modal.innerHTML = `
            <div style="background:#ffffff;border-radius:1rem;max-width:380px;width:100%;padding:1.5rem;box-shadow:0 20px 25px -5px rgba(0,0,0,0.2);text-align:center;font-family:inherit;">
                <div style="width:48px;height:48px;margin:0 auto 0.875rem;border-radius:9999px;background:#fee2e2;color:#dc2626;display:flex;align-items:center;justify-content:center;font-size:1.25rem;font-weight:700;">!</div>
                <h3 style="margin:0 0 0.375rem;font-size:1.125rem;font-weight:700;color:#1e293b;">Konfirmasi Keluar</h3>
                <p style="margin:0 0 1.25rem;font-size:0.9rem;color:#64748b;">Yakin ingin keluar?</p>
                <div style="display:flex;gap:0.75rem;justify-content:center;">
                    <button type="button" id="nitLogoutCancelBtn" style="flex:1;padding:0.625rem 1rem;border-radius:0.625rem;border:1px solid #cbd5e1;background:#ffffff;color:#334155;font-weight:600;font-size:0.875rem;cursor:pointer;">Batal</button>
                    <button type="button" id="nitLogoutConfirmBtn" style="flex:1;padding:0.625rem 1rem;border-radius:0.625rem;border:none;background:#dc2626;color:#ffffff;font-weight:600;font-size:0.875rem;cursor:pointer;">Ya, Keluar</button>
                </div>
            </div>
        `;

        document.body.appendChild(modal);

        const closeModal = () => {
            modal.style.display = 'none';
            pendingLogoutAction = null;
        };

        modal.querySelector('#nitLogoutCancelBtn').addEventListener('click', closeModal);
        modal.addEventListener('click', (ev) => {
            if (ev.target === modal) closeModal();
        });
        document.addEventListener('keydown', (ev) => {
            if (ev.key === 'Escape' && modal.style.display === 'flex') closeModal();
        });

        modal.querySelector('#nitLogoutConfirmBtn').addEventListener('click', () => {
            const action = pendingLogoutAction;
            closeModal();
            if (typeof action === 'function') action();
        });

        return modal;
    };

    const showLogoutModal = (onConfirm) => {
        const modal = createLogoutModal();
        pendingLogoutAction = onConfirm;
        modal.style.display = 'flex';
    };

    // Tangkap submit form logout (Admin & Client)
    document.addEventListener('submit', (e) => {
        const form = e.target;
        if (!(form instanceof HTMLFormElement)) return;
        const actionUrl = (form.getAttribute('action') || '').toLowerCase();

        if (actionUrl.includes('logout') && form.dataset.logoutConfirmed !== 'true') {
            e.preventDefault();
            showLogoutModal(() => {
                form.dataset.logoutConfirmed = 'true';
                form.submit();
            });
        }
    }, true);

    // Tangkap klik tautan <a href="...logout..."> jika ada yang memakai GET/link
    document.addEventListener('click', (e) => {
        const link = e.target.closest('a[href]');
        if (!link) return;
        const href = (link.getAttribute('href') || '').toLowerCase();

        if (href.includes('logout') && link.dataset.logoutConfirmed !== 'true') {
            e.preventDefault();
            showLogoutModal(() => {
                link.dataset.logoutConfirmed = 'true';
                window.location.href = link.href;
            });
        }
    }, true);
});

/* === Perbaikan Tinggi Panel Biru Carousel & Jarak Tombol Panah di HP === */
document.addEventListener('DOMContentLoaded', () => {
    const headings = Array.from(document.querySelectorAll('h1, h2, h3, h4')).filter((el) =>
        /Membangun Ekosistem Digital|Politeknik Negeri Jember PSDKU Sidoarjo/i.test(el.textContent || '')
    );

    if (!headings.length) return;

    headings.forEach((heading) => {
        // Cari container panel biru yang membungkus judul
        let bluePanel = heading.parentElement;
        while (bluePanel && bluePanel !== document.body) {
            const bg = window.getComputedStyle(bluePanel).backgroundColor;
            if (bg && bg !== 'rgba(0, 0, 0, 0)' && bg !== 'rgb(255, 255, 255)') {
                break;
            }
            bluePanel = bluePanel.parentElement;
        }

        if (!bluePanel || bluePanel === document.body) return;

        const slide = bluePanel.parentElement;
        const blueBg = window.getComputedStyle(bluePanel).backgroundColor;

        // Pastikan slide meregang penuh dengan warna latar yang sama agar tidak ada celah putih
        if (slide) {
            slide.style.display = 'flex';
            slide.style.flexDirection = 'column';
            slide.style.height = 'auto';
            slide.style.minHeight = '100%';
            slide.style.backgroundColor = blueBg;
        }

        bluePanel.style.flex = '1 1 auto';
        bluePanel.style.display = 'flex';
        bluePanel.style.flexDirection = 'column';
        bluePanel.style.justifyContent = 'center';

        // Di layar HP, beri padding kiri-kanan agar tombol panah < > tidak menutupi teks judul
        if (window.innerWidth <= 767) {
            bluePanel.style.paddingLeft = '3.25rem';
            bluePanel.style.paddingRight = '3.25rem';
        }
    });
});

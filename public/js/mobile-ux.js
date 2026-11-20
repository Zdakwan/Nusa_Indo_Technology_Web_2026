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


/* === Perbaikan Tinggi Panel Biru Carousel Khusus Layar HP (Desktop Tetap 2 Kolom) === */
document.addEventListener('DOMContentLoaded', () => {
    const applyCarouselLayout = () => {
        const isMobile = window.innerWidth <= 767;
        const headings = Array.from(document.querySelectorAll('h1, h2, h3, h4')).filter((el) =>
            /Membangun Ekosistem Digital|Politeknik Negeri Jember PSDKU Sidoarjo/i.test(el.textContent || '')
        );

        headings.forEach((heading) => {
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

            if (isMobile) {
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
                bluePanel.style.paddingLeft = '2.5rem';
                bluePanel.style.paddingRight = '2.5rem';
            } else {
                // Reset total di desktop agar gambar & teks tampil berdampingan normal
                if (slide) {
                    slide.style.display = '';
                    slide.style.flexDirection = '';
                    slide.style.height = '';
                    slide.style.minHeight = '';
                    slide.style.backgroundColor = '';
                }
                bluePanel.style.flex = '';
                bluePanel.style.display = '';
                bluePanel.style.flexDirection = '';
                bluePanel.style.justifyContent = '';
                bluePanel.style.paddingLeft = '';
                bluePanel.style.paddingRight = '';
            }
        });
    };

    applyCarouselLayout();
    window.addEventListener('resize', applyCarouselLayout);

    /* === Lengkapi Ikon Kartu Dashboard Client (Permintaan Konsultasi & Riwayat Konsultasi) === */
    const clientCardsConfig = [
        {
            title: 'Permintaan Konsultasi',
            bg: '#fef3c7',
            color: '#d97706',
            svg: '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>'
        },
        {
            title: 'Riwayat Konsultasi',
            bg: '#dbeafe',
            color: '#2563eb',
            svg: '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>'
        }
    ];

    clientCardsConfig.forEach((cfg) => {
        const labelEl = Array.from(document.querySelectorAll('p, h3, h4, span, div')).find(
            (el) => el.children.length === 0 && (el.textContent || '').trim() === cfg.title
        );
        if (!labelEl) return;

        // Naik ke container kartu terdekat
        let card = labelEl.parentElement;
        while (card && card !== document.body) {
            const cls = card.className || '';
            if (typeof cls === 'string' && (cls.includes('rounded') || cls.includes('shadow') || cls.includes('bg-white'))) {
                break;
            }
            card = card.parentElement;
        }
        if (!card || card === document.body) return;

        // Jika kartu sudah punya ikon SVG/FontAwesome di dalamnya, lewati
        if (card.querySelector('.nit-client-card-icon, svg:not([class*="wave"]), i[class*="fa-"]')) return;

        if (window.getComputedStyle(card).position === 'static') {
            card.style.position = 'relative';
        }

        const iconBox = document.createElement('div');
        iconBox.className = 'nit-client-card-icon';
        iconBox.style.cssText = `position:absolute;top:1.25rem;right:1.25rem;width:2.5rem;height:2.5rem;border-radius:0.75rem;background:${cfg.bg};color:${cfg.color};display:flex;align-items:center;justify-content:center;pointer-events:none;`;
        iconBox.innerHTML = cfg.svg;
        card.appendChild(iconBox);
    });
});

/* === Fallback Klik Tombol Garis 3 (Sidebar) & Foto Profil di Browser HP === */
document.addEventListener('DOMContentLoaded', () => {
    // 1. Rapikan teks stepper "Pengajuan / Diverifikasi / Dijadwalkan / Selesai" agar tidak bertumpuk di HP
    const stepWords = ['Pengajuan', 'Diverifikasi', 'Dijadwalkan', 'Selesai'];
    document.querySelectorAll('p, span, div, h4, h5').forEach((el) => {
        if (el.children.length === 0 && stepWords.includes((el.textContent || '').trim())) {
            el.classList.add('nit-stepper-label');
        }
    });

    const header = document.querySelector('header');
    if (!header) return;

    // 2. Fallback tombol garis 3 (hamburger) di pojok kiri header
    const aside = document.querySelector('aside, .sidebar');
    const hamburgerBtn = Array.from(header.querySelectorAll('button')).find((btn) => {
        const rect = btn.getBoundingClientRect();
        return rect.width > 0 && rect.left < window.innerWidth / 2;
    });

    if (hamburgerBtn && aside) {
        const ensureBackdrop = () => {
            let bd = document.getElementById('nit-mobile-sidebar-backdrop');
            if (!bd) {
                bd = document.createElement('div');
                bd.id = 'nit-mobile-sidebar-backdrop';
                bd.style.cssText = 'position:fixed;inset:0;background:rgba(15,23,42,0.45);z-index:55;display:none;';
                bd.addEventListener('click', () => closeSidebarFallback());
                document.body.appendChild(bd);
            }
            return bd;
        };

        const isSidebarVisible = () => {
            const st = window.getComputedStyle(aside);
            const rect = aside.getBoundingClientRect();
            return st.display !== 'none' && st.visibility !== 'hidden' && rect.right > 20;
        };

        const closeSidebarFallback = () => {
            aside.classList.add('-translate-x-full');
            aside.classList.remove('translate-x-0');
            if (aside.dataset.nitForcedOpen === '1') {
                aside.style.transform = '';
                aside.style.display = '';
                delete aside.dataset.nitForcedOpen;
            }
            const bd = document.getElementById('nit-mobile-sidebar-backdrop');
            if (bd) bd.style.display = 'none';
        };

        hamburgerBtn.addEventListener('click', () => {
            if (window.innerWidth >= 1024) return;
            const wasVisible = isSidebarVisible();

            // Tunggu 60ms: jika handler bawaan halaman tidak mengubah status sidebar, jalankan fallback
            setTimeout(() => {
                if (isSidebarVisible() === wasVisible) {
                    const bd = ensureBackdrop();
                    if (!wasVisible) {
                        aside.dataset.nitForcedOpen = '1';
                        aside.classList.remove('hidden', '-translate-x-full');
                        aside.classList.add('translate-x-0');
                        aside.style.display = 'flex';
                        aside.style.position = 'fixed';
                        aside.style.top = '0';
                        aside.style.left = '0';
                        aside.style.bottom = '0';
                        aside.style.transform = 'translateX(0)';
                        bd.style.display = 'block';
                    } else {
                        closeSidebarFallback();
                    }
                }
            }, 60);
        });
    }

    // 3. Fallback klik foto profil di pojok kanan header
    const profileTrigger = header.querySelector('a img, button img')?.closest('a, button')
        || Array.from(header.querySelectorAll('img')).find((img) => {
            const rect = img.getBoundingClientRect();
            return rect.width > 0 && rect.left > window.innerWidth / 2;
        });

    if (profileTrigger) {
        profileTrigger.style.cursor = 'pointer';
        profileTrigger.addEventListener('click', () => {
            if (profileTrigger.tagName === 'A' && profileTrigger.getAttribute('href') && profileTrigger.getAttribute('href') !== '#') {
                return;
            }

            const wrapper = profileTrigger.closest('.relative') || profileTrigger.parentElement;
            const dropdown = wrapper ? wrapper.querySelector('[x-show], .dropdown-menu, [role="menu"], div.absolute') : null;

            if (dropdown) {
                const wasHidden = window.getComputedStyle(dropdown).display === 'none' || dropdown.classList.contains('hidden');
                setTimeout(() => {
                    const stillHidden = window.getComputedStyle(dropdown).display === 'none' || dropdown.classList.contains('hidden');
                    if (stillHidden === wasHidden) {
                        dropdown.classList.toggle('hidden', !wasHidden);
                        dropdown.style.display = wasHidden ? 'block' : 'none';
                    }
                }, 60);
            } else {
                // Jika tidak ada dropdown di halaman ini, arahkan ke halaman profil dari link sidebar
                const profileLink = document.querySelector('aside a[href*="profil"], aside a[href*="profile"], aside a[href*="pengaturan"]');
                if (profileLink && profileLink.href) {
                    window.location.href = profileLink.href;
                }
            }
        });
    }
});

/* === Penanda Otomatis Layout Mobile (Stepper, Tabel Konsultasi, Detail Proyek, Notifikasi) === */
document.addEventListener('DOMContentLoaded', () => {
    // 1. Stepper Status Pendaftaran Terbaru
    const statusHeading = Array.from(document.querySelectorAll('h2, h3, h4, p, div')).find(
        (el) => el.children.length === 0 && (el.textContent || '').trim() === 'Status Pendaftaran Terbaru'
    );
    if (statusHeading) {
        const card = statusHeading.closest('div[class*="rounded"], section') || statusHeading.parentElement;
        if (card) {
            const firstLabel = Array.from(card.querySelectorAll('p, span, div')).find(
                (el) => el.children.length === 0 && (el.textContent || '').trim() === 'Pengajuan'
            );
            if (firstLabel) {
                let track = firstLabel.parentElement;
                while (track && track !== card) {
                    const txt = track.textContent || '';
                    if (txt.includes('Pengajuan') && txt.includes('Diverifikasi') && txt.includes('Selesai')) {
                        track.classList.add('nit-stepper-track');
                        if (track.parentElement) {
                            track.parentElement.classList.add('nit-stepper-scroll');
                        }
                        break;
                    }
                    track = track.parentElement;
                }
            }
        }
    }

    // 2. Kartu Tabel Daftar Konsultasi
    const daftarHeading = Array.from(document.querySelectorAll('h2, h3, h4, p, div')).find(
        (el) => el.children.length === 0 && (el.textContent || '').trim() === 'Daftar Konsultasi'
    );
    if (daftarHeading) {
        const card = daftarHeading.closest('div[class*="rounded"], section');
        if (card) card.classList.add('nit-compact-table-card');
    }

    // 3. Tombol Aksi Detail Proyek (Export PDF & Kembali ke Proyek Saya)
    const exportBtn = Array.from(document.querySelectorAll('a, button')).find(
        (el) => /Export\s*PDF/i.test(el.textContent || '')
    );
    if (exportBtn && exportBtn.parentElement) {
        exportBtn.parentElement.classList.add('nit-proyek-actions');
    }

    // 4. Item Notifikasi agar teks tidak terjepit badge "Sudah dibaca"
    document.querySelectorAll('span, button, div, a').forEach((badge) => {
        if (badge.children.length > 1) return;
        const text = (badge.textContent || '').trim();
        if (text !== 'Sudah dibaca' && text !== 'Tandai dibaca') return;

        let row = badge.parentElement;
        while (row && row !== document.body) {
            const st = window.getComputedStyle(row);
            if (st.display === 'flex' && row.children.length >= 2 && row.getBoundingClientRect().width > 240) {
                row.classList.add('nit-notif-item');
                const directChildren = Array.from(row.children);
                const badgeContainer = directChildren.find((child) => child === badge || child.contains(badge));
                if (badgeContainer) {
                    badgeContainer.classList.add('nit-notif-badge-wrap');
                }
                directChildren.forEach((child) => {
                    if (child !== badgeContainer && !child.classList.contains('nit-notif-badge-wrap')) {
                        if (child.getBoundingClientRect().width > 60) {
                            child.classList.add('nit-notif-body');
                        }
                    }
                });
                break;
            }
            row = row.parentElement;
        }
    });
});

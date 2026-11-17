document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.getElementById('sidebar');
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const closeSidebarBtn = document.getElementById('closeSidebarBtn');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    const dropdownButtons = document.querySelectorAll('[data-dropdown-toggle]');
    const dashboardLink = document.getElementById('linkDashboard');

    // ==========================================
    // LOGIKA SIDEBAR MOBILE RESPONSIVE
    // ==========================================

    // Fungsi Buka Sidebar Mobile
    function openSidebar() {
        if(sidebar && sidebarOverlay) {
            sidebar.classList.remove('-translate-x-full');
            sidebarOverlay.classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // cegah scroll background
        }
    }

    // Fungsi Tutup Sidebar Mobile
    function closeSidebar() {
        if(sidebar && sidebarOverlay) {
            sidebar.classList.add('-translate-x-full');
            sidebarOverlay.classList.add('hidden');
            document.body.style.overflow = 'auto'; // kembalikan scroll
        }
    }

    // Event Listeners untuk interaksi Sidebar Mobile
    if (mobileMenuBtn) {
        mobileMenuBtn.addEventListener('click', openSidebar);
    }

    if (closeSidebarBtn) {
        closeSidebarBtn.addEventListener('click', closeSidebar);
    }

    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', closeSidebar); // Tutup saat klik di luar sidebar
    }


    // ==========================================
    // LOGIKA DROPDOWN MENU
    // ==========================================
    function setActive(element, isActive) {
        if (element) {
            element.classList.toggle('is-active', isActive);
        }
    }

    dropdownButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            const menu = document.getElementById(button.dataset.dropdownToggle);
            if(!menu) return;

            const chevron = button.querySelector('.chevron');
            const willOpen = menu.classList.contains('hidden');

            dropdownButtons.forEach(function (b) {
                setActive(b, false);
            });

            menu.classList.toggle('hidden');
            if(chevron) chevron.classList.toggle('rotate-180', willOpen);

            if (willOpen) {
                setActive(button, true);
                setActive(dashboardLink, false);
            }
        });
    });
});

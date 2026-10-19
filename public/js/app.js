// public/js/app.js
document.addEventListener('DOMContentLoaded', function () {
    // Ikon Lucide
    if (window.lucide) {
        window.lucide.createIcons();
    }

    // Toggle generik (mobile menu, dsb.)
    document.querySelectorAll('[data-toggle]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var target = document.querySelector(btn.getAttribute('data-toggle'));
            if (target) target.classList.toggle('hidden');
        });
    });

    // Sidebar admin (mobile)
    var sidebar = document.getElementById('sidebar');
    var overlay = document.getElementById('sidebar-overlay');

    document.querySelectorAll('[data-open-sidebar]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            if (sidebar) sidebar.classList.remove('-translate-x-full');
            if (overlay) overlay.classList.remove('hidden');
        });
    });

    function closeSidebar() {
        if (sidebar) sidebar.classList.add('-translate-x-full');
        if (overlay) overlay.classList.add('hidden');
    }
    document.querySelectorAll('[data-close-sidebar]').forEach(function (el) {
        el.addEventListener('click', closeSidebar);
    });

    // Tombol dismiss alert
    document.querySelectorAll('[data-dismiss]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var alert = btn.closest('[data-autohide], .alert-success, .alert-error');
            if (alert) alert.remove();
        });
    });

    // Auto hide alert setelah 7 detik
    document.querySelectorAll('[data-autohide]').forEach(function (el) {
        setTimeout(function () {
            el.style.transition = 'opacity .4s ease';
            el.style.opacity = '0';
            setTimeout(function () { el.remove(); }, 400);
        }, 7000);
    });

    // Konfirmasi form
    document.querySelectorAll('form[data-confirm]').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            if (!window.confirm(form.getAttribute('data-confirm'))) {
                e.preventDefault();
            }
        });
    });

    // Format input nominal (hanya angka)
    document.querySelectorAll('input[data-currency]').forEach(function (input) {
        input.addEventListener('input', function () {
            var digits = input.value.replace(/[^0-9]/g, '');
            input.value = digits ? new Intl.NumberFormat('id-ID').format(digits) : '';
        });
    });

    // Tutup sidebar saat link diklik (mobile)
    document.querySelectorAll('#sidebar a').forEach(function (link) {
        link.addEventListener('click', function () {
            if (window.innerWidth < 1024) closeSidebar();
        });
    });
});

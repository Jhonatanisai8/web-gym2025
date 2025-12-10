// Main JavaScript
document.addEventListener('DOMContentLoaded', function () {

    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });

    // Confirm delete actions
    const deleteButtons = document.querySelectorAll('[data-confirm]');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            if (!confirm(this.dataset.confirm)) {
                e.preventDefault();
            }
        });
    });

    // Active navigation highlighting
    const currentPath = window.location.pathname;
    const navItems = document.querySelectorAll('.nav-item');
    navItems.forEach(item => {
        if (item.getAttribute('href') && currentPath.includes(item.getAttribute('href'))) {
            navItems.forEach(nav => nav.classList.remove('active'));
            item.classList.add('active');
        }
    });

});

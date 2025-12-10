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
    
    // Remove all active classes first
    navItems.forEach(nav => nav.classList.remove('active'));
    
    // Find and activate the matching nav item
    let matched = false;
    navItems.forEach(item => {
        const href = item.getAttribute('href');
        if (href) {
            // Extract the route from the href (e.g., 'clientes', 'dashboard')
            const route = href.split('/').filter(Boolean).pop();
            
            // Check if current path includes this route
            if (route && currentPath.includes(route)) {
                item.classList.add('active');
                matched = true;
            }
        }
    });
    
    // If no match found, activate dashboard (default)
    if (!matched) {
        const dashboardItem = document.querySelector('.nav-item[href*="dashboard"]');
        if (dashboardItem) {
            dashboardItem.classList.add('active');
        }
    }

});

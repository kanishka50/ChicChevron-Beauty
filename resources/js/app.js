import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Admin specific JavaScript
if (window.location.pathname.startsWith('/admin')) {
    import('./admin/dashboard.js');
}

Alpine.start();
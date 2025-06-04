import axios from 'axios';
import Alpine from 'alpinejs';
import focus from '@alpinejs/focus';
import collapse from '@alpinejs/collapse';
import persist from '@alpinejs/persist';

window.axios = axios;
window.Alpine = Alpine;

// Initialize Alpine.js plugins
Alpine.plugin(focus);
Alpine.plugin(collapse);
Alpine.plugin(persist);

// Start Alpine.js
Alpine.start();

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

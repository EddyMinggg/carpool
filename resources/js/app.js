import '../css/app.css';
import './bootstrap';

import Alpine from 'alpinejs';

// 檢查是否已經有 jQuery，如果沒有才導入
if (typeof window.$ === 'undefined') {
    import('jquery').then((jQuery) => {
        window.$ = jQuery.default;
        window.jQuery = jQuery.default;
        console.log('jQuery loaded via app.js');
    });
} else {
    console.log('jQuery already loaded, skipping app.js import');
}

window.Alpine = Alpine;

Alpine.start();

import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

import Swup from 'swup';
import SwupHeadPlugin from '@swup/head-plugin';
import SwupScriptsPlugin from '@swup/scripts-plugin';

const swup = new Swup({
    plugins: [
        new SwupHeadPlugin(),
        new SwupScriptsPlugin()
    ]
});

let loaderTimeout = null;

swup.hooks.on('visit:start', () => {
    loaderTimeout = setTimeout(() => {
        window.dispatchEvent(new CustomEvent('show-loader'));
    }, 250);
});

swup.hooks.on('visit:end', () => {
    clearTimeout(loaderTimeout);
    window.dispatchEvent(new CustomEvent('hide-loader'));
});

swup.hooks.on('page:view', () => {
    const currentUrl = window.location.href.split('?')[0]; // Ignorar query params
    
    document.querySelectorAll('.nav-link-item').forEach(link => {
        link.classList.remove('nav-item-active', 'text-white');
        
        if (link.href && currentUrl.startsWith(link.href)) {
            link.classList.add('nav-item-active', 'text-white');
        }
    });
});

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
        new SwupScriptsPlugin({ optin: true }),
    ],
    // Animar el header y el contenido principal
    containers: ['#swup-header', '#swup'],
    // Ya no ignoramos links en formularios (para que "Limpiar" y paginación sean fluidos)
});
window.swup = swup;

// Interceptar formularios GET para que usen Swup y sean fluidos (Filtros)
document.addEventListener('submit', (e) => {
    if (e.target && e.target.method && e.target.method.toUpperCase() === 'GET') {
        // Solo interceptar si el form no tiene un atributo data-no-swup
        if (!e.target.hasAttribute('data-no-swup')) {
            e.preventDefault();
            const url = new URL(e.target.action || window.location.href);
            const params = new URLSearchParams(new FormData(e.target));
            url.search = params.toString();
            swup.navigate(url.pathname + url.search);
        }
    }
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

// Re-inicializar Alpine en el contenido nuevo de Swup
swup.hooks.on('content:replace', () => {
    // Inicializar los componentes Alpine del nuevo DOM
    document.querySelectorAll('#swup [x-data]').forEach(el => {
        Alpine.initTree(el);
    });
});

swup.hooks.on('page:view', () => {
    const currentUrl = window.location.href.split('?')[0];
    
    document.querySelectorAll('.nav-link-item').forEach(link => {
        link.classList.remove('nav-item-active', 'text-white');
        
        if (link.href && currentUrl.startsWith(link.href)) {
            link.classList.add('nav-item-active', 'text-white');
        }
    });
});

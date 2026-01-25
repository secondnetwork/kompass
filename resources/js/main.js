// resources/js/app.js

import '/resources/css/kompass.css'
import 'preline'
import click_to_edit from './alpine/click_to_edit'

// import '@nextapps-be/livewire-sortablejs';
import * as editorjs from './editorjs';

if (document.getElementsByClassName('embed-video')) {
  const { app } = import('./plugins/lite-yt-embed')
  const { appvimeo } = import('./plugins/lite-vimeo-embed')
  // app();
}

/**
 * =================================================================
 * Theme Manager (Dark/Light/System Mode) mit data-theme
 * =================================================================
 */
const themeManager = {
    // Diese Funktion synchronisiert das UI (HTML-Attribut und Buttons)
    // basierend auf dem aktuellen Zustand im localStorage.
    sync() {
        const appearance = localStorage.getItem('appearance') || 'system';
        
        // 1. Theme auf dem <html>-Tag anwenden
        if (appearance === 'system') {
            const media = window.matchMedia('(prefers-color-scheme: dark)');
            document.documentElement.setAttribute('data-theme', media.matches ? 'dark' : 'light');
        } else {
            document.documentElement.setAttribute('data-theme', appearance);
        }

        // 2. Zustand der Buttons aktualisieren
        document.querySelectorAll('button[onclick^="setAppearance"]').forEach((button) => {
            button.setAttribute('aria-pressed', String(appearance === button.value));
        });
    },

    // Diese Funktion wird von den Buttons (onclick) aufgerufen.
    // Sie ändert nur den Zustand im localStorage und ruft dann sync() auf.
    set(appearance) {
        if (appearance === 'system') {
            localStorage.removeItem('appearance');
        } else {
            localStorage.setItem('appearance', appearance);
        }
        this.sync();
    }
};

// Die 'set' Funktion global verfügbar machen, damit onclick="setAppearance(...)" funktioniert
window.setAppearance = (appearance) => themeManager.set(appearance);

// 1. Theme sofort beim ersten Laden des Skripts anwenden
// themeManager.sync();


/**
 * =================================================================
 * Event Listeners & Initialisierungen
 * =================================================================
 */

// Wir bündeln alle Aktionen, die nach einer Livewire-Navigation
// ausgeführt werden müssen, in einem einzigen Listener.
// document.addEventListener('livewire:navigated', () => {
//     // Preline-Komponenten neu initialisieren
//     window.HSStaticMethods.autoInit();
//     window.HSAccordion.autoInit();
//     window.HSDropdown.autoInit();
//     window.HSOverlay.autoInit();
//     window.HSSelect.autoInit();
//     console.log('Preline components re-initialized.');
    
//     // Theme-Buttons neu synchronisieren, falls die Seite gewechselt hat
//     themeManager.sync();
//     console.log('Theme buttons synced.');
// });


// AlpineJS Store
Alpine.store('showside', {
  on: false,
  toggle() {
      this.on = ! this.on
  }
});
window.click_to_edit = click_to_edit;
// Click-to-edit Funktion
// Initialize dashboard charts module (Chart.js based)
import './dashboard-charts.js';

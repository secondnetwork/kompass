// resources/js/app.js

import '/resources/css/kompass.css'
import click_to_edit from './alpine/click_to_edit'
import { passkey_authenticate, passkey_register } from './passkeys'

// Register with Alpine.data
Alpine.data('click_to_edit', click_to_edit);
Alpine.data('passkey_authenticate', passkey_authenticate);
Alpine.data('passkey_register', passkey_register);

// import '@nextapps-be/livewire-sortablejs';

if (document.getElementsByClassName('embed-video')) {
  const { app } = import('./plugins/lite-yt-embed')
  const { appvimeo } = import('./plugins/lite-vimeo-embed')
  // app();
}

/**
 * =================================================================
 * Theme Manager
 * Light is the system-wide default. Dark is opt-in per user via the
 * profile settings — the chosen theme is persisted on the User model
 * and the server renders <html data-theme="..."> on every request.
 * This script only handles the live in-page switch when the user
 * toggles the radio in /profile, before the next navigation.
 * =================================================================
 */
window.setAppearance = (appearance) => {
    const next = appearance === 'dark' ? 'dark' : 'light';
    document.documentElement.setAttribute('data-theme', next);
};


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

// Dashboard Charts - Lazy Loading (nur laden wenn Charts auf der Seite sind)
if (document.getElementById('chartPagesSparkline') || 
    document.getElementById('area-chart') || 
    document.getElementById('grid-chart')) {
  import('./dashboard-charts.js');
}

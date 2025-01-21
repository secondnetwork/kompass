import '/resources/css/kompass.css'
import 'preline'
import click_to_edit from './alpine/click_to_edit'

import '@nextapps-be/livewire-sortablejs';
import * as editorjs from './editorjs';

if (document.getElementsByClassName('embed-video')) {
  const { app } = import('./plugins/lite-yt-embed')
  const { appvimeo } = import('./plugins/lite-vimeo-embed')
  // app();
}

var themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
var themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

// Change the icons inside the button based on previous settings
if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
    themeToggleLightIcon.classList.remove('hidden');
} else {
    themeToggleDarkIcon.classList.remove('hidden');
}

var themeToggleBtn = document.getElementById('theme-toggle');

themeToggleBtn.addEventListener('click', function() {

    // toggle icons inside button
    themeToggleDarkIcon.classList.toggle('hidden');
    themeToggleLightIcon.classList.toggle('hidden');

    // if set via local storage previously
    if (localStorage.getItem('color-theme')) {
        if (localStorage.getItem('color-theme') === 'light') {
            document.documentElement.classList.add('dark');
            localStorage.setItem('color-theme', 'dark');
        } else {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('color-theme', 'light');
        }

    // if NOT set via local storage previously
    } else {
        if (document.documentElement.classList.contains('dark')) {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('color-theme', 'light');
        } else {
            document.documentElement.classList.add('dark');
            localStorage.setItem('color-theme', 'dark');
        }
    }
    
});
// const html = document.querySelector('html');
// const isLightOrAuto = localStorage.getItem('hs_theme') === 'light' || (localStorage.getItem('hs_theme') === 'auto' && !window.matchMedia('(prefers-color-scheme: dark)').matches);
// const isDarkOrAuto = localStorage.getItem('hs_theme') === 'dark' || (localStorage.getItem('hs_theme') === 'auto' && window.matchMedia('(prefers-color-scheme: dark)').matches);

// if (isLightOrAuto && html.classList.contains('dark')) html.classList.remove('dark');
// else if (isDarkOrAuto && html.classList.contains('light')) html.classList.remove('light');
// else if (isDarkOrAuto && !html.classList.contains('dark')) html.classList.add('dark');
// else if (isLightOrAuto && !html.classList.contains('light')) html.classList.add('light');

document.addEventListener('livewire:navigated', () => {
  window.HSStaticMethods.autoInit();
  window.HSAccordion.autoInit();
  window.HSDropdown.autoInit();
  window.HSOverlay.autoInit();
  window.HSSelect.autoInit();
  console.log('init');
})

Alpine.store('showside', {
  on: false,

  toggle() {
      this.on = ! this.on
  }
})

window.click_to_edit = click_to_edit;
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



import '/resources/css/kompass.css'
import 'preline'
import click_to_edit from './alpine/click_to_edit'

// import * as livewiresortable from './livewire.sortable';
import '@nextapps-be/livewire-sortablejs';
import * as editorjs from './editorjs';

if (document.getElementsByClassName('embed-video')) {
  const { app } = import('./plugins/lite-yt-embed')
  const { appvimeo } = import('./plugins/lite-vimeo-embed')
  // app();
}

document.addEventListener('livewire:navigated', () => {
  window.HSStaticMethods.autoInit();
  window.HSAccordion.autoInit();
  window.HSDropdown.autoInit();
  window.HSOverlay.autoInit();
  window.HSSelect.autoInit();
  console.log('init');
})
 // This code should be added to <head>.
// It's used to prevent page load glitches.
// const html = document.querySelector('html');
// const isLightOrAuto = localStorage.getItem('hs_theme') === 'light' || (localStorage.getItem('hs_theme') === 'auto' && !window.matchMedia('(prefers-color-scheme: dark)').matches);
// const isDarkOrAuto = localStorage.getItem('hs_theme') === 'dark' || (localStorage.getItem('hs_theme') === 'auto' && window.matchMedia('(prefers-color-scheme: dark)').matches);

// if (isLightOrAuto && html.classList.contains('dark')) html.classList.remove('dark');
// else if (isDarkOrAuto && html.classList.contains('light')) html.classList.remove('light');
// else if (isDarkOrAuto && !html.classList.contains('dark')) html.classList.add('dark');
// else if (isLightOrAuto && !html.classList.contains('light')) html.classList.add('light');

// const { livewiresortable } = import('./livewire.sortable');

// const { editorjs } = import('./editorjs');

// document.addEventListener('livewire:init', () => {
//   livewiresortable();
//   editorjs()
//   console.log('go');

// })
// Alpine.start();
// Livewire.start();
// if (document.getElementsByClassName('kompass-admin-dashboard')) {

//   livewiresortable();
//   editorjs()
 
//   console.log('goss');
//     // const { app } = import('./plugins/lite-yt-embed')
//     // app();
// }


Alpine.store('showside', {
  on: false,

  toggle() {
      this.on = ! this.on
  }
})

// Alpine.store('darkMode', {
//   on: false,

//   toggle() {
//       this.on = ! this.on
//   }
// })



window.click_to_edit = click_to_edit;
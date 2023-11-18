
import '/resources/css/kompass.css'

import click_to_edit from './alpine/click_to_edit'

import * as livewiresortable from './livewire.sortable';

import * as editorjs from './editorjs';

if (document.getElementsByClassName('embed-video')) {
  const { app } = import('./plugins/lite-yt-embed')
  const { appvimeo } = import('./plugins/lite-vimeo-embed')
  // app();
}
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
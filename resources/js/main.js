
import '/resources/css/kompass.css'

import click_to_edit from './alpine/click_to_edit'

import * as livewiresortable from './livewire.sortable';

import * as editorjs from './editorjs';
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
window.click_to_edit = click_to_edit;
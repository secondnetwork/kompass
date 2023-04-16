// import "@fontsource/inter/latin.css"
import '/resources/css/kompass.css'
import Alpine from 'alpinejs'
import collapse from '@alpinejs/collapse'
import Clipboard from '@ryangjchandler/alpine-clipboard'
import click_to_edit from './alpine/click_to_edit'
// import quill from 'quill'


import quill from './quill';

// if (document.getElementById('parent')) {
//     const { app } = import('./plugins/lite-yt-embed')
//     app();
// }

import '@nextapps-be/livewire-sortablejs'


Alpine.plugin(collapse,Clipboard)

Alpine.plugin(Clipboard.configure({
  onCopy: () => {
      console.log('Copied!')
  }
}))

// document.addEventListener('alpine:init', () => {
//     Alpine.data('offcanvasmedia', () => ({
//         open: false,

//         toggle() {
//             this.open = ! this.open
//         },
//     }))
// })


window.click_to_edit = click_to_edit;
// window.Quill = quill;
window.Alpine = Alpine;
document.addEventListener('alpine:init', () => {

  Alpine.data('quill', quill);

});
Alpine.start()

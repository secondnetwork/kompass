
import '/resources/css/kompass.css'
import click_to_edit from './alpine/click_to_edit'

import quill from './quill';
import * as livewiresortable from './livewire.sortable';

import * as editorjs from './editorjs';

// const { livewiresortable } = await import.meta.glob('./livewire.sortable');

// const { editorjs } = await import.meta.glob('./editorjs');


// livewiresortable();
// if (document.getElementById('parent')) {
//     const { app } = import('./plugins/lite-yt-embed')
//     app();
// }

document.addEventListener('alpine:init', () => {

  Alpine.data('quill', quill);

});
window.click_to_edit = click_to_edit;
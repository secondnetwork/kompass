// import "@fontsource/inter/latin.css"
import '/resources/css/kompass.css'
import Alpine from 'alpinejs'
import collapse from '@alpinejs/collapse'
import Clipboard from '@ryangjchandler/alpine-clipboard'
import click_to_edit from './alpine/click_to_edit'
// import quill from 'quill'

import quill from './quill';
// const { quill } = import('./quill');
// const { EditorJS } = import('@editorjs/editorjs');
import EditorJS from '@editorjs/editorjs';
// import ImageTool from '@editorjs/image';
// import List from '@editorjs/list';
import Header from '@editorjs/header';
// import Underline from '@editorjs/underline';
// import Code from '@editorjs/code';
// import InlineCode from '@editorjs/inline-code';
// import Quote from '@editorjs/quote';
import Table from '@editorjs/table';
window.editorInstance = function(dataProperty, editorId, readOnly, placeholder, logLevel) {
    return {
        instance: null,
        data: null,

        initEditor() {
            this.data = this.$wire.get(dataProperty);

            this.instance = new EditorJS({

                holder: editorId,
                minHeight : 10,
                readOnly,

                placeholder,

                logLevel,

                tools: {
                    // image: {
                    //     class: ImageTool,

                    //     config: {
                    //         uploader: {
                    //             uploadByFile: (file) => {
                    //                 return new Promise((resolve) => {
                    //                     this.$wire.upload(
                    //                         'uploads',
                    //                         file,
                    //                         (uploadedFilename) => {
                    //                             const eventName = `fileupload:${uploadedFilename.substr(0, 20)}`;

                    //                             const storeListener = (event) => {
                    //                                 resolve({
                    //                                     success: 1,
                    //                                     file: {
                    //                                         url: event.detail.url
                    //                                     }
                    //                                 });

                    //                                 window.removeEventListener(eventName, storeListener);
                    //                             };

                    //                             window.addEventListener(eventName, storeListener);

                    //                             this.$wire.call('completedImageUpload', uploadedFilename, eventName);
                    //                         }
                    //                     );
                    //                 });
                    //             },

                    //             uploadByUrl: (url) => {
                    //                 return this.$wire.loadImageFromUrl(url).then(result => {
                    //                     return {
                    //                         success: 1,
                    //                         file: {
                    //                             url: result
                    //                         }
                    //                     }
                    //                 });
                    //             }
                    //         }
                    //     }
                    // },
                        table: {
                      class: Table,
                      inlineToolbar: true,
                      
                      config: {
                        
                        rows: 2,
                        cols: 3,
                        withHeadings: true,
                      },
                    },
                    // list: List,
                    // header: Header,
                    // underline: Underline,
                    // code: Code,
                    // 'inline-code': InlineCode,
                    // quote: Quote
                },

                data: this.data,

                onChange: () => {
                    this.instance.save().then((outputData) => {
                        this.$wire.set(dataProperty, outputData);

                        // this.$wire.call('save');
                    }).catch((error) => {
                        console.log('Saving failed: ', error)
                    });
                }
            });
            
        }
    }
}





// if (document.getElementById('parent')) {
//     const { app } = import('./plugins/lite-yt-embed')
//     app();
// }

import '@nextapps-be/livewire-sortablejs'


Alpine.plugin(collapse, Clipboard)

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

// window.editorInstance = editorInstance;
window.click_to_edit = click_to_edit;
// window.Quill = quill;
window.Alpine = Alpine;
document.addEventListener('alpine:init', () => {

  Alpine.data('quill', quill);

});
Alpine.start()


  

    {{-- <div class="bg-white" >
      <div
      x-data="quillEditor({})" >
           <input hidden id="{{$id}}" x-ref="input" {{ $attributes }}>
      
           <div wire:ignore>
               <div x-ref="editor">{!! $slot !!}</div>
           </div>
      </div>
    </div> --}}
    {{-- @php
    $name = $attributes->wire('model')->value();
@endphp
    <textarea  x-data="{ value: @entangle($attributes->wire('model')) }" name="{{ $name }}" x-cloak></textarea>
    <label for="{{ $name}}">
        {{ $slot }}:
    </label> --}}
  
    {{-- <div x-data="{ value: @entangle($attributes->wire('model')) }" x-init="tinymce.init({
      target: $refs.tinymce,
      themes: 'modern',
      height: 200,
      menubar: false,
      plugins: [
          'advlist autolink lists link image charmap print preview anchor',
          'searchreplace visualblocks code fullscreen',
          'insertdatetime media table paste code help wordcount'
      ],
      toolbar: 'undo redo | formatselect | ' +
          'bold italic backcolor | alignleft aligncenter ' +
          'alignright alignjustify | bullist numlist outdent indent | ' +
          'removeformat | help',
      setup: function(editor) {
          editor.on('blur', function(e) {
              value = editor.getContent()
          })
  
          editor.on('init', function(e) {
              if (value != null) {
                  editor.setContent(value)
              }
          })
  
          function putCursorToEnd() {
              editor.selection.select(editor.getBody(), true);
              editor.selection.collapse(false);
          }
  
          $watch('value', function(newValue) {
              if (newValue !== editor.getContent()) {
                  editor.resetContent(newValue || '');
                  putCursorToEnd();
              }
          });
      }
  })" wire:ignore>
      <div>
          <input id="{{$id}}" x-ref="tinymce" type="textarea" {{ $attributes->whereDoesntStartWith('wire:model') }}>
      </div>
  </div> --}}
  
  



 


  <div class="bg-white" >
    <div
    x-data="quillEditor({})" >
         <input hidden id="{{$id}}" x-ref="input" {{ $attributes }}>
    
         <div wire:ignore>
             <div class="prose m-0 max-w-none prose-p:m-4" x-ref="editor">{!! $slot !!}</div>
         </div>
    </div>
  </div>

{{--    
    @once
      @push('scripts')
      <script>

        var toolbarOptions = [
          [{ 'header': 1 }, { 'header': 2 }],    
          ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
          ['link','blockquote', 'code-block'],
        
                   // custom button values
          [{ 'list': 'ordered'}, { 'list': 'bullet' }],
          // [{ 'script': 'sub'}, { 'script': 'super' }],      // superscript/subscript
          // [{ 'indent': '-1'}, { 'indent': '+1' }],          // outdent/indent
          // [{ 'direction': 'rtl' }],                         // text direction
        
          // [{ 'size': ['small', false, 'large', 'huge'] }],  // custom dropdown
          //  [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
        
        //  [{ 'color': [] }, { 'background': [] }],          // dropdown with defaults from theme
          // [{ 'font': [] }],
          [{ 'align': [] }],
        
          ['clean']                                         // remove formatting button
        ];
          function quillEditor(data) {
            return {
              
                instance: null,
                init() {
                    this.$nextTick(() => {
                        this.instance = new Quill(this.$refs.editor, {
                            theme: 'snow',
                            modules: {
                              toolbar: toolbarOptions
                            },
                        });
                        
                        this.instance.on('text-change', () => {
                        
                            this.$refs.input.dispatchEvent(new CustomEvent('input', {
                     
                                detail: this.instance.root.innerHTML
                            }));
                 
                        })
                  
                    })
                    
                },
                ...data
            }
        }
        </script>   
      @endpush
    @endonce --}}
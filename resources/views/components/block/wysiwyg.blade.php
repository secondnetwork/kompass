{{-- @props(['itemfield']) --}}

    <div class="col-span-{{ $itemfield->grid }}" style="order: {{ $itemfield->order }} ">
        @php
            $jsfield = is_array($itemfield->data) ? $itemfield->data : json_decode($itemfield->data, true);
            $gridtables = $itemfield->grid;
        @endphp
        @livewire(
            \Secondnetwork\Kompass\Livewire\TiptapEditor::class,
            [
                'editorId' => $itemfield->id,
                'value' => $jsfield,
                'uploadDisk' => 'publish',
                'class' => 'tiptap-editor-wrapper',
                'style' => '',
                'placeholder' => __('write something...'),
            ],
            key($itemfield->id)
        )
    </div>
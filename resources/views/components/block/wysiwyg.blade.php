{{-- @props(['itemfield']) --}}

    <div class="col-span-{{ $itemfield->grid }}" style="order: {{ $itemfield->order }} ">
        @php
            $jsfield = is_array($itemfield->data) ? $itemfield->data : json_decode($itemfield->data, true);
            $gridtables = $itemfield->grid;
        @endphp
        @livewire(
            'editorjs',
            [
                'editorId' => $itemfield->id,
                'value' => $jsfield,
                'uploadDisk' => 'publish',
                'downloadDisk' => 'publish',
                'class' => 'cdx-input',
                'style' => '',
                'placeholder' => __('write something...'),
            ],
            key($itemfield->id)
        )
    </div>
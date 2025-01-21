@props(['itemblocks'])
@foreach ($itemblocks->datafield as $itemfields)
    <div class="col-span-{{ $itemfields->grid }}" style="order: {{ $itemfields->order }} ">
        @php
            $jsfield = json_decode($itemfields->data, true);
            $gridtables = $itemfields->grid;
        @endphp
        @livewire(
            'editorjs',
            [
                'editorId' => $itemfields->id,
                'value' => $jsfield,
                'uploadDisk' => 'publish',
                'downloadDisk' => 'publish',
                'class' => 'cdx-input',
                'style' => '',
                'placeholder' => __('write something...'),
            ],
            key($itemfields->id)
        )
    </div>
@endforeach
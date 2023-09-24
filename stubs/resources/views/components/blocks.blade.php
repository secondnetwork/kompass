@props([
    'name' => '',
    'label' => '',
    'value' => '',
    'type' => '',
    'key' => '',
    'fields' => '',
])

@if ($label === '')
    @php
        //remove underscores from name
        $label = str_replace('_', ' ', $name);
        //detect subsequent letters starting with a capital
        $label = preg_split('/(?=[A-Z])/', $label);
        //display capital words with a space
        $label = implode(' ', $label);
        //uppercase first letter and lower the rest of a word
        $label = ucwords(strtolower($label));
    @endphp
@endif


{{-- Name: <strong >{{$fields[$key]->name}}</strong>
Data: {{$fields[$key]->data}} ID:{{$fields[$key]->id}}  --}}

{{-- {{$itemfields->id}}
{{$name}}
{{$type}} --}}
{{-- {{$itemfields->order}}
{{$itemfields->grid}} --}}

@if ($type == 'text' || $type == 'wysiwyg')
{{-- <input  type="text" wire:model="fields.{{$key}}.data"> -  --}}
{!!$fields!!}

@endif


@if ($type == 'image')
    @if (!empty($fields))
    @php
    // $mediafile =
    $file = Secondnetwork\Kompass\Models\File::find($fields);

    // if (Storage::disk('local')->exists('/public/'. $file->extension)) {

    // }
    @endphp
        @if ($file)
            @if (Storage::disk('local')->exists('/public/'.$file->path.'/'.$file->extension))
            <img on="pages.pages-show" alt="logo" class="h-48 aspect-auto" src="{{ asset($file->path.'/'.$file->extension) }}">
            @endif
        @endif

    @endif
    <button wire:click="selectItem({{$idField}}, 'addMedia')">Add Media library</button>
    {{-- <button wire:click="selectItem({{$idField}}, 'addMedia')">Add Media library</button> --}}

@endif

@if ($type == 'oembed')
    video
@endif


{{--
<div>
    <label for='{{ $name }}'>{{ $label }}</label>
    <input type='checkbox' name='{{ $name }}' id='{{ $name }}' value='{{ $value }}' @if ($slot != '') checked="checked" @endif {{ $attributes }}>
</div> --}}

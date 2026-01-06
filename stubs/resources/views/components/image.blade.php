@props([
    'id' => null,
    'url' => null,
    'size' => null,
    'alt' => null
])

@php

    use Secondnetwork\Kompass\Helpers\ImageFactory;

    if ($id !== null) {
        $builder = getImageID($id, $size);
    } else {
        $builder = getImageUrl($url, $size);
    }

    if ($alt) {
        $builder->alt($alt);
    }
    
    // Attribute an die Factory Instanz übergeben
    $builder->mergeAttributes($attributes->getAttributes());
@endphp

{{-- Rendern --}}
{!! $builder->render() !!}
@php
    $logoDimensions = null;
    if ($isImage && $imageSrc) {
        $logoDimensions = cache()->rememberForever(
            'logo-image-dimensions-'.md5($imageSrc),
            fn () => @getimagesize(str_starts_with($imageSrc, 'http') ? $imageSrc : public_path($imageSrc)) ?: null,
        );
    }
@endphp
<x-kompass::elements.link href="/" style="height:{{ $height ?? '3' }}rem; width:auto; display:block" aria-label="{{ config('app.name') }} Logo">
    @if($isImage)
        <img src="{{ url($imageSrc) }}" style="height:100%; width:auto" alt=""
            @if($logoDimensions) width="{{ $logoDimensions[0] }}" height="{{ $logoDimensions[1] }}" @endif />
    @else
        {!! str_replace('<svg', '<svg style="height:100%; width:auto"', $svgString) !!}
    @endif
</x-kompass::elements.link>
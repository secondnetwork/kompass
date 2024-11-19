<x-kompass::elements.link href="/" style="height:{{ $height ?? '3' }}rem; width:auto; display:block" aria-label="{{ config('app.name') }} Logo">
    @if($isImage)
        <img src="{{ url($imageSrc) }}" style="height:100%; width:auto" alt="" />
    @else
        {!! str_replace('<svg', '<svg style="height:100%; width:auto"', $svgString) !!}
    @endif
</x-kompass::elements.link>
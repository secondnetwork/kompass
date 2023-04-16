
@if ('is_front_page' !== str_replace(".","-", Route::currentRouteName()))
    @section('title',$page->title )
@endif
@section('seo')
{{-- {!! seo($SEOData) !!} --}}
@endsection
{{-- @dump($this->blocks->toArray()) --}}

<div>
@foreach ($this->blocks as $key => $item)

    <x-blocks.header layout="{{$item->slug}}" blockid="{{$item->id}}" :set="$item->set"/>
    <x-blocks.longtext layout="{{$item->slug}}" blockid="{{$item->id}}" :set="$item->set"/>
    <x-blocks.anmeldemaske layout="{{$item->slug}}" blockid="{{$item->id}}" :set="$item->set"/>
    <x-blocks.unsergaste layout="{{$item->slug}}" blockid="{{$item->id}}" :set="$item->set"/>
    <x-blocks.ueberschrift layout="{{$item->slug}}" blockid="{{$item->id}}" :set="$item->set"/>
    <x-blocks.accordion layout="{{$item->slug}}" blockid="{{$item->id}}" :set="$item->set"/>
    <x-blocks.oembed layout="{{$item->slug}}" blockid="{{$item->id}}" :set="$item->set"/>

    <x-blocks.group layout="{{$item->type}}" blockid="{{$item->id}}" :children="$item['children']->where('status', 'public')->sortBy('order')" />

@endforeach
<section></section>
</div>

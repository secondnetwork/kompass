
@if ('is_front_page' !== str_replace(".","-", Route::currentRouteName()))
    @section('title',$page->title )



@endif
@section('seo')
{{-- {!! seo($SEOData) !!} --}}
@endsection
{{-- @dump($this->blocks->toArray()) --}}

<div>

@foreach ($this->blocks as $key => $item)

    <x-blocks.header layout="{{$item->type}}" blockid="{{$item->id}}" :set="$item->set"/>
    <x-blocks.longtext layout="{{$item->type}}" blockid="{{$item->id}}" :set="$item->set"/>
    <x-blocks.anmeldemaske layout="{{$item->type}}" blockid="{{$item->id}}" :set="$item->set"/>
    <x-blocks.unsergaste layout="{{$item->type}}" blockid="{{$item->id}}" :set="$item->set"/>
    <x-blocks.ueberschrift layout="{{$item->type}}" blockid="{{$item->id}}" :set="$item->set"/>
    <x-blocks.accordion layout="{{$item->type}}" blockid="{{$item->id}}" :set="$item->set"/>
    <x-blocks.gallery layout="{{$item->type}}" blockid="{{$item->id}}" :set="$item->set"/>
    <x-blocks.oembed layout="{{$item->type}}" blockid="{{$item->id}}" :set="$item->set"/>
    <x-blocks.tables layout="{{$item->type}}" blockid="{{$item->id}}" :set="$item->set"/>


    <x-blocks.group layout="{{$item->type}}" blockid="{{$item->id}}" :set="$item->set" :children="$item['children']->where('status', 'published')->sortBy('order')" />

@endforeach

<section></section>
</div>

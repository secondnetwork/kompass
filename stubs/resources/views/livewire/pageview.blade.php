
@if ('is_front_page' !== str_replace(".","-", Route::currentRouteName()))
    @section('title',$page->title )



@endif
@section('seo')
{{-- {!! seo($SEOData) !!} --}}
@endsection
{{-- @dump($this->blocks->toArray()) --}}

<div>

    @if ('is_front_page' !== str_replace(".","-", Route::currentRouteName()))
        <section class="fullpage mt-4">

         <div class="relative">
      <img class="w-full h-96 rounded-md" src="{{asset('alexander-bagno-wD5onrVizyo-unsplash.jpeg')}}" alt="Image Description">
         </div>

    </section>
@endif


@if ($page->password !== NULL)

<section  x-data="{ open: false }">
    <div class="gap-4 grid" x-show="!open">
    <h3 x-show="!open">Passwortgeschützte</h3>
    {{-- <x-inputs.password   value="" />
    <x-button secondary  x-on:click="open = ! open" label="Absenden" /> --}}
    </div>


    <div x-show="open">
       @foreach ($this->blocks as $key => $item)

    <x-blocks.header layout="{{$item->slug}}" blockid="{{$item->id}}" :set="$item->set"/>
    <x-blocks.longtext layout="{{$item->slug}}" blockid="{{$item->id}}" :set="$item->set"/>
    <x-blocks.anmeldemaske layout="{{$item->slug}}" blockid="{{$item->id}}" :set="$item->set"/>
    <x-blocks.unsergaste layout="{{$item->slug}}" blockid="{{$item->id}}" :set="$item->set"/>
    <x-blocks.ueberschrift layout="{{$item->slug}}" blockid="{{$item->id}}" :set="$item->set"/>
    <x-blocks.accordion layout="{{$item->slug}}" blockid="{{$item->id}}" :set="$item->set"/>
    <x-blocks.gallery layout="{{$item->slug}}" blockid="{{$item->id}}" :set="$item->set"/>
    <x-blocks.oembed layout="{{$item->slug}}" blockid="{{$item->id}}" :set="$item->set"/>
    <x-blocks.tables layout="{{$item->slug}}" blockid="{{$item->id}}" :set="$item->set"/>
    <x-blocks.group layout="{{$item->slug}}" blockid="{{$item->id}}" :set="$item->set" :children="$item['children']->where('status', 'published')->sortBy('order')" />

@endforeach.
    </div>

</section>



@else
@foreach ($this->blocks as $key => $item)

    <x-blocks.header layout="{{$item->slug}}" blockid="{{$item->id}}" :set="$item->set"/>
    <x-blocks.longtext layout="{{$item->slug}}" blockid="{{$item->id}}" :set="$item->set"/>
    <x-blocks.anmeldemaske layout="{{$item->slug}}" blockid="{{$item->id}}" :set="$item->set"/>
    <x-blocks.unsergaste layout="{{$item->slug}}" blockid="{{$item->id}}" :set="$item->set"/>
    <x-blocks.ueberschrift layout="{{$item->slug}}" blockid="{{$item->id}}" :set="$item->set"/>
    <x-blocks.accordion layout="{{$item->slug}}" blockid="{{$item->id}}" :set="$item->set"/>
    <x-blocks.gallery layout="{{$item->slug}}" blockid="{{$item->id}}" :set="$item->set"/>
    <x-blocks.oembed layout="{{$item->slug}}" blockid="{{$item->id}}" :set="$item->set"/>
    <x-blocks.tables layout="{{$item->slug}}" blockid="{{$item->id}}" :set="$item->set"/>
    <x-blocks.group layout="{{$item->slug}}" blockid="{{$item->id}}" :set="$item->set" :children="$item['children']->where('status', 'published')->sortBy('order')" />

@endforeach
@endif




<section></section>
</div>

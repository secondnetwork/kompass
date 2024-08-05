
@if ('is_front_page' !== str_replace(".","-", Route::currentRouteName()))
    @section('title',$page->title )



@endif
@section('seo')
{{-- {!! seo($SEOData) !!} --}}
@endsection

<div>

<section>
@foreach ($this->blocks as $key => $item)

    <x-blocks.longtext :item="$item" class="prose m-0 max-w-none  {{ $item->set->layout ?? '' }}"/>
    <x-blocks.oembed :item="$item" class="{{ $item->set->layout ?? '' }}"/>
        
@endforeach
</section>
<section>
</section>
</div>



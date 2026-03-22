@if(seo()->get('title'))
<title>{!! seo()->get('title') !!}</title>
@unless(seo()->hasTag('og:title'))
<meta property="og:title" content="{!! seo()->get('title') !!}">
@endunless
@endif
@if(seo()->get('description'))
<meta property="og:description" content="{!! seo()->get('description') !!}">
<meta name="description" content="{!! seo()->get('description') !!}">
@endif
@if(seo()->get('keywords'))
<meta name="keywords" content="{!! seo()->get('keywords') !!}">
@endif
@if(seo()->get('type'))
<meta property="og:type" content="{!! seo()->get('type') !!}">
@else
<meta property="og:type" content="website">
@endif
@if(seo()->get('site'))
<meta property="og:site_name" content="{!! seo()->get('site') !!}">
@endif
@if(seo()->get('locale'))
<meta property="og:locale" content="{!! seo()->get('locale') !!}">
@endif
@if(seo()->get('image'))
<meta property="og:image" content="{!! seo()->get('image') !!}">
@endif
@if(seo()->get('url'))
<meta property="og:url" content="{!! seo()->get('url') !!}">
<link rel="canonical" href="{!! seo()->get('url') !!}">
@endif
@foreach(seo()->tags() as $key => $tag)
<meta property="{{ $key }}" content="{!! $tag !!}">
@endforeach
@if(seo()->isTwitterEnabled())
@php $twitter = seo()->twitterData(); @endphp
@if(isset($twitter['site']))
<meta name="twitter:site" content="{{ $twitter['site'] }}">
@endif
@if(seo()->get('title'))
<meta name="twitter:title" content="{!! seo()->get('title') !!}">
@endif
@if(seo()->get('description'))
<meta name="twitter:description" content="{!! seo()->get('description') !!}">
@endif
@if(seo()->get('image'))
<meta name="twitter:image" content="{!! seo()->get('image') !!}">
@endif
@endif

@extends('kompass::admin.layouts.app')

@section('content')
    <div class="grid grid-cols-3 gap-8 pt-4">
        <div class="rounded-xl p-6 flex items-center shadow bg-center col-span-3"
            style="background-image: url({{ kompass_asset('kompass_bg.png') }})">
            <div>

                <div class=" text-gray-400">
       @php
  
        $h = date('G');
        @endphp

        @if ($h>=0 && $h<=11)
        {{__('Good morning')}}
        @endif
        @if ($h>=12 && $h<=16)
        {{__('Good afternoon')}}
        @endif
        @if ($h>=17 && $h<=23)
        {{__('Good evening')}}
        @endif
 
                </div>
                <h2 class=" text-white">{{ auth()->user()->name }}</h2>
            </div>

        </div>

    

    </div>
    </div>
@endsection

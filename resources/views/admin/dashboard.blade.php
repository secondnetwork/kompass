@extends('kompass::admin.layouts.app')

@section('content')
    <div class=" pt-4">

        <div class="rounded-xl items-center shadow bg-center col-span-3"
            style="background-image: url({{ kompass_asset('kompass_bg.png') }})">

            @env('local') 
            <div class="flex items-center p-2 px-6 rounded-t-xl font-bold  gap-1 bg-warning text-warning-content  w-full text-center text-xs ">
           Developer Mode
           
           </div> 
           @endenv
            <div class="p-6">


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
                <h3 class=" text-white">{{ auth()->user()->name }}</h3>
                <div class=" text-gray-400">{{ now()->isoFormat('dddd, D. MMMM YYYY') }}</div>
            </div>

        </div>

        <p class="alert alert-warning" wire:offline>
            Whoops, your device has lost connection. The web page you are viewing is offline.
        </p>

    </div>
    </div>
@endsection

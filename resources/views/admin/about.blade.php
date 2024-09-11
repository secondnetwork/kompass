@extends('kompass::admin.layouts.app')

@section('content')
    <div class="grid grid-cols-3 gap-8 pt-8">
        <div class="rounded-2xl p-12 flex items-center  shadow bg-center col-span-3"
            style="background-image: url({{ kompass_asset('kompass_bg.png') }})">



<div class="logo"><img class="h-[6rem]" src="{{ kompass_asset('kompass_logo_cms.svg')}}" alt=""></div>


    </div>
    </div>


<div class="mt-4 bg-white rounded-2xl border border-gray-100 shadow bg-opacity-25 grid grid-cols-1 md:grid-cols-2">
    <div class="p-8">
        <div class="flex items-center">
           <x-tabler-book  />    <div class="ml-4 text-lg text-gray-600 leading-7 font-semibold"><a href="https://kompass.secondnetwork.de" target="_black">Documentation</a></div>
        </div>

        <div class="ml-10">
            <div class="mt-2 text-sm text-gray-500">
                In this documentation we'll walk you through all information and tips.
            </div>

            <a href="https://kompass.secondnetwork.de" target="_black">
                <div class="mt-3 flex items-center text-sm font-semibold text-indigo-700">
                        <div>Explore the documentation</div>

                        <div class="ml-1 text-indigo-500">
                        <x-tabler-arrow-narrow-right  />
                        </div>
                </div>
            </a>
        </div>
    </div>

    <div class="p-8 border-t border-gray-200 md:border-t-0 md:border-l">
        <div class="flex items-center">
         <x-tabler-device-tv  /> 
            <div class="ml-4 text-lg text-gray-600 leading-7 font-semibold">YouTube tutorials</div>
        </div>

        <div class="ml-10">
            <div class="mt-2 text-sm text-gray-500">
               Video tutorials for Kompass
            </div>

            <a href="https://youtube.com/@secondnetwork" target="_black">
                <div class="mt-3 flex items-center text-sm font-semibold text-indigo-700">
                        <div>Start watching</div>

                        <div class="ml-1 text-indigo-500">
                      <x-tabler-arrow-narrow-right  />    </div>
                </div>
            </a>
        </div>
    </div>

    <div class="p-8 border-t border-gray-200">
        <div class="flex items-center">
          {{-- <x-tabler-3d-cube-sphere  /> --}}
         <div class="ml-4 text-lg text-gray-600 leading-7 font-semibold">Technology</div>
        </div>

        <div class="ml-10">
            <div class="mt-2 text-sm text-gray-500">
                Kompass is built with the power of Tailwind, Alpine.js, Laravel and Livewire.
            </div>
        </div>
    </div>

    <div class="p-8 border-t border-gray-200 md:border-l">
        <div class="flex items-center">
       
       <x-tabler-brand-laravel  />
            <div class="ml-4 text-lg text-gray-600 leading-7 font-semibold">Laravel Fortify Authentication</div>
        </div>

        <div class="ml-10">
            <div class="mt-2 text-sm text-gray-500">
                Authentication and registration views are included with Laravel Fortify, as well as support for user email verification and resetting forgotten passwords. So, you're free to get started what matters most: building your application.
            </div>
        </div>
    </div>
</div>

@endsection

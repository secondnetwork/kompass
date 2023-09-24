@props(['on'])

<div  x-data="{ show: false, timeout: null, timeshow: null }"
    x-init="@this.on('{{ $on }}', () => 
    {   clearTimeout(timeshow); 
         
        timeshow = setTimeout(() => { show = true }, 100); 
        clearTimeout(timeout); 
        timeout = setTimeout(() => { show= false }, 3000);  
        
    })"

    x-transition:enter="transition origin-top ease-out duration-300"
    x-transition:enter-start="transform translate-y-full "
    x-transition:enter-end="transform translate-y-0 "
    x-transition:leave="transition origin-top ease-out duration-250"
    x-transition:leave-start="transform translate-y-0 opacity-100"
    x-transition:leave-end="translate-y-full opacity-0"
    x-show="show"

    class="z-50 fixed bottom-16 right-6 w-full sm:w-11/12 md:max-w-md alert text-white bg-gray-800 flex flex-row items-center rounded-lg shadow-lg overflow-hidden "
    {{ $attributes->merge(['class' => 'text-gray-600']) }}
    >

    <div class="flex w-2 mx-2 my-4 rounded-full bg-green-500 h-12"></div>
    <div class="h-full px-1">
        <x-tabler-circle-check class="stroke-green-500" />
    </div>
    <div class="mx-4 flex-row w-full">
      <div class="flex flex-row items-center justify-between">
        <h1 class="text-base sm:text-lg font-bold text-gray-200">{{ __('Saved.') }}</h1>
      </div>
      <p class="text-xs sm:text-sm font-medium pr-2 sm:pr-0 text-gray-300">{{ __('successfully updated') }}</p>
    </div>

    {{-- {{ $slot->isEmpty() ? 'Saved.' : $slot }} --}}


</div>
{{-- x-transition:enter="transform transition-transform duration-300" 
x-transition:enter-start="-translate-y-full" 
x-transition:enter-end="translate-y-0" 
x-transition:leave="transform transition-transform duration-300" 
x-transition:leave-start="translate-y-0" 
x-transition:leave-end="-translate-y-full" --}}
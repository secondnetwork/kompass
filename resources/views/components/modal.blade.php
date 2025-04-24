@props([
    'id' => 'text',
    'data' => '',

])

<div x-data="{ modalOpen: @entangle('FormDelete') }"
    @keydown.escape.window="modalOpen = false"
    :class="{ 'z-40': modalOpen }" class="relative w-auto h-auto">
   
    <template x-teleport="body">
        <div x-show="modalOpen" class="fixed top-0 left-0 z-[99] flex items-center justify-center w-screen h-screen">
            <div x-show="modalOpen"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-300"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                @click="modalOpen=false" class="absolute inset-0 w-full h-full bg-gray-900 bg-opacity-50 backdrop-blur-sm"></div>
            <div x-show="modalOpen"
                x-trap.inert.noscroll="modalOpen"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-90"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-90"
                class="relative w-full py-6 bg-white shadow-md px-7 bg-opacity-90 drop-shadow-md backdrop-blur-sm sm:max-w-lg sm:rounded-lg">
                <div class="flex items-center justify-between pb-3">
                    {{-- <h3 class="text-lg font-semibold">Modal Title</h3> --}}
         
                <span @click="modalOpen = false" class="absolute top-2 right-2 cursor-pointer p-2 bg-gray-100 rounded-full hover:bg-gray-300 transition-all"><x-tabler-x /></span>
                </div>
                <div class="relative w-auto py-8">
                    <div> {{__('Are you sure you want to delete the selected resources?')}}</div>
                </div>

                <div class="flex flex-col-reverse sm:flex-row sm:justify-end sm:space-x-2">  
                    <button @click="modalOpen = false" type="button" class="btn btn-neutral" data-dismiss="modal">{{ __('Cancel')}}</button>
                    <button wire:click="delete" type="button" class="btn btn-error">{{ __('Delete')}}</button>       
                </div>
            </div>
        </div>
    </template>
</div>
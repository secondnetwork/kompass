@props([
    'id' => 'text',
    'data' => '',
])

<div x-data="{ modalOpen: @entangle('FormDelete') }" @keydown.escape.window="modalOpen = false" :class="{ 'z-40': modalOpen }"
    class="relative w-auto h-auto">

    <template x-teleport="body">
        <div x-show="modalOpen" class="fixed top-0 left-0 z-[99] flex items-center justify-center w-screen h-screen">
            <div x-show="modalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-300"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="modalOpen=false"
                class="absolute inset-0 w-full h-full  bg-opacity-50 backdrop-blur-sm"></div>
            <div x-show="modalOpen" x-trap.inert.noscroll="modalOpen" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-90"
                class="overflow-hidden relative w-full p-6 bg-white shadow-md bg-opacity-90 drop-shadow-md backdrop-blur-sm sm:max-w-lg sm:rounded-lg">

    <div class="relative w-max">
        <div data-featured-icon="true"
            class="relative flex shrink-0 items-center justify-center *:data-icon:size-6 rounded-full size-12 bg-error/20 text-featured-icon-light-fg-success">
            <x-tabler-trash class="text-error" />
        </div>
            
            <svg width="336" height="336" viewBox="0 0 336 336" fill="none"
            class="opacity-30 pointer-events-none absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
            <mask id="mask0_4947_375931" style="mask-type: alpha;" maskUnits="userSpaceOnUse" x="0" y="0" width="336"
                height="336">
                <rect width="336" height="336" fill="url(#paint0_radial_4947_375931)"></rect>
            </mask>
            <g mask="url(#mask0_4947_375931)">
                <circle cx="168" cy="168" r="47.5" stroke="currentColor"></circle>
                <circle cx="168" cy="168" r="47.5" stroke="currentColor"></circle>
                <circle cx="168" cy="168" r="71.5" stroke="currentColor"></circle>
                <circle cx="168" cy="168" r="95.5" stroke="currentColor"></circle>
                <circle cx="168" cy="168" r="119.5" stroke="currentColor"></circle>
                <circle cx="168" cy="168" r="143.5" stroke="currentColor"></circle>
                <circle cx="168" cy="168" r="167.5" stroke="currentColor"></circle>
            </g>
            <defs>
                <radialGradient id="paint0_radial_4947_375931" cx="0" cy="0" r="1"
                    gradientUnits="userSpaceOnUse" gradientTransform="translate(168 168) rotate(90) scale(168 168)">
                    <stop></stop>
                    <stop offset="1" stop-opacity="0"></stop>
                </radialGradient>
            </defs>
        </svg>
    </div>

                <div class="flex items-center justify-between pb-3">
                    {{-- <h3 class="text-lg font-semibold">Modal Title</h3> --}}

                    <span @click="modalOpen = false"
                        class="absolute top-3 right-4 cursor-pointer p-2 bg-gray-100 rounded-full hover:bg-gray-300 transition-all"><x-tabler-x /></span>
                </div>
                <div class="relative w-auto py-8">
                    <div> {{ __('Are you sure you want to delete the selected resources?') }}</div>
                </div>

                <div class="flex flex-col-reverse sm:flex-row sm:justify-end sm:space-x-2">
                    <button @click="modalOpen = false" type="button" class="btn btn-neutral"
                        data-dismiss="modal">{{ __('Cancel') }}</button>
                    <button wire:click="delete" type="button" class="btn btn-error">{{ __('Delete') }}</button>
                </div>
            </div>
        </div>
    </template>
</div>



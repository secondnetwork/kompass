<div x-data="{
    favicon_light: @entangle('favicon_light'),
    favicon_dark: @entangle('favicon_dark'),
}">
    <div class="pb-5 mb-5 border-b border-zinc-200">
        <div class="pb-3 w-full">
            <label class="block text-sm font-medium leading-6 text-gray-900">Favicon Image</label>
            <p class="text-sm leading-6 text-gray-400">{{ __('Choose the default favicon image by 512 x 512 Pixel. This image will show by default and in light mode.') }}</p>
        </div>
        <div class="flex flex-col items-start w-full h-auto">

            <label for="favicon_light" class="flex overflow-hidden justify-start items-center mt-3 w-auto h-auto text-sm cursor-pointer">
                @if(isset($favicon_light) && $favicon_light != '')
                <div class="flex relative justify-center items-center w-12 h-12 mr-2 rounded border border-zinc-200 bg-zinc-100">
                    <img src="{{ url($favicon_light) . '?' . uniqid() }}" class="w-auto h-8 rounded-md" />
                </div>
                @endif
                <div class="grow">
                    <div class="flex items-center gap-x-2">
                     @if(empty($favicon_light))
                      <div class="btn btn-primary" >
                        <x-tabler-upload />
                        {{ __('Upload') }}
                      </div>
                      @else
                      <button wire:click="deleteFaviconLight()" class="btn btn-error">
                      <x-tabler-trash class="mr-1 w-4 h-4" />
                      <span>{{ __('Remove Image') }}</span>
                      </button>
                      @endif
                      {{-- <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-xs font-semibold rounded-lg border border-gray-200 bg-white text-base-content/70 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none" data-hs-file-upload-clear="">Delete</button> --}}
                    </div>
                  </div>

                <input id="favicon_light" type="file" wire:model="favicon_light" class="hidden" />
            </label>
        </div>
    </div>

    <div class="pb-5 mb-5 border-b border-zinc-200">
        <div class="pb-3 w-full">
            <label class="block text-sm font-medium leading-6 text-gray-900">Favicon Dark Mode Image</label>
            <p class="text-sm leading-6 text-gray-400">{{ __('This is the favicon image will show when user machine is in dark mode.') }}</p>
        </div>
        <div class="flex flex-col items-start w-full h-auto">

            <label for="favicon_dark" class="flex overflow-hidden justify-start items-center mt-3 w-auto h-auto text-sm cursor-pointer">
                @if(isset($favicon_dark) && $favicon_dark != '')
                <div class="flex relative justify-center items-center w-12 h-12 mr-2 rounded border border-zinc-800 bg-zinc-900">
                    <img src="{{ url($favicon_dark) . '?' . uniqid() }}" class="w-auto h-8 rounded-md" />
                </div>
                @endif
                <div class="grow">
                    <div class="flex items-center gap-x-2">
                    @if(empty($favicon_dark))
                      <div class="btn btn-primary" >
                        <x-tabler-upload />
                        {{ __('Upload') }}
                      </div>
                    @else
                        <button wire:click="deleteFaviconDark()" class="btn btn-error">
                        <x-tabler-trash class="mr-1 w-4 h-4" />
                        <span>{{ __('Remove Image') }}</span>
                        </button>
                    @endif

                      {{-- <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-xs font-semibold rounded-lg border border-gray-200 bg-white text-base-content/70 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none" data-hs-file-upload-clear="">Delete</button> --}}
                    </div>
                  </div>
     
                <input id="favicon_dark" type="file" wire:model="favicon_dark" class="hidden" />
            </label>
        </div>
    </div>
    <div class="pb-3 w-full">
        <label class="block text-sm font-medium leading-6 text-gray-900">Theme Color Meta Tag</label>
        <p class="text-sm leading-6 text-gray-400">{{ __('A color for the browser toolbar and the status bar on mobile devices.') }}</p>
    </div>
    <div x-data="{ selectedColor: @entangle('color_theme'), showPicker: false }" class="w-full h-auto flex items-center gap-2">
          <!-- Hidden Color Input -->
        <input type="color" x-ref="colorInput" style="display: none;" @change="selectedColor = $refs.colorInput.value; $wire.set('color_theme', selectedColor)" />

        <!-- Custom Button to Open Color Picker -->
        <div class="w-10 h-10 border-2 border-gray-300 rounded-full flex items-center justify-center cursor-pointer" :style="{ backgroundColor: selectedColor }" @click="$refs.colorInput.click()">
     
        </div>
        <input type="text" value="#000000"  wire:model.live="color_theme" class="w-28" />

    </div>
</div>
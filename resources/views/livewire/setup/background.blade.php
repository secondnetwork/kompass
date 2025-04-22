<div
    x-data="{
        image: @entangle('image').live,
        image_overlay_opacity: @entangle('image_overlay_opacity').live
    }"
 class="max-w-2xl">



    <div class="pb-5 mb-5 border-b border-zinc-200">
        <div class="pb-3 w-full">
            <label class="block text-sm font-medium leading-6 text-gray-900">{{ __('Background Color') }}</label>
        </div>
        
        <div x-data="{ selectedColor: @entangle('color'), showPicker: false }" class="w-full h-auto flex items-center gap-2">
 
        
            <!-- Hidden Color Input -->
            <input type="color" x-ref="colorInput" style="display: none;" @change="selectedColor = $refs.colorInput.value; $wire.set('color', selectedColor)" />
    
            <!-- Custom Button to Open Color Picker -->
            <div class="w-10 h-10 border-2 border-gray-300 rounded-full flex items-center justify-center cursor-pointer" :style="{ backgroundColor: selectedColor }" @click="$refs.colorInput.click()">
         
            </div>
            <input type="text" value="#000000"  wire:model.live="color" class="w-28" />
    
        </div>
    </div>

    <div class="pb-5 mb-5 border-b border-zinc-200">
        <div class="pb-3 w-full">
            <label class="block text-sm font-medium leading-6 text-gray-900">{{ __('Background Image') }}</label>
            <p class="text-sm leading-6 text-gray-400">{{ __('Choose a nice background image to use as your authentication background.') }}</p>
        </div>
        <div class="w-2/3 h-auto">
            @if(isset($image) && $image != '')
            <div class="relative">
                <img src="{{ is_string($image ) ? url($image) . '?' . uniqid() : $image->temporaryUrl() }}" class="w-full h-auto rounded-lg object-cover aspect-[16/9]" />
                <button wire:click="deleteImage()" class="flex absolute top-0 right-0 items-center px-3 py-1.5 mt-2 mr-2 text-xs font-medium text-white rounded-md bg-red-500/70 hover:bg-red-500/90">
                    <x-tabler-trash class="mr-1 w-4 h-4" />
                    <span>{{ __('Remove Image') }}</span>
                </button>
            </div>
        @else
            <div class="flex items-center w-full">
                <label for="image" class="flex flex-col justify-center items-center aspect-[1.91/1] w-full bg-gray-50 rounded-t-lg border-2 border-gray-300 border-dashed cursor-pointer dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                    <div class="flex flex-col justify-center items-center pt-5 pb-6">
                        <x-tabler-cloud-upload class="mb-4 w-8 h-8 text-base-content/70 dark:text-gray-400" />
                        <p class="mb-2 text-sm text-base-content/70 dark:text-gray-400"><span class="font-semibold">{{ __('Click to upload') }}</span></p>
                        <p class="text-xs text-base-content/70 dark:text-gray-400">{{ __('PNG, JPG or GIF') }}</p>
                    </div>
                    <input id="image" type="file" wire:model="image" class="hidden" />
                </label>
            </div> 
        @endif
        </div>
    </div>

    <div class="pb-5 mb-5 border-b border-zinc-200">
        <div class="pb-3 w-full ">
            <label class="block text-sm font-medium leading-6 text-gray-900">{{ __('Image Overlay Color') }}</label>
            <p class="text-sm leading-6 text-gray-400">{{ __('If you use a background image you can specify a color overlay here.') }}</p>
        </div>
        <div x-data="{ selectedColor: @entangle('image_overlay_color'), showPicker: false }" class="w-full h-auto flex items-center gap-2">
 
        
            <!-- Hidden Color Input -->
            <input type="color" x-ref="colorInput" style="display: none;" @change="selectedColor = $refs.colorInput.value; $wire.set('image_overlay_color', selectedColor)" />
    
            <!-- Custom Button to Open Color Picker -->
            <div class="w-10 h-10 border-2 border-gray-300 rounded-full flex items-center justify-center cursor-pointer" :style="{ backgroundColor: selectedColor }" @click="$refs.colorInput.click()">
         
            </div>
            <input type="text" value="#000000"  wire:model.live="image_overlay_color" class="w-28" />
    
        </div>

    </div>

    <div class="pb-5 mb-5 border-b border-zinc-200">
        <div class="pb-3 w-full">
            <label class="block text-sm font-medium leading-6 text-gray-900">{{ __('Image Overlay Opacity') }}</label>
            <p class="text-sm leading-6 text-gray-400">{{ __('The opacity of the image overlay color. Set to 0 for no overlay') }}</p>
        </div>
        <div class="w-full h-auto">
            <p class="font-bold" x-text="image_overlay_opacity + '%'"></p>
            <div class="relative mb-6 max-w-xs">
                <input type="range" value="100" min="0" max="100" x-model="image_overlay_opacity" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer dark:bg-gray-700">
                <span class="flex justify-between items-center w-full">
                    <span class="-bottom-6 text-sm text-base-content/70">0%</span>
                    <span class="-bottom-6 text-sm text-base-content/70">50%</span>
                    <span class="-bottom-6 text-sm text-base-content/70">100%</span>
                </span>
            </div>
        </div>

        
    </div>




   
    {{-- <div class="bg-white mx-auto my-auto p-6">
      <div x-data="app()" x-init="[initColor()]">
        <div>
          <label for="color-picker" class="block mb-1 font-semibold">Select a color</label>
          <div class="flex flex-row relative">
            <input id="color-picker" class="border border-gray-400 p-2 rounded-lg" x-model="currentColor">
            <div @click="isOpen = !isOpen" class="cursor-pointer rounded-full ml-3 my-auto h-10 w-10 flex" :class="`bg-${currentColor}`" >
              <svg xmlns="http://www.w3.org/2000/svg" :class="`${iconColor}`" class="h-6 w-6 mx-auto my-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
              </svg>
            </div>
            <div x-show="isOpen" @click.away="isOpen = false" x-transition:enter="transition ease-out duration-100 transform"
              x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
              x-transition:leave="transition ease-in duration-75 transform" x-transition:leave-start="opacity-100 scale-100"
              x-transition:leave-end="opacity-0 scale-95" class="border border-gray-300 origin-top-right absolute right-0 top-full mt-2 rounded-md shadow-lg">
              <div class="rounded-md bg-white shadow-xs p-2">
                <div class="flex">
                  <template x-for="color in colors">
                    <div class="">
                      <template x-for="variant in variants">
                        <div @click="selectColor(color,variant)" class="cursor-pointer w-6 h-6 rounded-full mx-1 my-1" :class="`bg-${color}-${variant}`"></div>
                      </template>
                    </div>
                  </template>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <script>
      function app() {
          return {
            colors: ['gray', 'red', 'yellow', 'green', 'blue', 'indigo', 'purple', 'pink'],
            variants: [100, 200, 300, 400, 500, 600, 700, 800, 900],
            currentColor: '',
            iconColor: '',
            isOpen: false,
            initColor () {
              this.currentColor = 'red-800'
              this.setIconWhite()
            },
            setIconWhite () {
              this.iconColor = 'text-white'
            },
            setIconBlack () {
              this.iconColor = 'text-black'
            },
            selectColor (color, variant) {
              this.currentColor = color + '-' + variant
              if (variant < 500) {
                this.setIconBlack()
              }
              else {
                this.setIconWhite()
              }
            }
          }
      }
    </script> --}}

</div>
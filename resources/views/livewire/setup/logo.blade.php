<div
    x-data="{
        logo_image: @entangle('logo_image'),
        logo_image_src: @entangle('logo_image_src'),
        logo_svg_string: @entangle('logo_svg_string'),
        logo_type: @entangle('logo_type').live
        {{-- image_uploaded: @entangle('image_uploaded') --}}
    }"
    class="flex justify-start gap-4 items-start space-x-7 w-full">

    <div class="mx-auto w-full max-w-sm">
        <div class="pb-3 w-full">
            <label class="block text-sm font-medium leading-6 text-gray-900">Logo</label>
        </div>

        <div class="flex flex-col justify-center items-start w-full">
            <div class="flex justify-start items-center p-1 w-auto text-xs font-medium rounded-lg bg-zinc-100">
                <div 
                    x-on:click="logo_type = 'image';"
                    :class="{'bg-white shadow-sm': logo_type == 'image', 'bg-transparent': logo_type != 'image'}"
                    class="flex-shrink-0 px-4 py-2 text-center text-gray-900 rounded-md cursor-pointer">{{ __('Upload an Image') }}</div>
                    
                <div 
                    x-on:click="logo_type = 'svg'; setTimeout(function(){ document.getElementById('svgTextarea').focus(); }, 10);"
                    :class="{'bg-white shadow-sm': logo_type == 'svg', 'bg-transparent': logo_type != 'svg'}"
                    class="flex-shrink-0 px-4 py-2 text-center text-gray-900 rounded-md cursor-pointer">{{ __('Use an SVG') }}</div>
            </div>
            <div class="mt-2 w-full bg-white">
                <div x-show="logo_type == 'image'" class="rounded-lg">
                    <x-kompass::upload-image 
                        wire:model.live="logo_image"
                        :image="$logo_image_src" 
                        deleteAction="deleteLogoImage" 
                        label=""
                    />
                </div>
                <div x-show="logo_type == 'svg'" x-data="{ example: false }" class="p-3 rounded-lg border border-gray-200 bg-zinc-50">
                    <small class="block text-xs text-base-content/70">{{ __('Enter the SVG code for your logo') }} (<span x-on:click="example=!example" class="text-blue-500 underline cursor-pointer select-none">{{ __('view example') }}</span>)</small>
                    <pre wire:ignore x-show="example" class="p-2.5 mt-2.5 font-mono text-xs bg-gray-100 rounded-md" x-collapse><code class="whitespace-pre-line bg-transparent language-html">&lt;svg viewBox=&quot;0 0 24 24&quot; fill=&quot;black&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;&gt;&lt;rect width=&quot;24&quot; height=&quot;24&quot; /&gt;&lt;/svg&gt;</code></pre>
                    <textarea id="svgTextarea" wire:model="logo_svg_string" class="mt-3 w-full h-20 font-mono text-xs text-gray-700 bg-white rounded border shadow-sm outline-none border-gray-200/60 focus:border-gray-900 focus:ring-2 focus:ring-gray-900 focus:ring-opacity-25 focus:outline-none"></textarea>
                    <div class="flex items-center gap-4">
                        <x-kompass::button wire:click="saveSvg" class="btn btn-primary mt-2">{{ __('Save') }}</x-kompass::button>
                        @if (session()->has('message'))
                            <span class="text-sm text-green-600 mt-2">{{ session('message') }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="pt-5">
            @error('logo_image') <span class="mt-1 text-sm text-red-400">{{ $message }}</span> @enderror
        </div>

        <div class="pb-3 w-full">
            <label class="block text-sm font-medium leading-6 text-gray-900">Logo {{ __('height') }} (in rem)</label>
        </div>
        <div class="w-full h-auto">
            <div class="w-24">
                <x-kompass::input type="number" wire:model.live="logo_height" />
            </div>
        </div>
    </div>

    

    <div class="relative w-full">
        <strong class="block pb-5 text-xs">{{ __('Preview') }}</strong>
        <div class="flex justify-center items-center py-10 w-full bg-white rounded-lg border border-dashed">

            <x-kompass::elements.logo
                :height="$logo_height"
                :isImage="($logo_type == 'image')"
                :imageSrc="($logo_image_src) . '?' . uniqid()"
                :svgString="$logo_svg_string"
            />
            
        </div>
    </div>




</div>
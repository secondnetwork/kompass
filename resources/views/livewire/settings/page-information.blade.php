<div class="l h-auto grid  gap-4" x-data="{
    {{-- supline: @entangle('supline').live, --}}
    image: @entangle('image').live,

}">


    <div class="max-w-2xl py-2">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <x-kompass::input wire:model.live="webtitle" label="{{ __('Website Title') }}"
                placeholder="{{ __('Enter website title') }}" />
            <x-kompass::input wire:model.live="supline" label="{{ __('Subline') }}"
                placeholder="{{ __('Enter subline') }}" />
        </div>

        <x-kompass::form.textarea wire:model.live="description" label="{{ __('Description') }}"
            placeholder="{{ __('Enter website description for SEO') }}" rows="3" />


    </div>
    <hr class="h-px w-full border-none bg-base-300">

    <div class="max-w-2xl gap-4 pb-5 mb-5">
        <div class="pb-3 w-full ">
            <label class="block text-sm font-medium leading-6 text-gray-900">{{ __('Image') }} (Sharepic)</label>
            <p class="text-sm leading-6 text-base-content/70">
                {{ __('Minimum dimension of 1200×630 pixels and an aspect ratio of 1.91:1 and file size requirement of less than 1MB.') }}
            </p>
        </div>
        <div wire:loading wire:target="image">Uploading...</div>


        <div class="lg:w-1/2 h-auto border border-gray-300 rounded-lg">
            @if (isset($image) && $image != '')
                <div class="relative">

                    <img src="{{ is_string($image) ? url($image) . '?' . uniqid() : $image->temporaryUrl() }}"
                        class="w-full h-auto rounded-t-lg aspect-[1.91/1]" />
                    <button wire:click="deleteImage()"
                        class="flex absolute top-0 right-0 items-center px-3 py-1.5 mt-2 mr-2 text-xs font-medium text-white rounded-md bg-red-500/70 hover:bg-red-500/90">
                        <x-tabler-trash class="mr-1 w-4 h-4" />
                        <span>{{ __('Remove Image') }}</span>
                    </button>
                </div>
            @else
                <div class="flex items-center w-full">
                    <label for="image"
                        class="flex flex-col justify-center items-center aspect-[1.91/1] w-full bg-gray-50 rounded-t-lg border-2 border-gray-300 border-dashed cursor-pointer dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                        <div class="flex flex-col justify-center items-center pt-5 pb-6">
                            <x-tabler-cloud-upload class="mb-4 w-8 h-8 text-base-content/70 dark:text-gray-400" />
                            <p class="mb-2 text-sm text-base-content/70 dark:text-gray-400"><span
                                    class="font-semibold">{{ __('Click to upload') }}</span></p>
                            <p class="text-xs text-base-content/70 dark:text-gray-400">{{ __('PNG, JPG or GIF') }}</p>
                        </div>
                        <input id="image" type="file" wire:model="image" class="hidden" />
                    </label>
                </div>
            @endif
            <div class="p-3">
                <div class="text-sm font-bold text-gray-600">{{ $webtitle }} | {{ $supline }}</div>
                <div class="text-sm text-gray-600 py-2">{{ $description }}</div>
                <div class="text-sm text-gray-400">{{ url('/') }}</div>
            </div>

        </div>
    </div>

    <hr class="h-px w-full border-none bg-base-300">
        <div>
            <h3 class="font-bold text-lg">Footer</h3>
            <p class="text-sm text-base-content/60">{{ __('Information displayed in the footer of your website.') }}
            </p>
        </div>
    <div class="max-w-2xl gap-4 pb-5 mb-5">


        <x-kompass::form.textarea wire:model.live="footer_textarea" label="{{ __('Text Area') }}" rows="3" />

        <div class="pt-5 grid grid-cols-1 md:grid-cols-3 gap-6">
            <x-kompass::input wire:model.live="email_address" label="{{ __('E-Mail Address') }}" type="email" />
            <x-kompass::input wire:model.live="phone" label="{{ __('Phone') }}" />
            <x-kompass::input wire:model.live="copyright" label="{{ __('Copyright Text') }}" />
        </div>
    </div>
</div>

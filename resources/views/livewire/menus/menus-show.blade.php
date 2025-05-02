<div>
    {{-- @dump($menu->toArray()) --}}
    {{-- @dump($menuitem->toArray()) --}}


<x-kompass::action-message class="fixed bottom-8 right-8 alert text-white bg-gray-800 w-[35rem] p-8  flex"
    on="status">
    <div class=" mr-4 ">
        <x-tabler-circle-check class="stroke-green-500" />
    </div>
    <div>
        <h4 class="pb-4 text-white">{{ __('Saved.') }}</h4>
        <p class="text-md">{{__('successfully updated')}}</p>
    </div>
</x-kompass::action-message>

<x-kompass::modal data="FormDelete" />

<div x-cloak x-data="{ open: @entangle('FormEdit') }">
    <x-kompass::offcanvas :w="'w-2/4'">
        <x-slot name="body">

            <x-kompass::form.input type="text" label="{{__('Title')}}" wire:model="title" />
            <x-kompass::input-error for="title" class="mt-2" />

            <x-kompass::form.input type="text" label="URL" wire:model="url" />
            <x-kompass::input-error for="url" class="mt-2" />

            <div>
            <x-kompass::form.input type="text" label="Iconclass" wire:model="iconclass" />
            <x-kompass::input-error for="iconclass" class="mt-2" />
            <p class="text-xs text-gray-400">{{__('Find class name at')}} <a class="text-blue-400" href="https://tabler-icons.io/" target="_blank">tabler-icons.io</a></p>
            </div>

            <div>
                <label>{{__('Open')}}</label>
                <x-kompass::select wire:model="target" :options="[
                            ['name' => __('Same tab'),  'id' => '_self'],
                            ['name' => __('New tab'),  'id' => '_blank'],
                        ]">
                </x-kompass::select>
            </div>


            <button wire:click="addNew" class="btn btn-primary">
                <div wire:loading>
                    <svg class="animate-spin h-5 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
                <x-tabler-device-floppy class="icon-lg" wire:loading.remove />
                {{ __('Save') }}    
            </button>

        </x-slot>
    </x-kompass::offcanvas>
</div>

<div class="flex justify-end my-4">
    <button class="btn btn-primary" wire:click="selectItem({{ $menu->id }}, 'additem')"><x-tabler-text-plus stroke-width="1.5" />{{__('Add Menu')}}</button>
</div>


<div wire:sortable="updateGroupOrder" wire:sortable-group="updateItemsOrder"
{{-- wire:sortable-group="updateOrder" --}}
wire:sortable-group.options="{ animation: 100, ghostClass: 'sort-ghost' , chosenClass: 'sort-chosen' ,dragClass: 'sort-drag', removeCloneOnHide: true }"
wire:sortable.options="{ animation: 100, ghostClass: 'sort-ghost' , chosenClass: 'sort-chosen' ,dragClass: 'sort-drag', removeCloneOnHide: true }"
>


@forelse ($menuitem as $key => $item)

<x-kompass::menugroup :item="$item" :fields="$menuitem" :key="$key" :class="'itemblock border-blue-400 shadow border-r-4 border-b-2 mt-4'" />

@empty
<div
class="grid place-content-center border-2 border-dashed border-gray-300 rounded-2xl h-60 text-gray-400">

{{__('Click "Add Menu" to create a new link')}}

</div>
@endforelse

</div>


</div>

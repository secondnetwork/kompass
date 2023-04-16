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
    <x-kompass::offcanvas class="grid p-8">
        <x-slot name="body">
            
            <label>{{__('Title')}}</label>
            <input wire:model="title" type="text" class="form-control" />

            @if ($errors->has('title'))
            <p style="color: red;">{{ $errors->first('title') }}</p>
            @endif

            <label>URL</label>
            <input wire:model="url" type="text" class="form-control" />
            @if ($errors->has('url'))
            <p style="color: red;">{{ $errors->first('url') }}</p>
            @endif

     
            {{-- <label>Icon Class</label>
            <input wire:model="icon_class" type="text" class="form-control" />
            @if ($errors->has('icon_class'))
            <p style="color: red;">{{ $errors->first('icon_class') }}</p>
            @endif
            <label>color</label>
            <input wire:model="color" type="text" class="form-control" />
            @if ($errors->has('color'))
            <p style="color: red;">{{ $errors->first('name') }}</p>
            @endif --}}

            <label>{{__('Open')}}</label>
            <select wire:model="target">
                <option value="_self">{{__('Same tab')}}</option>
                <option value="_blank">{{__('New tab')}}</option>
            </select>
            <div class="flex gap-x-2 justify-end items-center">
            <button class="flex gap-x-2   justify-end items-center text-md"
            wire:click="addNew({{ $menu->id }})">
            <x-tabler-device-floppy class="icon-lg" />
            {{ __('Save') }}
            </button>
            </div>
        </x-slot>
    </x-kompass::offcanvas>
</div>

<div class="flex justify-end my-8">
    <button class="flex gap-x-2 justify-center items-center text-md" wire:click="selectItem({{ $menu->id }}, 'additem')">{{__('Add Menu')}}</button>
</div>

<div wire:sortable="updateGroupOrder" wire:sortable-group="updateItemsOrder"
{{-- wire:sortable-group="updateOrder" --}}
wire:sortable-group.options="{ animation: 100, ghostClass: 'sort-ghost' , chosenClass: 'sort-chosen' ,dragClass: 'sort-drag', removeCloneOnHide: true }"
wire:sortable.options="{ animation: 100, ghostClass: 'sort-ghost' , chosenClass: 'sort-chosen' ,dragClass: 'sort-drag', removeCloneOnHide: true }"
>
@forelse ($menuitem as $item)
              

{{-- <x-kompass::blocksgroup :itemblocks="$itemblocks" :keyblock="$keyblock" :fields="$fields" :page="$page" :class="'itemblock border-blue-400 shadow border-r-4 mt-8'" /> --}}
<x-kompass::menugroup :item="$item"  :class="'itemblock border-blue-400 shadow border-r-4 border-b-2 mt-4'" />


@empty
<div
class="grid place-content-center border-2 border-dashed border-gray-300 rounded-2xl h-60 text-gray-400">

{{__('Click "Add Menu" to create a new link')}}

</div>
@endforelse
</div>


</div>
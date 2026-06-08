<div class="col-span-1 md:col-span-{{ $grid ?? '1' }} ">

<div class="border border-gray-300 rounded-md shadow-sm">
  {{-- Handle muss hier sein und im Parent Div wire:sortable.item --}}
  <div class="flex justify-between items-center bg-slate-200 rounded-t-md p-2 border-b border-gray-300">
    <div wire:sortable.handle class="cursor-move">
        <x-tabler-grip-horizontal class="stroke-current text-gray-600" />
    </div>

              <span wire:click="selectItem({{ $field->id }}, 'delete')"
                                                class="flex justify-center">
                                                <x-tabler-trash class="cursor-pointer stroke-red-500" />
                                            </span>
</div>

      <div class="p-4 flex flex-col gap-4 bg-base-100 rounded-b-md">
      {{-- Binden an die Properties der Kind-Komponente --}}

      <div>

      {{ __('Field name') }}: <strong>{{ $name ?? __('Not set') }}</strong>
    </div>
      <x-kompass::form.input  type="text" wire:model="name" label="{{ __('Name') }}" placeholder="{{ __('Name') }}" />
       @error('field.name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

       <x-kompass::select :searchable="false" label="{{ __('Grid') }}" :options="[
        ['name' => '', 'id' => '1', 'icon' => 'tabler-square-number-1'],
        ['name' => '', 'id' => '2', 'icon' => 'tabler-square-number-2'],
        ['name' => '', 'id' => '3', 'icon' => 'tabler-square-number-3'],
        ['name' => '', 'id' => '4', 'icon' => 'tabler-square-number-4'],
    ]" option-label="name"
    option-value="id" wire:model.live="grid" />


       @error('grid') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

      <x-kompass::select :searchable="false" label="{{ __('Type') }}" :options="field_registry()->fieldSelectOptions()" option-label="name"
      option-value="id" wire:model="type" />

    
        @error('type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
      
  </div>
</div>

<x-kompass::modal data="FormDelete" />  

</div>


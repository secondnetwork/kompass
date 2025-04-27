<div class="col-span-1 md:col-span-{{ $grid ?? '1' }} ">
<div class="border border-gray-300 rounded-md shadow-sm">
  {{-- Handle muss hier sein und im Parent Div wire:sortable.item --}}
  <div class="flex justify-between items-center bg-slate-200 rounded-t-md p-2 border-b border-gray-300">
    <div wire:sortable.handle class="cursor-move">
        <x-tabler-grip-horizontal class="stroke-current text-gray-600" />
    </div>

    <span class="cursor-pointer">
        <x-tabler-trash class="stroke-current text-red-500 hover:text-red-700" />
    </span>
</div>

  <div class="p-4 flex flex-col gap-4 bg-white rounded-b-md">
      {{-- Binden an die Properties der Kind-Komponente --}}

      <div>

      Feldname: <strong>{{ $name ?? 'Nicht gesetzt' }}</strong>
    </div>
      <x-kompass::form.input  type="text" wire:model="name" placeholder="Name" />
       @error('field.name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

       <x-kompass::select label="Grid Spalten (max 4)" :options="[
        ['name' => '1', 'id' => '1'],
        ['name' => '2', 'id' => '2'],
        ['name' => '3', 'id' => '3'],
        ['name' => '4', 'id' => '4'],
    ]" option-label="name"
    option-value="id" wire:model.live="grid" />


       @error('grid') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

       <select wire:model="type">
        <option value="">{{__('Select')}}</option>

        {{-- Option Groups --}}
        <optgroup label="{{__('Basis')}}">
            <option value="text">Text einzeilig</option>
            <option value="text_headline">Headline</option>
            <option value="text_link">Text Link</option>
            <option value="text_url">Url</option>
            <option value="icon">Icon</option>
        </optgroup>
        <optgroup label="{{__('Contents')}}">
            <option value="image">{{__('Image')}}</option>
            {{-- <option value="file">Datei</option> --}}
            <option value="wysiwyg">WYSIWYG-Editor</option>
            <option value="oembed">oEmbed</option>
        </optgroup>
        <optgroup label="{{__('Selection')}}">
            <option value="true_false">{{__('True / False')}}</option>
        </optgroup>
       </select>
        @error('type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

      {{-- Speicher-Button für DIESES Feld --}}
      <button wire:click="saveField('{{ $field->id }}')" class="bg-blue-300 p-1">Feld Speichern</button>
      {{-- Lösch-Button für DIESES Feld --}}
      <button wire:click="deleteField" class="bg-red-300 p-1">Feld Löschen</button>

      
  </div>
</div>
</div>
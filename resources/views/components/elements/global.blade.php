 @props([
    'settings' => [],
    'type' => 'text',
    'getId' => null,
    'selectedItem' => null,
    'name' => '',
    'key' => '',
    'group' => '',
    'valuedata' => '',
    'value' => '',
])
<div>
  @if ($settings->count())
  <table class="min-w-full divide-y divide-gray-200">
      <tbody wire:sort="updateOrder" class="bg-base-100 divide-y divide-gray-200">
          @foreach ($settings as $key => $setting)
          <tr wire:sort:item="{{ $setting->id }}" >
              <td class="p-3">
                  <span class="block text-sm font-medium">{{ __($setting->name) }}</span>
                  <span class="block text-xs text-base-content/50">{{ $setting->description }}</span>
              </td>
              <td class="p-3 text-xs text-base-content/60">
                    <span
                      class="badge badge-sm border-green-200 bg-green-100 text-green-800">
                      @php echo '{{' @endphp setting('{{ $setting->group }}.{{ $setting->key }}') @php
                      echo '}}' @endphp
                  </span>
              </td>
              <td class="px-4 py-3 whitespace-nowrap bg-base-100 text-right">
                  <div class="flex items-center justify-end gap-2">
                      <span wire:click="selectItem({{ $setting->id }}, 'update')" class="cursor-pointer p-1 hover:bg-base-200 rounded">
                          <x-tabler-edit class="h-5 w-5 stroke-blue-500" />
                      </span>
                      <span x-clipboard.raw="{{ setting($setting->group . '.' . $setting->key) }}" class="cursor-pointer p-1 hover:bg-base-200 rounded">
                          <x-tabler-clipboard class="h-5 w-5 stroke-violet-500" />
                      </span>
                      <span wire:click="selectItem({{ $setting->id }}, 'delete')" class="cursor-pointer p-1 hover:bg-base-200 rounded">
                          <x-tabler-trash class="h-5 w-5 stroke-red-500" />
                      </span>
                      <x-tabler-arrow-autofit-height wire:sort:handle class="cursor-move h-5 w-5 stroke-current text-gray-400" />
                  </div>
              </td>
          </tr>
          @endforeach
      </tbody>
  </table>
  @else
  <div class="h-36 grid place-content-center text-base-content/30 italic">{{__('No settings found')}}</div>
  @endif

  <x-kompass::modal data="FormDelete" />

  <div x-cloak x-data="{ open: @entangle('FormAdd')}">
    <x-kompass::offcanvas :w="'w-2/6'" >
        <x-slot name="body">
            <div class="grid gap-6 p-2">
                @if(!$selectedItem)
                    <div class="step-1 grid gap-4">
                        <x-kompass::form.input type="text" name="name" label="Name" wire:model="name" />
                        <x-kompass::form.input type="text" name="key" label="Key" wire:model="key" />
                        <x-kompass::select wire:model="group" name="Group" label="Gruppe" :options="[['name' => 'Header', 'id' => 'header'], ['name' => 'Main', 'id' => 'main'], ['name' => 'Footer', 'id' => 'footer'], ['name' => 'Admin Panel', 'id' => 'admin'], ['name' => 'Mail', 'id' => 'mail'], ['name' => 'Form', 'id' => 'form']]" />
                        <button wire:click="saveStepOne" class="btn btn-primary mt-4 w-full"><span>Weiter zu Typ & Inhalt</span></button>
                    </div>
                @else
                    <div class="step-2 grid gap-4 animate-in slide-in-from-right duration-300">
                        <div x-data="{ showDetails: false }">
                            <div class="flex items-center justify-between border-b pb-2">
                                <button @click="showDetails = !showDetails" class="btn btn-ghost text-[10px]">Details</button>
                            </div>
                            <div x-show="showDetails" class="grid gap-3 my-4">
                                <x-kompass::form.input type="text" name="name" label="Name" wire:model="name" />
                                <x-kompass::form.input type="text" name="key" label="Key" wire:model="key" />
                            </div>
                        </div>
                        <x-kompass::select wire:model.live="type" name="type" label="Type" :options="[['name' => __('Text'), 'id' => 'text', 'icon' => 'tabler-letter-case'], ['name' => __('Rich Textbox'), 'id' => 'rich_text_box', 'icon' => 'tabler-blockquote'], ['name' => __('Image'), 'id' => 'image', 'icon' => 'tabler-photo'], ['name' => __('true or false'), 'id' => 'switch', 'icon' => 'tabler-toggle-left'], ['name' => __('File'), 'id' => 'file', 'icon' => 'tabler-file-zip']]" />
                        <div class="">
                            @switch($type)
                                @case('image')
                                    @if (!empty($valuedata))
                                        @php $file = Secondnetwork\Kompass\Models\File::find($valuedata); @endphp
                                        @if ($file)
                                            <div class="relative group">
                                                <img alt="setting" class="aspect-[4/3] w-full object-cover rounded-xl border shadow-sm"
                                                    src="{{ asset('storage/' . $file->path . '/' . $file->slug . '.' . $file->extension) }}">
                                                <div class="absolute bottom-0 right-0 p-2 flex gap-1">
                                                    <button wire:click="removemedia({{ $selectedItem }})" class="btn btn-error btn-xs"><x-tabler-trash class="h-4 w-4"/></button>
                                                    <button wire:click="selectItem({{ $selectedItem }}, 'addMedia')" class="btn btn-primary btn-xs"><x-tabler-edit class="h-4 w-4"/></button>
                                                </div>
                                            </div>
                                        @endif
                                    @else
                                        <button wire:click="selectItem({{ $selectedItem }}, 'addMedia')"
                                            class="grid place-content-center border-2 border-dashed border-base-300 rounded-2xl hover:border-primary hover:text-primary transition-colors text-base-content/30 w-full aspect-[4/3]">
                                            <x-tabler-photo-plus class="h-12 w-12 stroke-[1]" />
                                            <span class="mt-2 text-sm">Bild hinzufügen</span>
                                        </button>
                                    @endif
                                @break
                                @case('switch') <input @if($valuedata) checked="" @endif wire:change="update('{{ $selectedItem }}', $el.checked)" type="checkbox" class="toggle toggle-primary"> @break
                                @case('rich_text_box') @livewire('editorjs', ['editorId' => $selectedItem, 'value' => is_array($valuedata) ? $valuedata : json_decode($valuedata, true)], key('editor-'.$selectedItem)) @break
                                @default <x-kompass::form.input type="text" name="value" label="Wert" wire:model="valuedata" />
                            @endswitch
                        </div>
                        <button wire:click="addNew" class="btn btn-primary w-full shadow-md">Speichern</button>
                    </div>
                @endif
            </div>
</x-slot>
    </x-kompass::offcanvas>
</div>


<div x-cloak x-data="{ open: @entangle('FormMedia'), ids: @js($getId) }" id="FormMedia">
    <x-kompass::offcanvas :w="'w-3/4'">
        <x-slot name="body">
            @livewire('medialibrary', ['fieldId' => $getId])
        </x-slot>
    </x-kompass::offcanvas>
</div>
</div>
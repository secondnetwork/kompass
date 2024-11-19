<div>
  @if ($settings->count())
  <table  class="min-w-full divide-y divide-gray-200">


      <tbody wire:sortable="updateOrder" class="bg-white divide-y divide-gray-200">

          @foreach ($settings as $key => $setting)

          <tr wire:sortable.item="{{ $setting->id }}" >

              <td class="p-3">
                  <span class="block text-sm">{{ __($setting->name) }}</span>
                  <span class="block text-sm">{{ $setting->description }}</span>
              </td>
              <td  class="w-2 ">

                @if ($setting->type == 'switch')
                <label class="inline-flex items-center mb-5 cursor-pointer">
                    <input @if($setting->data) checked="" @endif wire:change="update('{{ $setting->id }}', $el.checked)" name="{{ $key }}" type="checkbox" class="sr-only peer">
                    <div class="relative w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                  </label>
                @endif
                     
            </td>
              <td class="p-3">
                  <span
                      class="px-2.5 py-2 inline-flex font-semibold rounded-lg text-xs whitespace-nowrap  bg-green-100 text-green-800">
                      @php echo '{{' @endphp setting('{{ $setting->group }}.{{ $setting->key }}') @php
                      echo '}}' @endphp
                  </span>
              </td>

              <td class="px-4 py-3 whitespace-nowrap bg-white">
                  <div class="flex items-center justify-end">

                      <span wire:click="selectItem({{ $setting->id }}, 'update')"
                          class="flex justify-center">
                          <x-tabler-edit class="cursor-pointer stroke-blue-500" />
                      </span>

                      <span
                          x-clipboard.raw="@php echo '{{' @endphp setting('{{ $setting->key }}') @php echo '}}' @endphp">
                          <x-tabler-clipboard class="cursor-pointer stroke-violet-500" />
                      </span>

                      <span wire:click="selectItem({{ $setting->id }}, 'delete')"
                          class="flex justify-center">
                          <x-tabler-trash class="cursor-pointer stroke-red-500" />
                      </span>

                      <x-tabler-arrow-autofit-height wire:sortable.handle class="cursor-move stroke-current text-gray-600" />
                  </div>
              </td>
              @endforeach
          </tr>

      </tbody>
  </table>
  @else
  <div class="h-36 text-center">{{__('No Data')}}</div>

  @endif


  <x-kompass::modal data="FormDelete" />

  <div x-cloak x-data="{ open: @entangle('FormAdd')}">
    <x-kompass::offcanvas :w="'w-2/6'" >
        <x-slot name="body">
            <div class="grid gap-4">
            <x-kompass::form.input type="text" name="name" label="Name" wire:model="name" />
            <x-kompass::input-error for="name" class="mt-2" />

            <x-kompass::form.input type="text" name="key" label="Key" wire:model="key" />

            <x-kompass::select wire:model.live="type" name="type" :options="[
                        ['name' => __('Text'),  'id' => 'text'],
                        ['name' => __('Rich Textbox'),  'id' => 'rich_text_box'],
                        ['name' => __('Image'),  'id' => 'image'],
                        ['name' => __('true or false'),  'id' => 'switch'],
                    ]">
            </x-kompass::select>


            

            <div x-data="{openType: @entangle('type')}">
                @switch($type)
                @case('image')
                
                @if (!empty($valuedata))
                @php
                $file = Secondnetwork\Kompass\Models\File::find($valuedata);
                @endphp

                    @if ($file)
                        @if (Storage::disk('local')->exists('/public/' . $file->path . '/' . $file->slug . '.' .
                        $file->extension))
                        <div class="relative">

                            <img on="pages.pages-show" alt="logo" class="aspect-[4/3] w-full object-cover rounded-xl"
                                src="{{ asset('storage/' . $file->path . '/' . $file->slug . '.' . $file->extension) }}">
                            <action-button
                                class="absolute flex justify-between items-center w-full bottom-0 right-0 z-10 p-3 gap-1 bg-gray-100/80 ">
                                <div class="text-xs font-semibold truncate">{{ $file->name }}</div>
                                <div class="flex">
                                    <span wire:click="removemedia({{ $setting->id }})">
                                        <x-tabler-trash class="cursor-pointer stroke-current text-red-500 " />
                                    </span>
                                    <span wire:click="selectItem({{ $setting->id }}, 'addMedia')">
                                        <x-tabler-edit class=" cursor-pointer stroke-current text-blue-500 " />
                                    </span>
                                </div>
                            </action-button>

                        </div>
                        @endif
                    @endif
                @else
                <span wire:click="selectItem({{ $setting->id }}, 'addMedia')">
                    <img-block
                        class="cursor-pointer grid place-content-center border-2 border-dashed border-gray-400 rounded-2xl text-gray-400 w-1/2 aspect-[4/3] ">
                        <x-tabler-photo-plus class="h-[4rem] w-[4rem] stroke-[1.5]" />
                    </img-block>
                </span>
                @endif
                
                @break
                @case('text_area')
                <textarea name="" id="" cols="30" rows="10" wire:model="value"></textarea>

                @break

                @case('rich_text_box')

                @livewire(
                'editorjs',
                    [
                        'editorId' => $this->selectedItem,
                        'value' => json_decode($this->value, true),
                        'class' => 'cdx-input',
                        'style' => '',
                        'readOnly' => false,
                        'placeholder' => __('write something...'),
                    ]
                )
                @break
                @case('switch')

                <div class="flex items-center">
                    <input @if($valuedata) checked="" @endif wire:change="update('{{ $getId }}', $el.checked)" type="checkbox" id="hs-small-switch"
                        class="relative shrink-0 w-11 h-6 bg-gray-400 checked:bg-none checked:bg-blue-600 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 border border-transparent ring-1 ring-transparent focus:border-blue-600 focus:ring-blue-600 ring-offset-white focus:outline-none appearance-none 
           
                           before:inline-block before:w-5 before:h-5 before:bg-white checked:before:bg-blue-200 before:translate-x-0 checked:before:translate-x-full before:shadow before:rounded-full before:transform before:ring-0 before:transition before:ease-in-out before:duration-200 ">
                </div>

                @break
                @case('file')
                <span wire:click="selectItem({{ $setting->id }}, 'addMedia')">
                    <img-block
                        class="cursor-pointer grid place-content-center border-2 border-dashed border-gray-400 rounded-2xl text-gray-400 w-1/2 aspect-[4/3] ">
                        <x-tabler-file class="h-[4rem] w-[4rem] stroke-[1.5]" />
                    </img-block>
                </span>
                @break
                @case('text')
                <x-kompass::form.input type="text" name="value" wire:model="valuedata" />
                <x-kompass::input-error for="value" class="mt-2" />
                @break
                @default
    
                @endswitch



            </div>
            <x-kompass::select wire:model.live="group" name="Group" :options="[
                ['name' => 'Header',  'id' => 'header'],
                ['name' => 'Main',  'id' => 'main'],
                ['name' => 'Footer',  'id' => 'footer'],
                ['name' => 'Admin Panel',  'id' => 'admin'],
            ]">
            </x-kompass::select>

        
            <x-kompass::input-error for="group" class="mt-2" />

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
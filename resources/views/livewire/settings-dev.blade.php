<livewire-components>

        {{-- <div class="border-b border-gray-200 px-10  grid-3-2 fixed w-full top-0">
            <div id="tab_wrapper" class="relative">
                <!-- The tabs navigation -->
                <nav class="flex bottom-0 z-10">
                     
                  <a class="text-base text-gray-400 border-b p-[1.6rem]" :class="{ 'active  border-current text-blue-600': tab === 'site' }" @click.prevent="tab = 'sitesettings'; window.location.hash = 'sitesettings'" href="#">
                    Settings
                </a>
                  <a class="text-base text-gray-400 border-b p-[1.6rem]" :class="{ 'active  border-current text-blue-600': tab === 'layout' }" @click.prevent="tab = 'layout'; window.location.hash = 'layout'" href="#">
                    Layout
                </a>
                  <a class="text-base text-gray-400 border-b p-[1.6rem]" :class="{ 'active  border-current text-blue-600': tab === 'seo' }" @click.prevent="tab = 'seo'; window.location.hash = 'seo'" href="#">
                    SEO
                </a>
                </nav>
              
    
              
              </div>
            <div class="flex justify-end items-center">

        </div>
    
        </div> --}}
<div class="flex flex-col" x-data="{ tab: window.location.hash ? window.location.hash.substring(1) : 'sites' }">
    <div class=" border-gray-200 py-4 whitespace-nowrap text-sm flex gap-8 justify-end items-center">
        <button wire:click="selectItem('', 'add')" class="flex gap-x-2 justify-center items-center text-md" @click="open = true">
            <x-tabler-square-plus stroke-width="1.5" />{{ __('New Setting') }}
         </button>
    </div>

    <div class="border-b border-gray-200 px-10 py-5 grid-3-2">
        <div id="tab_wrapper" class="relative">
            <!-- The tabs navigation -->
            <nav class="absolute bottom-0 z-10">
                
                @foreach ($settingsGroup as $item)
             

                    <a class="text-base text-gray-400 border-b p-[1.6rem]" :class="{ 'active  border-current text-blue-600': tab === '{{$item->group}}' }" @click.prevent="tab = '{{$item->group}}'; window.location.hash = '{{$item->group}}'" href="#">
                        <span class="capitalize">{{$item->group}}</span>
                    </a>

                @endforeach    
              {{-- <a class="text-base text-gray-400 border-b p-[1.6rem]" :class="{ 'active  border-current text-blue-600': tab === 'sitesettings' }" @click.prevent="tab = 'sitesettings'; window.location.hash = 'sitesettings'" href="#">
                Seiten
            </a>
              <a class="text-base text-gray-400 border-b p-[1.6rem]" :class="{ 'active  border-current text-blue-600': tab === 'layout' }" @click.prevent="tab = 'layout'; window.location.hash = 'layout'" href="#">
                Layout
            </a>
              <a class="text-base text-gray-400 border-b p-[1.6rem]" :class="{ 'active  border-current text-blue-600': tab === 'seo' }" @click.prevent="tab = 'seo'; window.location.hash = 'seo'" href="#">
                SEO
            </a> --}}
            </nav>
          

          
          </div>

    </div>


        <item-setting class="align-middle inline-block min-w-full">

            {{-- <div class="p-4 my-10 border-l-8 text-md border-blue-900  text-white bg-blue-500 rounded-lg " role="alert">
                <h3 class="text-white text-base">Verwendung:</h3>Sie können den Wert jeder Einstellung überall auf der Seite erhalten durch den Aufruf von <strong>setting('key')</strong>
              </div> --}}
         
        
            
            
            @if ($settings->count())
                   
                <item-setting wire:sortable="updateOrder" class="grid gap-4 my-4 p-2 border rounded-md border-dashed border-cyan-400  grid-cols-3">
                    @foreach ($settings as $key => $setting)
                    <div wire:sortable.item="{{ $item->id }}" wire:key="task-{{ $item->id }}" class="p-2 bg-gray-200">

                            <div class="flex justify-between items-center gap-2">
                                <div wire:sortable.handle >
                                    <x-tabler-arrow-autofit-height
                                        class="cursor-move stroke-current text-gray-400" />
                                </div>
                                <div class="flex items-center justify-end">
                                    <spam x-clipboard.raw="@php echo '{{' @endphp setting('{{ $setting->key }}') @php echo '}}' @endphp">
                                        <x-tabler-code-dots class="cursor-pointer stroke-violet-500" />
                                    </spam>
                                    <span wire:click="selectItem({{ $setting->id }}, 'update')" class="flex justify-center""
                                        class="flex justify-center">
                                        <x-tabler-edit class="cursor-pointer stroke-blue-500" />
                                    </spam>
                                    <span wire:click="selectItem({{ $setting->id }}, 'delete')" class="flex justify-center">
                                        <x-tabler-trash class="cursor-pointer stroke-red-500" />
                                    </span>
                                </div>
                            </div>
                            @foreach ($data as $key => $value) 
         
                          
                                    <div class="flex items-center text-sm font-medium text-gray-900 ">
                                        {{-- @if ($key == 1)

                                            @if ($setting->type == 'image')
                                            image
                                            @endif
                                            @if ($setting->type == 'text')
                                            {{ $setting->$value }}
                                            @endif
            
                                        @endif --}}
                                        {{ $setting->$value }}
                                    </div>
                        
                            @endforeach
                   
                                <span class="px-2.5 py-2 inline-flex font-semibold rounded-lg text-xs whitespace-nowrap  bg-green-100 text-green-800">
                                  @php echo '{{' @endphp setting('{{ $setting->key }}') @php echo '}}' @endphp
                                  </span>
                     

                    </div>
                    @endforeach
                </item-setting>
        
     
        @else
            <div class="h-36 text-center">{{__('No Data')}}</div>
        
        @endif

 
    </item-setting>

    <x-kompass::modal data="FormDelete" />

    <div x-cloak x-data="{ open: @entangle('FormAdd') }">
        <x-kompass::offcanvas class="p-8">
            <x-slot name="body">

                <x-kompass::form.input type="text" name="name" wire:model="name" />
                <x-kompass::input-error for="name" class="mt-2" />

                <x-kompass::form.input type="text" name="key" wire:model="key" />
                <x-kompass::input-error for="key" class="mt-2" />

                <x-kompass::form.input type="text" name="value" wire:model="value" />
                <x-kompass::input-error for="value" class="mt-2" />

                <x-kompass::form.input type="text" name="group" wire:model.lazy="group" />
                <x-kompass::input-error for="group" class="mt-2" />

                <select wire:model="type" name="type" class="form-control" required="required">
                    <option value="text">Text</option>
                    <option value="text_area">Text Area</option>
                    <option value="rich_text_box">Rich Textbox</option>
            
                    <option value="checkbox">Check Box</option>
                    <option value="radio">Radio Button</option>
            
                    <option value="file">Datei</option>
                    <option value="image">Bild</option>
                </select>

                <button wire:click="addNew" class="btn btn-primary">Save</button>

            </x-slot>
        </x-kompass::offcanvas>
    </div>


</div>
</livewire-components>
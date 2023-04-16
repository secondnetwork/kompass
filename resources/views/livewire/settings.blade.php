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
<div class="flex flex-col" x-data="{ tab: window.location.hash ? window.location.hash.substring(1) : 'footer' }">
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
     
    <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
        
        
        @if ($settings->count())
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                @foreach ($headers as $key => $value)
                    <th scope="col"
                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                        {{ __($value) }}
                    </th>
                @endforeach
               
            <tbody wire:sortable="updateOrder" class="bg-white divide-y divide-gray-200">
                @foreach ($settings as $key => $setting)
                    <tr x-show="tab === '{{$setting->group}}'" wire:sortable.item="{{ $setting->id }}">
                        <td wire:sortable.handle class="px-4 w-4 ">
                            <x-tabler-arrow-autofit-height
                                class="cursor-move stroke-current text-gray-400" />
                        </td>
                       
                        @foreach ($data as $key => $value) 
     
                            <td class="px-6 py-4 bg-white ">
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
                            </td>
                        @endforeach
                        <td>           <span class="px-2.5 py-2 inline-flex font-semibold rounded-lg text-xs whitespace-nowrap  bg-green-100 text-green-800">
                            @php echo '{{' @endphp setting('{{ $setting->key }}') @php echo '}}' @endphp
                            </span></td>
                        <td class="px-4 py-3 whitespace-nowrap bg-white flex justify-end items-center gap-2">
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
                        </td>
                @endforeach
                </tr>
    
    
            </tbody>
        </table>
    @else
        <div class="h-36 text-center">{{__('No Data')}}</div>
    
    @endif

</div>
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
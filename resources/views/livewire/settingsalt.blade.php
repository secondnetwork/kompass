<div class="h-full" x-data="{ 
    'asidenav': new URLSearchParams(window.location.search).get('asidenav') || 'global',
    'tab': new URLSearchParams(window.location.search).get('tab') || 'meta',
    addQueryParam(key, value) {
        // Create a URL object based on the current document URL
        let url = new URL(window.location.href);

        // Set or replace the query parameter
        url.searchParams.set(key, value);

        // Update the URL in the address bar without reloading the page
        window.history.pushState({ path: url.toString() }, '', url.toString());
    }
}">
$data = [
    [ ]
    ]

// Schritt 2: PHP-Array in eine Laravel-Collection umwandeln
$collection = collect($data);

// Schritt 3: Collection nach dem Alter sortieren
$sortedCollection = $collection->sortByDesc('order');

// Optional: Wenn du die Collection in ein Array umwandeln möchtest
$sortedArray = $sortedCollection->values()->toArray();

// Ausgabe der sortierten Collection

    <grid-side class="grid grid-cols-11 h-full gap-4" x-data="{ activeTab:  'global' }">

        <aside class="col-start-1 lg:col-end-3 border-r border-gray-200 h-full ">

            <div class="uppercase text-xs mt-5 border-r-2 border-gray-400 text-gray-500 font-semibold">{{ __('Global Settings') }}</div>
            <nav class="setting">
                <ul>
                    <li>
                        <a @click="activeTab ='global'" x-data="{ activeTabName:  'global' }" class="cursor-pointer text-blue-600" :class="activeTab == activeTabName ? ' text-blue-600' : 'text-gray-600'">                
                            <span class="capitalize">{{ __('Settings') }}</span>
                        </a>
                    </li>

                </ul>
            </nav>

            {{-- @php
            $asidenav = ['global' => __('Settings'),  'favicon' => 'Backend'];
            @endphp
            <nav class="" aria-label="Tabs">
                <ul>
                @foreach($asidenav as $slug => $tab)
                
                <li>
                    <a href="#_" @click.prevent="asidenav = '{{ $slug }}'" 
                        :class="{ 'border-indigo-500 text-indigo-600' : asidenav == '{{ $slug }}', ' text-gray-500 hover:border-gray-300 hover:text-gray-700' : asidenav != '{{ $slug }}' }"
                        class=" text-sm font-medium ">{{ $tab }}</a>
                    </li>
                @endforeach
            </ul>
            </nav> --}}

            <div class="uppercase text-xs mt-4 border-r-2 border-gray-400 text-gray-500 font-semibold">Theme {{ __('Settings') }}</div>
            @php
            $asidenav = ['frondend' => __('Page Customize'),  'backend' => __('Admin Panel Customize'), 'menu' => __('Menu'),];
            @endphp
            <nav class="" aria-label="Tabs">
                <ul>
                @foreach($asidenav as $slug => $tab)
                
                <li>
                    <a href="#_" @click.prevent="asidenav = '{{ $slug }}'" 
                        :class="{ 'border-indigo-500 text-indigo-600' : asidenav == '{{ $slug }}', ' text-gray-500 hover:border-gray-300 hover:text-gray-700' : asidenav != '{{ $slug }}' }"
                        class=" text-sm font-medium ">{{ $tab }}</a>
                    </li>
                @endforeach
            </ul>
            </nav>

            <div class="uppercase text-xs mt-4 border-r-2 border-gray-400 text-gray-500 font-semibold">{{ __('Tools') }}</div>
            @php
            $asidenav = ['Weiterleitungen' => __('Redirection'),  'Backup' => __('Backup'), 'Activity-log' => __('Activity-log'),];
            @endphp
            <nav class="" aria-label="Tabs">
                <ul>
                @foreach($asidenav as $slug => $tab)
                
                <li>
                    <a href="#_" @click.prevent="asidenav = '{{ $slug }}'" 
                        :class="{ 'border-indigo-500 text-indigo-600' : asidenav == '{{ $slug }}', ' text-gray-500 hover:border-gray-300 hover:text-gray-700' : asidenav != '{{ $slug }}' }"
                        class=" text-sm font-medium flex items-center">{{ $tab }} </a>
                    </li>
                @endforeach
            </ul>
            </nav>

            <div class="uppercase text-xs mt-5  text-gray-500 font-semibold">Customize {{ __('Settings') }}</div>
            <nav class="setting">


                    <ul>
                        @foreach ($settingsGroup as $item)
                        <li>

                            <a  
                                @click="activeTab ='{{$item->group}}'"
                                x-data="{ activeTabName:  '{{$item->group}}' }"
                                class="cursor-pointer"
                                 :class="activeTab == activeTabName ? ' text-blue-600' : 'text-gray-600'">
                                {{-- :class="{ 'active border-current text-blue-600': activeTab == activeTabName } " --}}
                                
                                <span class="capitalize">{{$item->group}}</span>
                            </a>
                        </li>
                        @endforeach

                    </ul>
            </nav>

        

            {{-- <livewire:adminmenu name="adminsettings"> --}}
        </aside>

        <div class="flex flex-col col-start-3 col-end-12">

 

            <div class=" border-gray-200 py-4 whitespace-nowrap text-sm flex gap-8 justify-between items-center">
      
                    <h3 class="font-bold text-left">{{ __('Settings') }}</h3>
                    {{-- <p class="text-sm text-left text-gray-600">{{ __('Global Settings') }}</p> --}}
     
                {{-- <button wire:click="selectItem('', 'add')" class="flex btn gap-x-2 justify-center items-center text-md"
                    @click="open = true">
                    <x-tabler-settings-plus stroke-width="1.5" />{{ __('New Setting') }}
                </button> --}}
            </div>


            <item-setting class="align-middle inline-block min-w-full" >

                <div x-show="activeTab === 'global'" >
                    <div class="border-b border-gray-200">
                        @php
                            $tabs = ['logo' => 'Logo',  'favicon' => 'Favicon', 'meta' => 'Metadata', 'background' => 'background','css' => 'css'];
                        @endphp
                        <nav class="flex -mb-px space-x-8" aria-label="Tabs">
                            @foreach($tabs as $slug => $tab)
                                <a href="#_" @click.prevent="tab = '{{ $slug }}'" 
                                    :class="{ 'border-indigo-500 text-indigo-600' : tab == '{{ $slug }}', 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' : tab != '{{ $slug }}' }"
                                    class="px-1 py-4 text-sm font-medium whitespace-nowrap border-b-2">{{ $tab }}</a>
                            @endforeach
                            <!-- Current: "border-indigo-500 text-indigo-600", Default: "border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700" -->
                        </nav>
                        </div>

                        <div class="grid gap-y-4 py-8">
                          
                            {{-- <livewire:brokenlink lazy /> --}}
                            {{-- <livewire:redirect lazy /> --}}

                            <div x-show="tab == 'logo'" class="w-full h-auto" x-cloak>
                                <livewire:setup.logo lazy />
                        
                            </div>
                          <div x-show="tab == 'background'" class="w-full h-auto" x-cloak>
                                <livewire:setup.background lazy  />
                            </div>
                            {{-- <div x-show="tab == 'colors'" class="w-full h-auto" x-cloak>
                                <livewire:setup.color />
                            </div> --}}
                            {{-- <div x-show="tab == 'alignment'" class="w-full h-auto" x-cloak>
                                <livewire:setup.alignment />
                            </div> --}}
                            <div x-show="tab == 'favicon'" class="w-full h-auto" x-cloak>
                                <livewire:setup.favicon lazy />
                            </div>
                            <div x-show="tab == 'css'" class="w-full h-auto" x-cloak>
                                <livewire:setup.css lazy />
                            </div>
    
                            <div x-show="tab == 'meta'" class="w-full h-auto grid gap-4 max-w-xl" x-cloak>
    
                                <x-kompass::input wire:model="email" label="{{ __('Website') }} {{ __('Title') }}" />
                                <x-kompass::input wire:model="email" label="{{ __('Subline') }}" />

                                <x-kompass::form.textarea wire:model="email" label="{{ __('Description') }}" />



                             <div class="pb-5 mb-5 border-b border-zinc-200">
                                    <div class="pb-3 w-full">
                                        <label class="block text-sm font-medium leading-6 text-gray-900">{{ __('Image') }} (Sharepic)</label>
                                        <p class="text-sm leading-6 text-gray-400">Reconciling the guidelines for the image is simple: follow Facebook recommendation of a minimum dimension of 1200×630 pixels and an aspect ratio of 1.91:1, but adhere to Twitter file size requirement of less than 1MB.
                                        </p>
                                    </div>
                                    <div class="w-full h-auto ">
                                        @if(isset($image) && $image != '')
                                            <div class="relative">
                                                <img src="{{ url($image) . '?' . uniqid() }}" class="w-full h-auto rounded-md aspect-[1.91/1]" />
                                                <button wire:click="deleteBackgroundImage()" class="flex absolute top-0 right-0 items-center px-3 py-1.5 mt-2 mr-2 text-xs font-medium text-white rounded-md bg-red-500/70 hover:bg-red-500/90">
                                                    <x-tabler-trash class="mr-1 w-4 h-4" />
                                                    <span>Remove Image</span>
                                                </button>
                                            </div>
                                        @else
                                            <div class="flex items-center w-full">
                                                <label for="image" class="flex flex-col justify-center items-center aspect-[1.91/1] h-64 bg-gray-50 rounded-lg border-2 border-gray-300 border-dashed cursor-pointer dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                                                    <div class="flex flex-col justify-center items-center pt-5 pb-6">
                                                        <svg class="mb-4 w-8 h-8 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                                        </svg>
                                                        <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Click to upload</span></p>
                                                        <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG or GIF</p>
                                                    </div>
                                                    <input id="image" type="file" wire:model="image" class="hidden" />
                                                </label>
                                            </div> 
                                        @endif
                                    </div>
                                </div>

                                {{-- <h6>Contact details <span class="text-sm">(Footer)</span></h6>
                                <x-kompass::form.textarea wire:model="email" label="{{ __('Text Area') }}" />
                                <x-kompass::input wire:model="email" label="{{ __('E-Mail Address') }}" />
                                <x-kompass::input wire:model="email" label="{{ __('Phone') }}" /> --}}

                                
                                </div>
    

                        </div>
 
                        @if ($settingsglobal->count())
                        <table  class="min-w-full divide-y divide-gray-200">

    
                            <tbody wire:sortable="updateOrder" class="bg-white divide-y divide-gray-200">
    
    
                       
    
                                @foreach ($settingsglobal as $key => $setting)
    
                                
                                <tr wire:sortable.item="{{ $setting->id }}" :class="{ 'active': activeTab === '{{ $setting->group }}' }" x-show.transition.in.opacity.duration.600="activeTab === '{{ $setting->group }}'">
                                    <td  class="w-2 ">

                                        @if ($setting->type == 'switch')
                                        <label class="inline-flex items-center mb-5 cursor-pointer">
                                            <input @if($setting->data) checked="" @endif wire:change="update('{{ $setting->id }}', $el.checked)" name="{{ $key }}" type="checkbox" class="sr-only peer">
                                            <div class="relative w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                                          </label>
                                        @endif
                                             
                                    </td>
                                    <td class="p-3">
                                        <strong>{{ __($setting->name) }}</strong>
                                        <span class="block text-sm">{{ $setting->description }}</span>
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
    

                 
                </div>
                <div x-show="activeTab !== 'global'" class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">

                    
                    @if ($settings->count())
                    <table  class="min-w-full divide-y divide-gray-200">
                        <thead>
                            @foreach ($headers as $key => $value)
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                {{ __($value) }}
                            </th>
                            @endforeach

                        <tbody wire:sortable="updateOrder" class="bg-white divide-y divide-gray-200">


                   

                            @foreach ($settings as $key => $setting)


                            <tr wire:sortable.item="{{ $setting->id }}" :class="{ 'active': activeTab === '{{ $setting->group }}' }" x-show.transition.in.opacity.duration.600="activeTab === '{{ $setting->group }}'">
        

                                @foreach ($data as $key => $value)
                                
                                <td class="p-4">
                                    <div class="flex  items-center text-sm font-medium text-gray-900 ">
                                        @if ($key == 0)
                                        {{ $setting->name }}
                                        @endif
                                        
                                        
                                        @if ($key == 1)
                   
                                        @if ($setting->type == 'image')
                                        image
                                        @endif
                                        @if ($setting->type == 'switch')
                                            @if ($setting->$value)
                                                true
                                            @else
                                                false
                                            @endif
                              
                                        @endif
                                        @if ($setting->type == 'text')
                                        {{ $setting->$value }}
                                        @endif
                                        @if ($setting->type == 'rich_text_box')

                                            @php
                                            $dataJson = json_decode($setting->$value);
                                            @endphp
                                            @if ($setting->$value)
                                            <div class="flex flex-col">
                                                @foreach ($dataJson->blocks as $block)

                                                @switch($block->type)
                                                    @case('header')
                                                    <div class="font-bold text-lg">{!! $block->data->text !!}</div>
                                                    @break
                                                    @case('paragraph')
                                                    <div>{!! $block->data->text !!}</div>
                                                    @break
                                                    @default
                                                @endswitch

                                                @endforeach
                                            </div>
                                            @endif

                                        {{-- {{ $setting->$value }} --}}
                                        @endif

                                        @endif
                                        {{-- {{ $setting->$value }} --}}
                                    </div>
                                </td>
                                @endforeach
                                <td class="p-4">
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

                                        {{-- <span
                                            x-clipboard.raw="@php echo '{{' @endphp setting('{{ $setting->key }}') @php echo '}}' @endphp">
                                            <x-tabler-clipboard class="cursor-pointer stroke-violet-500" />
                                        </span> --}}

                                        <span wire:click="selectItem({{ $setting->id }}, 'delete')"
                                            class="flex justify-center">
                                            <x-tabler-trash class="cursor-pointer stroke-red-500" />
                                        </span>
                                    </div>
                                </td>
                                <td wire:sortable.handle class="px-4 w-4 ">
                                    <x-tabler-arrow-autofit-height class="cursor-move stroke-current text-gray-600" />
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



        </div>
    </grid-side>




    <x-kompass::modal data="FormDelete" />

    <div x-cloak x-data="{ open: @entangle('FormAdd')}">
        <x-kompass::offcanvas :w="'w-2/6'">
            <x-slot name="body">
              
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
                    @default
                    <x-kompass::form.input type="text" name="value" wire:model="valuedata" />
                    <x-kompass::input-error for="value" class="mt-2" />
                    @endswitch



                </div>

                <x-kompass::form.input type="text" label="Group" name="group" wire:model.lazy="group" />
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

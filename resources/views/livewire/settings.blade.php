<div>
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
            <div class="uppercase text-xs mt-5 border-r-2 border-gray-400 text-gray-500 font-semibold">Theme {{ __('Settings') }}</div>
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

            <div class="uppercase text-xs mt-5  text-gray-500 font-semibold">{{ __('Tools') }}</div>

            {{-- <livewire:adminmenu name="adminsettings"> --}}
        </aside>

        <div class="flex flex-col col-start-3 col-end-12">
            <div class=" border-gray-200 py-4 whitespace-nowrap text-sm flex gap-8 justify-end items-center">
                <button wire:click="selectItem('', 'add')" class="flex btn gap-x-2 justify-center items-center text-md"
                    @click="open = true">
                    <x-tabler-settings-plus stroke-width="1.5" />{{ __('New Setting') }}
                </button>
            </div>


            <item-setting class="align-middle inline-block min-w-full" >

                <div x-show="activeTab === 'global'" >
                        @if ($settingsglobal->count())
                        <table  class="min-w-full divide-y divide-gray-200">
                            <thead>
                                @foreach ($headers as $key => $value)
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    {{ __($value) }}
                                </th>
                                @endforeach
    
                            <tbody wire:sortable="updateOrder" class="bg-white divide-y divide-gray-200">
    
    
                       
    
                                @foreach ($settingsglobal as $key => $setting)
    
    
                                <tr wire:sortable.item="{{ $setting->id }}" :class="{ 'active': activeTab === '{{ $setting->group }}' }" x-show.transition.in.opacity.duration.600="activeTab === '{{ $setting->group }}'">
                                    <td wire:sortable.handle class="px-4 w-4 ">
                                        <x-tabler-arrow-autofit-height class="cursor-move stroke-current text-gray-600" />
                                    </td>
    
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
                                                {{-- @if ($setting->$value)
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
                                                @endif --}}
    
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
                                <td wire:sortable.handle class="px-4 w-4 ">
                                    <x-tabler-arrow-autofit-height class="cursor-move stroke-current text-gray-600" />
                                </td>

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
                        <input value="true" wire:model="valuedata" type="checkbox" id="hs-small-switch"
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

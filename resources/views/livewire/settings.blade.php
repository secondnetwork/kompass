<livewire-components>
    <grid-side class="grid grid-cols-11 h-full gap-4">
     
    <aside class="col-start-1 lg:col-end-3 border-r border-gray-200 h-full ">
   
   <div class="uppercase text-xs mt-5 border-r-2 border-gray-400 text-gray-500 font-semibold">{{ __('Global Settings') }}</div>
   <nav class="setting">
   
       
   
   <ul x-data>
   @foreach ($settingsGroup as $item)
                
   <li class="my-2">
   <a wire:click="pagetap('{{$item->group}}')" class="text-base text-gray-400 cursor-pointer" :class="{ 'active  border-current text-blue-600': '{{$pagetap}}' === '{{$item->group}}' }" >
       <span class="capitalize">{{$item->group}}</span>
   </a>
   </li>
   @endforeach 
    
   {{-- Application
   API Tokens
   Content manager
   Webhooks
   Single Sign-On
   Media Library
   Documentation
   Internationalization
   ADMINISTRATION PANEL
   <div class="uppercase text-xs mt-5 text-gray-500 font-semibold">{{ __('Permissions') }}</div>
   
   <a class="flex gap-2 my-1" @if(Route::is('admin.account*')  ) class="active" @endif href="/admin/account"><x-tabler-users class="icon-lg"/><span>{{ __('User account') }}</span></a>
   <a class="flex gap-2 my-1" @if(Route::is('admin.roles*')  ) class="active" @endif href="/admin/roles"><x-tabler-lock-access class="icon-lg"/><span>{{ __('Roles') }}</span></a> --}}
   
   
   {{-- <div class="uppercase text-xs mt-5 text-gray-500 font-semibold">{{ __('Advanced settings') }}</div> --}}
   </ul>
   </nav> 
    </aside>
   
   <div class="flex flex-col lg:col-start-3 col-end-12" >
       <div class=" border-gray-200 py-4 whitespace-nowrap text-sm flex gap-8 justify-end items-center">
           <button wire:click="selectItem('', 'add')" class="flex gap-x-2 justify-center items-center text-md" @click="open = true">
               <x-tabler-square-plus stroke-width="1.5" />{{ __('New Setting') }}
            </button>
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
               
                   
                       <tr wire:sortable.item="{{ $setting->id }}">
                           <td wire:sortable.handle class="px-4 w-4 ">
                               <x-tabler-arrow-autofit-height
                                   class="cursor-move stroke-current text-gray-400" />
                           </td>
                          
                           @foreach ($data as $key => $value) 
        
                               <td class="px-6 py-4 bg-white ">
                                   <div class="flex items-center text-sm font-medium text-gray-900 ">
                                       @if ($key == 1)
   
                                           @if ($setting->type == 'image')
                                           image
                                           @endif
                                           @if ($setting->type == 'text')
                                           {{ $setting->$value }}
                                           @endif
           
                                       @endif
                                       {{ $setting->$value }}
                                   </div>
                               </td>
                           @endforeach
                           <td>           
                            <span class="px-2.5 py-2 inline-flex font-semibold rounded-lg text-xs whitespace-nowrap  bg-green-100 text-green-800">
                               @php echo '{{' @endphp setting('{{ $setting->group }}.{{ $setting->key }}') @php echo '}}' @endphp
                               </span></td>
                           <td class="px-4 py-3 whitespace-nowrap bg-white flex justify-end items-center gap-2">
                               <div class="flex items-center justify-end">

                                   <span wire:click="selectItem({{ $setting->id }}, 'update')" class="flex justify-center""
                                       class="flex justify-center">
                                       <x-tabler-edit class="cursor-pointer stroke-blue-500" />
                                   </spam>
                                   <spam x-clipboard.raw="@php echo '{{' @endphp setting('{{ $setting->key }}') @php echo '}}' @endphp">
                                    <x-tabler-clipboard class="cursor-pointer stroke-violet-500" />
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
   
   
   
   </div> 
   </grid-side> 
   
   
   
   
       <x-kompass::modal data="FormDelete" />
    
       <div x-cloak x-data="{ open: @entangle('FormAdd')}">
           <x-kompass::offcanvas :w="'w-2/6'">
               <x-slot name="body">
                   
                   <x-kompass::form.input type="text" name="name" wire:model="name" />
                   <x-kompass::input-error for="name" class="mt-2" />
   
   
                   <select wire:model="type" name="type" class="form-control" required="required">
                       <option value="text">{{__('Text')}}</option>
                       <option value="text_area">{{__('Text Area')}}</option>
                       <option value="rich_text_box">{{__('Rich Textbox')}}</option>
                       <option value="switch">{{__('true or false')}}</option>
                       {{-- <option value="file">{{__('File')}}</option> --}}
                       <option value="image">{{__('Image')}}</option>
                   </select>
                   <div x-data="{openType: @entangle('type')}">
                       
   
                       @switch($type)
                           @case('image')
                           @if (!empty($setting->$value))
                           @php
                               $file = Secondnetwork\Kompass\Models\File::find($setting->$value);
                           @endphp
                   
                           @if ($file)
                               @if (Storage::disk('local')->exists('/public/' . $file->path . '/' . $file->slug . '.' . $file->extension))
                               <div class="relative">
                               
                                   <img on="pages.pages-show" alt="logo" class="aspect-[4/3] w-full object-cover rounded-xl"
                                       src="{{ asset('storage' . $file->path . '/' . $file->slug . '_small.' . $file->extension) }}">
                                   <action-button class="absolute flex justify-between items-center w-full bottom-0 right-0 z-10 p-3 gap-1 bg-gray-100/80 ">
                                       <div class="text-xs font-semibold truncate">{{ $file->name }}</div>
                                       <div class="flex">
                                           <span  wire:click="removemediaIngallery({{ $setting->id }})">
                                               <x-tabler-trash class="cursor-pointer stroke-current text-red-500 " />
                                           </span>
                                           <span wire:click="selectItem({{ $setting->id }}, 'addMedia')"">
                                               <x-tabler-edit class=" cursor-pointer stroke-current text-blue-500 " />
                                           </span>
                                       </div>
                                   </action-button> 
                   
                   
                          
                           </div>
                               @endif
                           @else
                               <img-block 
                                   class="cursor-pointer grid place-content-center border-2 border-dashed border-gray-400 rounded-2xl text-gray-400 w-1/2 aspect-[4/3] ">
                                   <x-tabler-photo-plus class="h-[4rem] w-[4rem] stroke-[1.5]" />
                               </img-block>
                           @endif
                           @else
                    
                           <span wire:click="selectItem({{ $setting->id }}, 'addMedia')"">
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
                                   <x-quill id="{{ $key }}" wire:model="value" />
                               @break
                           @case('switch')
   
                           <div class="flex items-center">
                               <input value="true" wire:model="value" type="checkbox" id="hs-small-switch" 
                               class="relative shrink-0 w-11 h-6 bg-gray-400 checked:bg-none checked:bg-blue-600 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 border border-transparent ring-1 ring-transparent focus:border-blue-600 focus:ring-blue-600 ring-offset-white focus:outline-none appearance-none 
               
                               before:inline-block before:w-5 before:h-5 before:bg-white checked:before:bg-blue-200 before:translate-x-0 checked:before:translate-x-full before:shadow before:rounded-full before:transform before:ring-0 before:transition before:ease-in-out before:duration-200 ">
                           </div>
   
                           @break    
                           @case('file')
                           <span wire:click="selectItem({{ $setting->id }}, 'addMedia')"">
                               <img-block 
                                   class="cursor-pointer grid place-content-center border-2 border-dashed border-gray-400 rounded-2xl text-gray-400 w-1/2 aspect-[4/3] ">
                                   <x-tabler-file class="h-[4rem] w-[4rem] stroke-[1.5]" />
                               </img-block>
                           </span>  
                                @break    
                           @default
                           <x-kompass::form.input type="text" name="value" wire:model="value" />
                           <x-kompass::input-error for="value" class="mt-2" />
                       @endswitch    
       
       
         
                       </div>
   
                   <x-kompass::form.input type="text" name="group" wire:model.lazy="group" />
                   <x-kompass::input-error for="group" class="mt-2" />
   
                   <button wire:click="addNew" class="btn btn-primary">{{__('Save')}}</button>
   
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
   </livewire-components>
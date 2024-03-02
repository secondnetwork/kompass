@props([
    'itemblocks' => '',
    'fields' => '',
    'page' => '',
    'class' => '',
])

<div>
  <nav
      class="px-6 py-2 bg-gray-200 shadow-inner shadow-black/20 flex items-center gap-6 @if ($itemblocks->type == 'group' || $itemblocks->type == 'accordiongroup') border-b-4 border-purple-700 @endif">

      <x-kompass::nav-item :itemblocks="$itemblocks" />

  </nav>
  <div class="grid gap-6 py-6 grid-cols-{{ $itemblocks->grid }} @if ($itemblocks->type == 'group' || $itemblocks->type == 'accordiongroup') @endif">

      @switch($itemblocks->type)
          @case('video')
          @php
              $cardimg = 'false';
              $cardoembed = 'false';
              $box = 'true';
              $xShow = 'true';
          @endphp
          @foreach ($itemblocks->datafield as $key => $itemfields)
              @if ($itemfields->type == 'poster')
              @php
                  $cardimg = 'true';  $xShow = 'false';
              @endphp
              @endif
              @if ($itemfields->type == 'video')
              @php
                  $cardimg = 'true'; $xShow = 'false';
              @endphp
              @endif
              @if ($itemfields->type == 'oembed')
              @php
                  $cardoembed = 'true'; $xShow = 'false';
              @endphp
              @endif
          @endforeach


              <div x-data="{ oEmbed:{{ $cardoembed }}, videoInt:{{ $cardimg }}, box:{{ $box }}}">
       
                  @if ($xShow == 'true')
                      <div class="flex justify-end" x-show="!box">
                          <span @click="box = true, oEmbed = false, videoInt = false" class="cursor-pointer p-2 bg-gray-100 rounded-full hover:bg-gray-300 transition-all">
                              <x-tabler-x />
                          </span>
                      </div>
                  


                      <div class="grid grid-cols-2 gap-4" x-show="box">

                          <button class="btn justify-center" x-show="!oEmbed" x-on:click="oEmbed = true,box = false">    
                              <x-tabler-brand-youtube/>
                              {{ __('embed') }}
                          </button>
                          <button class="btn justify-center" x-on:click="videoInt = true,box = false"> 
                              <x-tabler-photo-video/> 
                              {{ __('Add file') }}
                          </button>
                      </div>
                      
                  @endif

                  <div x-show="videoInt">
                      <div class="@container">
                          <div class="grid @sm:grid-cols-1 @lg:grid-cols-3  gap-6">
  
                              @php
                                  $cardimg = 'false';
                              @endphp
  
                              @foreach ($itemblocks->datafield as $key => $itemfields)
                                  @if ($itemblocks->id == $itemfields->block_id && $itemfields->type == 'poster')
                                      @php
                                          $cardimg = 'true';
                                      @endphp
                                      <x-kompass::blocks key="{{ $key }}" type="{{ $itemfields->type }}"
                                          name="{{ $itemfields->name }}" fields="{!! $itemblocks->datafield[$key]['data'] !!}"
                                          idField="{{ $itemblocks->datafield[$key]['id'] }}" blockId="{{ $itemblocks->id }}">
                                      </x-kompass::blocks>
                                  @endif
                              @endforeach
  
                              @if ($cardimg == 'false')
                                  <div>
                                      <img-block wire:click="selectitem('addMedia',0,'poster',{{ $itemblocks->id }})"
                                          class="cursor-pointer grid place-content-center border-2 border-dashed border-gray-400 rounded-2xl w-full text-gray-400 aspect-video ">
                                          <x-tabler-photo-plus class="h-[4rem] w-[4rem] stroke-[1.5]" />
                                      </img-block>
                                  </div>
                              @endif
  
                              @php
                                  $cardvideo = 'false';
                              @endphp
                              @foreach ($itemblocks->datafield as $key => $itemfields)
                                  @if ($itemblocks->id == $itemfields->block_id && $itemfields->type == 'video')
                                      @php
                                          $cardvideo = 'true';
                                      @endphp
                                      <x-kompass::blocks key="{{ $key }}" type="{{ $itemfields->type }}"
                                          name="{{ $itemfields->name }}" fields="{!! $itemblocks->datafield[$key]['data'] !!}"
                                          idField="{{ $itemblocks->datafield[$key]['id'] }}" blockId="{{ $itemblocks->id }}">
                                      </x-kompass::blocks>
                                  @endif
                              @endforeach
  
                              @if ($cardvideo == 'false')
                                  <div>
                                      <img-block wire:click="selectitem('addMedia',0,'video',{{ $itemblocks->id }})"
                                          class="cursor-pointer grid place-content-center border-2 border-dashed border-gray-400 rounded-2xl w-full text-gray-400 aspect-video ">
                                          <x-tabler-video-plus class="h-[4rem] w-[4rem] stroke-[1.5]" />
                                      </img-block>
                                  </div>
                              @endif
                              <div>
  
                              </div>
  
                          </div>
                      </div>
                  </div>
                  @php
                  $cardoembed = 'false';
                  @endphp
                  @foreach ($itemblocks->datafield as $key => $itemfields)
                      @if ($itemblocks->id == $itemfields->block_id && $itemfields->type == 'oembed')
                      @php
                      $cardoembed = 'true';
                      @endphp
                      <x-kompass::blocks key="{{ $key }}" type="{{ $itemfields->type }}"
                          name="{{ $itemfields->name }}" fields="{!! $itemblocks->datafield[$key]['data'] !!}"
                          idField="{{ $itemblocks->datafield[$key]['id'] }}" blockId="{{ $itemblocks->id }}">
                      </x-kompass::blocks>
                      @endif
                  @endforeach
                  @if ($cardoembed == 'false')
                  <div x-show="oEmbed">

                      <div class="flex">YouTube URL</div>
                      <form wire:submit="addoEmbed({{ $itemblocks->id }})">
                          <x-kompass::form.input wire:model.blur="oembedUrl" type="text" wire:dirty.class="border-yellow" />
               
                      </form>
                      {{-- <button class="btn"
                      wire:click="selectitem('addBlock',{{ $page->id }})">{{ __('Add') }}</button>
                          <x-kompass::form.input wire:model.blur="addoEmbed({{ $itemblocks->id }})" type="text" wire:dirty.class="border-yellow" />
               
                          <button wire:click="oembedURL({{ $itemblocks->id }})" class="btn btn-primary">{{ __('Save') }}</button> --}}
                  </div>
                  @endif
   
              </div>
  
          @break

          @case('gallery')
              <div class="@container">
                  <div class="grid @sm:grid-cols-1 @lg:grid-cols-3 @3xl:grid-cols-4  gap-6">
    
                      @foreach ($itemblocks->datafield as $key => $itemfields)
             
                      <x-kompass::block.image :itemfield="$itemfields" />

                      @endforeach

                      <img-block wire:click="selectitem('addMedia',0,'gallery',{{ $itemblocks->id }})"
                          class="cursor-pointer grid place-content-center border-2 border-dashed border-gray-400 rounded-2xl w-full text-gray-400 aspect-[4/3] ">
                          <x-tabler-photo-plus class="h-[4rem] w-[4rem] stroke-[1.5]" />
                      </img-block>
                  </div>
              </div>
          @break

          @case('wysiwyg')
              @foreach ($itemblocks->datafield as $key => $itemfields)
         
                      <div class="col-span-{{ $itemfields->grid }}" style="order: {{ $itemfields->order }} ">

                          @php
                              $jsfield = json_decode($itemblocks->datafield[$key]['data'], true);
                              $gridtables = $itemblocks->datafield[$key]['grid'];
                          @endphp

                          @livewire(
                              'editorjs',
                              [
                                  'editorId' => $itemblocks->datafield[$key]['id'],
                                  'value' => $jsfield,
                                  'uploadDisk' => 'publish',
                                  'downloadDisk' => 'publish',
                                  'class' => 'cdx-input',
                                  'style' => '',
                                  // 'readOnly' => true,
                                  'placeholder' => __('write something...'),
                              ],
                              key($itemblocks->datafield[$key]['id'])
                          )
                      </div>
       
              @endforeach
          @break

          @default

              @foreach ($itemblocks->datafield as $key => $itemfields)
              {{-- @livewire('datafield-item') --}}
              <livewire:datafield-item :datafield="$itemfields" :key="$itemfields->id" />
                
{{-- 

<div x-data="{ids: '{{ $itemfields->id }}', data: '{{ $itemfields->data }}'}" wire:model.lazy="fil">
<input type="text" x-model="data"  >

</div> --}}
{{-- <input type="button" value="" wire:key="itemfields-{{ $itemfields->id }}">{{ $itemfields}}> --}}
{{-- <x-kompass::form.input  wire:model="fields.{{ $itemfields->data }}.data" label="{{ $itemfields->name }}" type="text" /> --}}
{{-- 
                  @if ($itemblocks->id == $itemfields->block_id)
                      <div class="col-span-{{ $itemfields->grid }}" style="order: {{ $itemfields->order }} ">
                        {{ $itemblocks->datafield[$key]['data'] }}
                          <x-kompass::blocks key="{{ $key }}" 
                              type="{{ $itemfields->type }}"
                              name="{{ $itemfields->name }}" 
                              fields="{!! $itemblocks->datafield[$key]['data'] !!}"
                              idField="{{ $itemblocks->datafield[$key]['id'] }}" 
                              blockId="{{ $itemblocks->id }}">
                          </x-kompass::blocks>

                      </div>
                  @endif --}}
              @endforeach
      @endswitch


  </div>

</div>
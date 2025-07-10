@props([
    'itemblocks' => '',
    'fields' => '',
    'page' => '',
    'class' => '',
    'cssclassname' => '',
])
  <div>
    <nav class="">
      <x-kompass::nav-item :itemblocks="$itemblocks" />
    </nav>
  </div>
  <div class="grid grid-cols-{{ $itemblocks->grid }} gap-6">
    @switch($itemblocks->type)
      {{-- @case('video')
        <x-kompass::block.video :itemblocks="$itemblocks" />
      @break
      @case('download')
        <x-kompass::block.download :itemblocks="$itemblocks" />
        @break --}}
      @case('gallery')
        <x-kompass::block.gallery :itemblocks="$itemblocks" />
      @break
      {{-- @case('wysiwyg')
        <x-kompass::block.wysiwyg :itemblocks="$itemblocks" />
      @break
      @default
        <x-kompass::block.default :itemblocks="$itemblocks" /> --}}

    @endswitch



  
    @foreach ($itemblocks->datafield as $item)
      @switch($item['type'])
          @case('video')
              <x-kompass::block.video :itemfield="$item" />
          @break
          @case('download')
              <x-kompass::block.download :itemfield="$item" />
          @break
          @case('gallery')
              {{-- <x-kompass::block.gallery :itemfield="$item" /> --}}
          @break 
          @case('wysiwyg')
              <x-kompass::block.wysiwyg :itemfield="$item" />
          @break 
          @case('link')
              <x-kompass::block.link :itemfield="$item" />
          @break
          @case('file')
              <x-kompass::block.file :itemfield="$item" />
          @break
          @case('color')
              <x-kompass::block.color :itemfield="$item" />
          @break
          @default
       <livewire:datafield-item :datafield="$item" :key="$item->id" :class="'col-span-'. $item->grid " /> 
      <x-kompass::block.default :itemblocks="$item" /> 
      @endswitch
    @endforeach 

  </div>


{{-- @dump($itemblocks->toArray()) --}}

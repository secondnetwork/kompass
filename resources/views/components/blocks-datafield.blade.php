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
  <div class="grid gap-6">
    @switch($itemblocks->type)
      @case('video')
        <x-kompass::block.video :itemblocks="$itemblocks" />
      @break
      @case('download')
        <x-kompass::block.download :itemblocks="$itemblocks" />
        @break
      @case('gallery')
        <x-kompass::block.gallery :itemblocks="$itemblocks" />
      @break
      @case('wysiwyg')
        <x-kompass::block.wysiwyg :itemblocks="$itemblocks" />
      @break
      @default
        <x-kompass::block.default :itemblocks="$itemblocks" />

    @endswitch
  </div>



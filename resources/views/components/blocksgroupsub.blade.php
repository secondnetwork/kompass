
@foreach ($childrensub as $childitem )
  <x-kompass::blocksgroup :itemblocks="$childitem" :fields="$fields" :page="$page" class="itemblock shadow border border-gray-300 col-span-{{ $childitem->layoutgrid }}"/>
@endforeach

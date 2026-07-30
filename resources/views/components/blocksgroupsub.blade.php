
@foreach ($childrensub as $childitem )
  <x-kompass::blocksgroup :itemblocks="$childitem" :fields="$fields" :page="$page" :copy-to-page="$copyToPage" class="itemblock shadow-sm border border-base-300 rounded-md col-span-{{ $childitem->layoutgrid }}"/>
@endforeach

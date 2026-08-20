@props([
    'childrensub' => [],
    'fields' => null,
    'page' => null,
    'copyToPage' => false,
    'fullWidth' => false,
])

@foreach ($childrensub as $childitem )
  <x-kompass::blocksgroup :itemblocks="$childitem" :fields="$fields" :page="$page" :copy-to-page="$copyToPage" class="itemblock shadow-sm border border-base-300 rounded-md {{ $fullWidth ? 'col-span-full' : 'col-span-'.$childitem->layoutgrid }}"/>
@endforeach

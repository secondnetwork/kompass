
@foreach ($childrensub as $childitem )

<x-kompass::blocksgroup :itemblocks="$childitem" :fields="$fields" :page="$page" class="itemblock shadow border-r-4 border-purple-500"/>

@endforeach


@foreach ($childrensub as $childitem )

<x-kompass::blocksgroup :itemblocks="$childitem" :fields="$fields" :page="$page" class="itemblock shadow border-purple-500"/>

@endforeach

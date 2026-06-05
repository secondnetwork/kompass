@foreach ($childrensub as $childitem)
    <x-kompass::menugroup :item="$childitem" />
@endforeach

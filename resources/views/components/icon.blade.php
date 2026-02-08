@php
$iconPath = base_path('vendor/secondnetwork/blade-tabler-icons/resources/svg/'.$name.'.svg');
if (!file_exists($iconPath)) {
    $iconPath = dirname(base_path()).'/vendor/secondnetwork/blade-tabler-icons/resources/svg/'.$name.'.svg';
}
$class = $attributes->get('class', '');
$fallback = '<span class="'.$class.'">'.$name.'</span>';
@endphp

@if(file_exists($iconPath))
    {!! str_replace('<svg', '<svg class="'.$class.'"', file_get_contents($iconPath)) !!}
@else
    {!! $fallback !!}
@endif

@props(['itemblocks' => ''])

{{-- Container blocks share the same registry-driven settings offcanvas as
     modules; the per-type control list (from block_registry()->controls())
     selects the container-specific controls. --}}
<x-kompass::nav-item :itemblocks="$itemblocks" />

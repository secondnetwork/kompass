<div class="col-span-{{ $itemfield->grid }}">
    <div class="p-4 border rounded bg-white flex items-center gap-2">
        <span class="font-semibold">Farb-Block:</span>
        <span class="w-6 h-6 rounded" style="background: {{ $itemfield->data ?? '#eee' }};"></span>
        <span>{{ $itemfield->data ?? 'Keine Farbe gesetzt' }}</span>
    </div>
</div>

<div class="col-span-{{ $itemfield->grid }}">
    <div class="p-4 border rounded bg-blue-50">
        <span class="font-semibold">Link-Block:</span>
        <a href="{{ $itemfield->data ?? '#' }}" class="text-blue-600 underline" target="_blank">
            {{ $itemfield->data ?? 'Kein Link gesetzt' }}
        </a>
    </div>
</div>

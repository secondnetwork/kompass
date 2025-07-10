<div class="col-span-{{ $itemfield->grid }}">
    <div class="p-4 border rounded bg-gray-50">
        <span class="font-semibold">Datei-Block:</span>
        @if($itemfield->data)
            <a href="{{ $itemfield->data }}" class="text-blue-600 underline" download>
                Datei herunterladen
            </a>
        @else
            <span class="text-gray-400">Keine Datei hinterlegt</span>
        @endif
    </div>
</div>

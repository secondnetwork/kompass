<div
    x-data="editorInstance('data', '{{ $editorId }}', {{ $readOnly ? 'true' : 'false' }}, '{{ $placeholder }}', '{{ $logLevel }}')"
    x-init="initEditor()"
    class="{{ $class }}"
    style="{{ $style }}"
    wire:ignore 
>
    <div id="{{ $editorId }}"></div>
</div>



{{-- 
    <div class="filament-editorjs">
      <div 
          wire:ignore
          class="editorjs-wrapper"
          x-data="editorjs({ 
                state: $wire.entangle('{{ $getStatePath() }}').defer,
                statePath: '{{ $getStatePath() }}',
                placeholder: '{{ $getPlaceholder() }}',
                readOnly: {{ $isDisabled() ? 'true' : 'false' }},
                tools: @js($getTools()),
                minHeight: @js($getMinHeight())
            })"
       >
      </div>
    </div> --}}
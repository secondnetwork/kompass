@props([ 'name' ])

<input type="checkbox" id="modal_{{ $name }}" class="modal-toggle" />
<div role="dialog" class="modal">
    <div {{ $attributes->merge([
        'class' => 'modal-box'
        ]) }}
        {{ $attributes }}>
        {{ $slot }}
    </div>

    <label method="dialog" class="modal-backdrop" for="modal_{{ $name }}">
        <button>close</button>
    </label>
</div>

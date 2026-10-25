@props([
    'itemfield' => '',
])

{{-- Multi-line code editor. Stores the raw snippet (line breaks preserved)
     so a block can render it inside a <pre> without escaping surprises. --}}
<div class="w-full">

    <label for="">{{ $itemfield->name }}</label>
    <textarea
        rows="14"
        spellcheck="false"
        wire:blur="updateDatafield({{ $itemfield->id }}, $event.target.value)"
        class="textarea textarea-bordered w-full font-mono text-xs leading-relaxed focus:textarea-primary"
        placeholder="// Your code here"
    >{{ $itemfield->data }}</textarea>

</div>

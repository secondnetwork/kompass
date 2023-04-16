<button {{ $attributes->merge(['type' => 'button', 'class' => ' disabled:opacity-25 transition']) }}>
    {{ $slot }}
</button>

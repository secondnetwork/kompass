<button {{ $attributes->merge(['type' => 'submit', 'class' => 'disabled:opacity-25 transition']) }}>
    {{ $slot }}
</button>

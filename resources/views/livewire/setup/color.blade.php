<div class="max-w-xl">
    @php
        $colorFields = [
            'text_color' => 'Text Color',
            'button_color' => 'Button Color',
            'button_text_color' => 'Button Text Color',
            'input_text_color' => 'Input Text Color',
            'input_border_color' => 'Input Border Color',
        ];
    @endphp

    @foreach ($colorFields as $field => $label)
        <div @class(['pb-5', 'mb-5 border-b border-zinc-200' => ! $loop->last])>
            <div class="pb-3 w-full">
                <label class="block text-sm font-medium leading-6 text-gray-900">{{ __($label) }}</label>
            </div>
            <div class="w-full max-w-xs">
                <x-kompass::color-picker
                    :value="$this->{$field}"
                    @changed="$wire.set('{{ $field }}', $event.detail)"
                />
            </div>
        </div>
    @endforeach
</div>

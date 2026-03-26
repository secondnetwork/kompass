<div class=" grid grid-cols-1 items-center  h-auto gap-4">

    <x-kompass::form.switch wire:model.live="show_posts" label="{{ __('Show Posts') }}" />
    <x-kompass::form.switch wire:model.live="show_categories" label="{{ __('Show Categories') }}" />
    <x-kompass::form.switch wire:model.live="show_pages" label="{{ __('Show Pages') }}" />
    <x-kompass::form.switch wire:model.live="show_medialibrary" label="{{ __('Show Media Library') }}" />

</div>
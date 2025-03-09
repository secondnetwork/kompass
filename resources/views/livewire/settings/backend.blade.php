<div class="grid grid-cols-1 divide-y items-center w-full h-auto   gap-4 py-8">
  <h3>Login {{ __('Page') }}</h3>
  <x-kompass::form.switch wire:model.live="registration_can_user" label="{{ __('User can register') }}"  />



</div>
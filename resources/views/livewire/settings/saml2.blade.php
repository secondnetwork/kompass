<div class="grid gap-6">
    @unless ($saml2Installed)
        <div class="rounded-lg border border-warning/40 bg-warning/10 p-4 text-sm">
            <div class="flex items-start gap-3">
                <x-tabler-alert-triangle class="size-5 text-warning shrink-0 mt-0.5" />
                <div>
                    <p class="font-semibold">{{ __('SAML2 package not installed') }}</p>
                    <p class="text-base-content/70 mt-1">
                        {{ __('Install scaler-tech/laravel-saml2 to manage identity providers here.') }}
                    </p>
                    <code class="mt-2 inline-block rounded bg-base-300 px-2 py-1 text-xs">composer require scaler-tech/laravel-saml2</code>
                </div>
            </div>
        </div>
    @else
        {{-- Tenant list --}}
        <div class="flex items-center justify-between">
            <h4 class="font-bold">{{ __('Identity Providers') }}</h4>
            <button wire:click="create" class="btn btn-primary btn-sm">
                <x-tabler-plus class="size-4" /> {{ __('Add Tenant') }}
            </button>
        </div>

        @if (empty($tenants))
            <div class="rounded-lg border border-dashed border-base-300 p-8 text-center text-base-content/60">
                <x-tabler-shield-lock class="size-8 mx-auto mb-2 opacity-50" />
                <p class="text-sm">{{ __('No identity providers configured yet.') }}</p>
            </div>
        @endif

        @if (! empty($tenants))
            <div class="overflow-hidden rounded-lg border border-base-300">
                <table class="table">
                    <thead>
                        <tr class="bg-base-200">
                            <th>{{ __('Key') }}</th>
                            <th>{{ __('Entity ID') }}</th>
                            <th>{{ __('Login URL') }}</th>
                            <th class="text-right">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tenants as $tenant)
                            <tr wire:key="saml2-tenant-{{ $tenant['id'] }}">
                                <td class="font-mono text-xs">{{ $tenant['key'] }}</td>
                                <td class="max-w-[16rem] truncate text-sm">{{ $tenant['idp_entity_id'] }}</td>
                                <td class="max-w-[16rem] truncate text-sm text-base-content/70">{{ $tenant['idp_login_url'] }}</td>
                                <td class="text-right whitespace-nowrap">
                                    <button wire:click="edit({{ $tenant['id'] }})" class="btn btn-ghost btn-xs" title="{{ __('Edit') }}">
                                        <x-tabler-edit class="size-4" />
                                    </button>
                                    <button wire:click="delete({{ $tenant['id'] }})"
                                            wire:confirm="{{ __('Are you sure you want to delete this tenant?') }}"
                                            class="btn btn-ghost btn-xs text-error" title="{{ __('Delete') }}">
                                        <x-tabler-trash class="size-4" />
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        {{-- Login-page button configuration --}}
        <div class="rounded-lg border border-base-300 p-6">
            <h4 class="font-bold mb-1">{{ __('Login Button') }}</h4>
            <p class="text-sm text-base-content/60 mb-4">{{ __('Show a single sign-on button on the login page.') }}</p>

            <form wire:submit="saveLoginButton" class="space-y-5">
                <x-kompass::form.switch wire:model.live="ssoEnabled" label="{{ __('Show SSO button on login page') }}" />

                @if ($ssoEnabled)
                    <div class="grid gap-5 md:grid-cols-2">
                        <div>
                            <label for="ssoTenantUuid" class="block text-sm font-medium mb-1">{{ __('SSO Tenant (URL)') }}</label>
                            <select wire:model="ssoTenantUuid" id="ssoTenantUuid" class="select select-bordered w-full">
                                <option value="">{{ __('Select tenant...') }}</option>
                                @foreach ($tenants as $tenant)
                                    <option value="{{ $tenant['uuid'] }}">{{ $tenant['key'] }} — {{ $tenant['uuid'] }}</option>
                                @endforeach
                            </select>
                            <p class="text-xs text-base-content/50 mt-1">{{ __('The button links to /saml2/{uuid}/login.') }}</p>
                        </div>
                        <div>
                            <x-kompass::form.input wire:model="ssoLabel" :label="__('Button Label')" name="ssoLabel" placeholder="{{ __('Single Sign-On') }}" />
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">{{ __('Button Icon') }}</label>
                        <div class="flex items-center gap-2">
                            <button type="button" wire:click="openIconPicker" class="btn btn-outline flex items-center gap-2">
                                @if ($ssoIcon)
                                    @svg($ssoIcon, 'h-4 w-4')
                                @else
                                    <x-tabler-icons class="h-4 w-4" />
                                @endif
                                {{ __('Choose icon') }}
                            </button>
                            @if ($ssoIcon)
                                <span class="text-xs">{{ $ssoIcon }}</span>
                                <button type="button" wire:click="clearIcon" class="btn btn-ghost btn-xs">
                                    <x-tabler-x class="h-3 w-3" />
                                </button>
                            @endif
                        </div>
                    </div>

                    <div class="rounded-lg border border-base-300 bg-base-200 px-4 py-3">
                        <span class="text-xs text-base-content/60 block mb-2">{{ __('Preview') }}</span>
                        <div class="btn flex justify-center items-center gap-2 w-full h-14 border-1 border-base-300 bg-base-100 pointer-events-none">
                            @if ($ssoIcon)
                                @svg($ssoIcon, 'h-6 w-6')
                            @endif
                            <span class="font-medium">{{ $ssoLabel ?: __('Single Sign-On') }}</span>
                        </div>
                    </div>
                @endif

                <div class="flex items-center gap-3">
                    <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                    <x-kompass::action-message class="ms-1" on="saml2-button-saved">{{ __('Saved.') }}</x-kompass::action-message>
                </div>
            </form>
        </div>

        {{-- Create / edit form (slide-out panel) --}}
        <div x-cloak x-data="{ open: @entangle('showForm') }">
            <x-kompass::offcanvas :w="'w-2/5'">
                <x-slot name="button">
                    <h4 class="font-bold text-lg">
                        {{ $editingId ? __('Edit Tenant') : __('New Tenant') }}
                    </h4>
                </x-slot>

                <x-slot name="body">
                    <form wire:submit="save" class="space-y-5">
                        {{-- Import IdP metadata --}}
                        <div x-data="{ metaOpen: false }" class="rounded-lg border border-base-300 bg-base-200/40 p-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <x-tabler-file-download class="size-5 text-base-content/60" />
                                    <span class="text-sm font-semibold">{{ __('Import from IdP metadata') }}</span>
                                </div>
                                <button type="button" @click="metaOpen = !metaOpen" class="btn btn-ghost btn-xs">
                                    <span x-show="!metaOpen">{{ __('Show') }}</span>
                                    <span x-show="metaOpen" x-cloak>{{ __('Hide') }}</span>
                                </button>
                            </div>

                            <div x-show="metaOpen" x-cloak class="mt-4 space-y-3">
                                <div class="flex gap-2 items-end">
                                    <div class="flex-1">
                                        <x-kompass::form.input wire:model="metadataUrl" :label="__('Metadata URL')" name="metadataUrl" placeholder="https://idp.example.com/metadata" />
                                    </div>
                                    <button type="button" wire:click="importMetadata" class="btn btn-primary">
                                        <x-tabler-download class="size-4" />
                                        <span wire:loading.remove wire:target="importMetadata">{{ __('Import') }}</span>
                                        <span wire:loading wire:target="importMetadata">{{ __('Importing...') }}</span>
                                    </button>
                                </div>

                                <div class="text-center text-xs text-base-content/50">{{ __('or upload an XML file') }}</div>

                                <div>
                                    <input type="file" wire:model="metadataFile" accept=".xml,text/xml,application/xml"
                                        class="file-input file-input-bordered w-full" />
                                    <div wire:loading wire:target="metadataFile" class="text-xs text-primary mt-1">{{ __('Uploading...') }}</div>
                                    @error('metadataFile') <span class="text-error text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div class="text-center text-xs text-base-content/50">{{ __('or paste the XML') }}</div>

                                <x-kompass::form.textarea wire:model="metadataXml" name="metadataXml" rows="4" class="font-mono text-xs"
                                    placeholder="&lt;EntityDescriptor ...&gt;" />

                                @error('metadataUrl') <span class="text-error text-xs block">{{ $message }}</span> @enderror

                                <x-kompass::action-message class="text-success text-xs" on="saml2-metadata-imported">
                                    {{ __('Metadata imported — review the fields below and save.') }}
                                </x-kompass::action-message>
                            </div>
                        </div>

                        <div class="grid gap-5 md:grid-cols-2">
                            <div>
                                <x-kompass::form.input wire:model="key" :label="__('Key')" name="key" placeholder="my-idp" />
                                @error('key') <span class="text-error text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="name_id_format" class="block text-sm font-medium mb-1">{{ __('Name ID Format') }}</label>
                                <select wire:model="name_id_format" id="name_id_format" class="select select-bordered w-full">
                                    @foreach ($nameIdFormats as $format)
                                        <option value="{{ $format['value'] }}">{{ $format['label'] }}</option>
                                    @endforeach
                                </select>
                                @error('name_id_format') <span class="text-error text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div>
                            <x-kompass::form.input wire:model="idp_entity_id" :label="__('IdP Entity ID')" name="idp_entity_id" placeholder="https://idp.example.com/metadata" />
                            @error('idp_entity_id') <span class="text-error text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid gap-5 md:grid-cols-2">
                            <div>
                                <x-kompass::form.input wire:model="idp_login_url" :label="__('IdP Login URL')" name="idp_login_url" placeholder="https://idp.example.com/sso" />
                                @error('idp_login_url') <span class="text-error text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <x-kompass::form.input wire:model="idp_logout_url" :label="__('IdP Logout URL')" name="idp_logout_url" placeholder="https://idp.example.com/slo" />
                                @error('idp_logout_url') <span class="text-error text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div>
                            <x-kompass::form.input wire:model="relay_state_url" :label="__('Relay State URL')" name="relay_state_url" placeholder="{{ url('/') }}" />
                            @error('relay_state_url') <span class="text-error text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <x-kompass::form.textarea wire:model="idp_x509_cert" :label="__('IdP x509 Certificate')" name="idp_x509_cert" rows="6" class="font-mono text-xs" placeholder="-----BEGIN CERTIFICATE-----" />
                            @error('idp_x509_cert') <span class="text-error text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex items-center gap-3">
                            <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                            <button type="button" wire:click="resetForm" class="btn btn-ghost">{{ __('Cancel') }}</button>
                            <x-kompass::action-message class="ms-1" on="saml2-saved">{{ __('Saved.') }}</x-kompass::action-message>
                        </div>
                    </form>
                </x-slot>
            </x-kompass::offcanvas>
        </div>

        <x-kompass::icon-picker />
    @endunless
</div>

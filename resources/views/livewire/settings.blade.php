<div class="h-full" x-data="{ asidenav: @entangle('asidenav'), tab: @entangle('tab') }">

    <grid-side class="flex gap-3" x-data="{}">

        <aside class="w-56 shrink-0 pr-4 border-r border-base-300">

            <nav class="flex flex-col gap-0.5 sticky top-4" aria-label="asidenav">
                @foreach ($navigation as $tab)
                    @php $tab = (object) $tab; @endphp
                    @if ($tab->slug == '')
                        <div class="uppercase text-[11px] tracking-wider mt-5 mb-1 px-3 text-base-content/50 font-semibold">
                            {{ $tab->name }}</div>
                    @else
                        <a href="#_" @click.prevent="asidenav = '{{ $tab->slug }}'"
                            :class="asidenav == '{{ $tab->slug }}'
                                ? 'bg-brand-500/20 text-brand-600 font-bold'
                                : 'text-base-content/70 hover:bg-base-200 hover:text-base-content'"
                            class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm transition-colors">
                            @svg($tab->icon ?? 'tabler-x', 'size-5 shrink-0')
                            <span class="truncate">{{ $tab->name }}</span></a>
                    @endif
                @endforeach
            </nav>

        </aside>

        <div class="flex flex-col w-full">

            <item-setting class="align-middle inline-block min-w-full">
                <div x-show="asidenav === 'page_information'" x-cloak class="bg-base-100 rounded-xl border border-base-300 p-6">

                    <div>
                        <h6 class="font-semibold text-lg">{{ __('Page Information') }}</h6>
                        <p class="text-xs opacity-60">
                            {{ __('Manage your website\'s basic information and SEO metadata.') }}</p>
                    </div>
                    <hr class="h-px w-full border-none bg-base-300 my-4">
                    <livewire:settings.page-information />
                </div>
                <div x-show="asidenav === 'page_appearance'" x-cloak class="bg-base-100 rounded-xl border border-base-300 p-6">

                    <div>
                        <h6 class="font-semibold text-lg">{{ __('Page Appearance') }}</h6>
                        <p class="text-xs opacity-60">{{ __('Change how your dashboard looks and feels.') }}</p>
                    </div>
                    <hr class="h-px w-full border-none bg-base-300 my-4">
                    <div class="border-b border-base-300">
                        @php
                            $tabs = [
                                'logo' => 'Logo',
                                'favicon' => 'Favicon',
                            ];
                        @endphp
                        <nav class="flex -mb-px space-x-8" aria-label="Tabs">
                            @foreach ($tabs as $slug => $tab)
                                <a href="#_" @click.prevent="tab = '{{ $slug }}'"
                                    :class="{
                                        'border-indigo-500 text-indigo-600': tab ==
                                            '{{ $slug }}',
                                        'border-transparent text-base-content/70 hover:border-gray-300 hover:text-gray-700': tab !=
                                            '{{ $slug }}'
                                    }"
                                    class="px-1 py-4 text-sm font-medium whitespace-nowrap border-b-2">{{ $tab }}</a>
                            @endforeach
                            <!-- Current: "border-indigo-500 text-indigo-600", Default: "border-transparent text-base-content/70 hover:border-gray-300 hover:text-gray-700" -->
                        </nav>
                    </div>

                    <div class="grid gap-y-4 py-8">

                        <div x-show="tab == 'logo'" class="w-full h-auto" x-cloak>
                            <livewire:setup.logo lazy />

                        </div>
                        
                        
                        
                        <div x-show="tab == 'favicon'" class="w-full h-auto" x-cloak>
                            <livewire:setup.favicon lazy />
                        </div>
                               

                    </div>

                </div>
                <div x-show="asidenav === 'multilingual'" x-cloak class="bg-base-100 rounded-xl border border-base-300 p-6">

                    <div>
                        <h6 class="font-semibold text-lg">{{ __('Multilingual') }}</h6>
                        <p class="text-xs opacity-60">{{ __('Manage multiple languages for your website.') }}</p>
                    </div>
                    <hr class="h-px w-full border-none bg-base-300 my-4">
                    <livewire:settings.multilingual />
                </div>
                <div x-show="asidenav === 'backend'" x-cloak class="bg-base-100 rounded-xl border border-base-300 p-6">
                    <div>
                        <h6 class="font-semibold text-lg">Login {{ __('Page') }}</h6>
                        <p class="text-xs opacity-60">
                            {{ __('Customize the background of your authentication pages.') }}</p>
                    </div>
                    <hr class="h-px w-full border-none bg-base-300 my-4">
                    <livewire:settings.backend lazy />
                    <hr class="h-px w-full border-none bg-base-300 my-4">
                    <livewire:setup.background lazy />

                </div>
                <div x-show="asidenav === 'backendmenu'" x-cloak class="bg-base-100 rounded-xl border border-base-300 p-6">
                    <div>
                        <h6 class="font-semibold text-lg">{{ __('Menu Visibility') }}</h6>
                        <p class="text-xs opacity-60">{{ __('Toggle visibility of menu items in the admin sidebar') }}</p>
                    </div>
                    <hr class="h-px w-full border-none bg-base-300 my-4">
                    <livewire:settings.backendmenu lazy />
                </div>
                {{-- <div x-show="asidenav === 'admin_panel'" x-cloak>

                    <div>
                        <h6 class="font-semibold text-lg">{{ __('Admin Panel') }}</h6>
                        <p class="text-xs opacity-60">
                            {{ __('Settings specifically for the administration interface.') }}</p>
                    </div>
                    <livewire:settings.admin-panel lazy />
                </div> --}}
                <div x-show="asidenav === 'global'" x-cloak class="bg-base-100 rounded-xl border border-base-300 p-6">

                    <div class="border-base-300 whitespace-nowrap flex gap-8 justify-between items-center mb-4">

                        <div>
                            <h6 class="font-semibold text-lg">{{ __('Global Settings') }}</h6>
                            <p class="text-xs opacity-60">
                                {{ __('Key-value pairs for general application configuration.') }}</p>
                        </div>

                        <button wire:click="selectItem('', 'add')" class="btn btn-primary" @click="open = true">
                            <x-tabler-settings-plus stroke-width="1.5" />{{ __('New Setting') }}
                        </button>
                    </div>

                    <x-kompass::elements.global :settings="$settings" :type="$type" :getId="$getId" :selectedItem="$selectedItem"
                        :name="$name" :key="$key" :group="$group" :valuedata="$valuedata" />

                </div>

                @if(class_exists(\Spatie\Activitylog\Models\Activity::class))
                <div x-show="asidenav === 'activity-log'" x-cloak class="bg-base-100 rounded-xl border border-base-300 overflow-hidden">

                    <div class="p-5 border-b border-base-300">
                        <h6 class="font-semibold text-lg">{{ __('Activity Log') }}</h6>
                        <p class="text-xs opacity-60">{{ __('Monitor all changes and actions within the system.') }}
                        </p>
                    </div>

                    <livewire:settings.activity-log lazy />

                </div>
                @endif

                <div x-show="asidenav === 'error-log'" x-cloak class="bg-base-100 rounded-xl border border-base-300 overflow-hidden">

                    <div class="p-5 border-b border-base-300">
                        <h6 class="font-semibold text-lg">{{ __('Error Log') }}</h6>
                        <p class="text-xs opacity-60">{{ __('Review 404 errors and other system exceptions.') }}</p>
                    </div>
                    <livewire:settings.error-log lazy />

                </div>

                @if (\Secondnetwork\Kompass\Features::hasSaml2())
                @role(['super_admin', 'admin'])
                <div x-show="asidenav === 'saml2'" x-cloak class="bg-base-100 rounded-xl border border-base-300 overflow-hidden">

                    <div class="p-5 border-b border-base-300">
                        <h6 class="font-semibold text-lg">{{ __('SAML2 SSO') }}</h6>
                        <p class="text-xs opacity-60">{{ __('Configure SAML2 identity providers for single sign-on.') }}</p>
                    </div>
                    <div class="p-5">
                        <livewire:settings.saml2 lazy />
                    </div>

                </div>
                @endrole
                @endif

                <div x-show="asidenav === 'redirection'" x-cloak class="bg-base-100 rounded-xl border border-base-300 overflow-hidden">

                    <div class="p-5 border-b border-base-300">
                        <h6 class="font-semibold text-lg">{{ __('Redirects') }}</h6>
                        <p class="text-xs opacity-60">{{ __('Create and manage URL redirects.') }}</p>
                    </div>
                    <livewire:redirection lazy />

                </div>

                <div x-show="asidenav === 'backup'" x-cloak>

                </div>
            </item-setting>

        </div>
    </grid-side>

</div>
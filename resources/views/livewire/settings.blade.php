<div class="h-full" x-data="{ asidenav: @entangle('asidenav'), tab: @entangle('tab') }">

    <grid-side class="grid grid-cols-11 h-full gap-6" x-data="{}">

        <aside class="col-start-1 col-end-3  border-r border-gray-200 h-full ">

            <nav class="flex flex-col" aria-label="asidenav">
                @foreach ($navigation as $tab)
                    @php $tab = (object) $tab; @endphp
                    @if ($tab->slug == '')
                        <div class="uppercase text-xs mt-5 mb-1 text-base-content/70 font-semibold">
                            {{ $tab->name }}</div>
                    @else
                        <a href="#_" @click.prevent="asidenav = '{{ $tab->slug }}'"
                            :class="{
                                'border-indigo-500 text-indigo-600': asidenav ==
                                    '{{ $tab->slug }}',
                                'text-base-content/70 hover:border-gray-300 hover:text-gray-700': asidenav !=
                                    '{{ $tab->slug }}'
                            }"
                            class="py-1 text-sm font-medium flex items-center gap-2">@svg($tab->icon ?? 'tabler-x')
                            {{ $tab->name }}</a>
                    @endif
                @endforeach
                <!-- Current: "border-indigo-500 text-indigo-600", Default: "border-transparent text-base-content/70 hover:border-gray-300 hover:text-gray-700" -->
            </nav>

        </aside>

        <div class="flex flex-col col-start-3 col-end-12">

            <item-setting class="align-middle inline-block min-w-full">
                <div x-show="asidenav === 'page_information'" x-cloak>

                    <div class="my-3">
                        <h3 class="text-2xl font-bold">{{ __('Page Information') }}</h3>
                        <p class="text-base-content/60 text-sm">
                            {{ __('Manage your website\'s basic information and SEO metadata.') }}</p>
                    </div>
                    <hr class="h-px w-full border-none bg-base-300 my-4">
                    <livewire:settings.page-information />
                </div>
                <div x-show="asidenav === 'page_appearance'" >

                    <div class="my-3">
                        <h3 class="text-2xl font-bold">{{ __('Page Appearance') }}</h3>
                        <p class="text-base-content/60 text-sm">{{ __('Change how your dashboard looks and feels.') }}</p>
                    </div>
                    <hr class="h-px w-full border-none bg-base-300 my-4">
                    <div class="border-b border-gray-200">
                        @php
                            $tabs = [
                                'logo' => 'Logo',
                                'favicon' => 'Favicon',
                                'css' => 'css',
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
                        <div x-show="tab == 'css'" class="w-full h-auto" x-cloak>
                            <livewire:setup.css lazy />
                        </div>

                        

                    </div>

                </div>
                <div x-show="asidenav === 'backend'" x-cloak>
                    <div class="my-3">
                        <h3 class="text-lg font-bold">Login {{ __('Page') }}</h3>
                        <p class="text-sm text-base-content/60 text-sm">
                            {{ __('Customize the background of your authentication pages.') }}</p>
                    </div>
                    <hr class="h-px w-full border-none bg-base-300 my-4">
                    <livewire:settings.backend lazy />

                    <livewire:setup.background lazy />

                </div>
                <div x-show="asidenav === 'admin_panel'" x-cloak>

                    <div class="my-3">
                        <h3 class="text-2xl font-bold">{{ __('Admin Panel') }}</h3>
                        <p class="text-base-content/60 text-sm">
                            {{ __('Settings specifically for the administration interface.') }}</p>
                    </div>
                    <livewire:settings.admin-panel lazy />
                </div>
                <div x-show="asidenav === 'global'" x-cloak>

                    <div class="border-gray-200 whitespace-nowrap flex gap-8 justify-between items-center">

                        <div class="my-3">
                            <h3 class="text-2xl font-bold">{{ __('Global Settings') }}</h3>
                            <p class="text-base-content/60 text-sm">
                                {{ __('Key-value pairs for general application configuration.') }}</p>
                        </div>

                        <button wire:click="selectItem('', 'add')" class="btn btn-primary" @click="open = true">
                            <x-tabler-settings-plus stroke-width="1.5" />{{ __('New Setting') }}
                        </button>
                    </div>

                    <x-kompass::elements.global :settings="$settings" :type="$type" :getId="$getId" :selectedItem="$selectedItem"
                        :name="$name" :key="$key" :group="$group" :valuedata="$valuedata" />

                </div>

                <div x-show="asidenav === 'activity-log'" x-cloak>

                    <div class="my-3">
                        <h3 class="text-2xl font-bold">{{ __('Activity Log') }}</h3>
                        <p class="text-base-content/60 text-sm">{{ __('Monitor all changes and actions within the system.') }}
                        </p>
                    </div>

                    <livewire:settings.activity-log lazy />

                </div>

                <div x-show="asidenav === 'error-log'" x-cloak>

                    <div class="my-3">
                        <h3 class="text-2xl font-bold">{{ __('Error Log') }}</h3>
                        <p class="text-base-content/60 text-sm">{{ __('Review 404 errors and other system exceptions.') }}</p>
                    </div>
                    <livewire:settings.error-log lazy />

                </div>

                <div x-show="asidenav === 'redirection'" x-cloak>
                    {{-- <livewire:redirect lazy />
                    <livewire:brokenlink lazy /> --}}
                </div>

                <div x-show="asidenav === 'backup'" x-cloak>

                </div>
            </item-setting>

        </div>
    </grid-side>

</div>
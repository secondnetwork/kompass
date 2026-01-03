<div class="h-full" x-data="{
    'asidenav': new URLSearchParams(window.location.search).get('asidenav') || 'page_information',
    'tab': new URLSearchParams(window.location.search).get('tab') || 'logo',
    addQueryParam(key, value) {
        // Create a URL object based on the current document URL
        let url = new URL(window.location.href);

        // Set or replace the query parameter
        url.searchParams.set(key, value);

        // Update the URL in the address bar without reloading the page
        window.history.pushState({ path: url.toString() }, '', url.toString());
    }
}" x-init="$watch('asidenav', function(value) {
    if (value !== null) {
        addQueryParam('asidenav', value);
    }

    $watch('tab', function(value) {
        if (value !== null) {
            addQueryParam('tab', value);
        }
        {{-- if(tab == 'css'){
        if(codemirrorEditor == null){
            setTimeout(function(){
                enableCodeMirror();
            }, 100);
        }
        //enableCodeMirror();
    } --}}

    });

    {{-- if(tab == 'css'){
        if(codemirrorEditor == null){
            setTimeout(function(){
                enableCodeMirror();
            }, 100);
        }
        //enableCodeMirror();
    } --}}

});
{{-- if(tab == 'css'){
    console.log('accessed');
    setTimeout(function(){
        enableCodeMirror();
    }, 100);
} --}}">


    @php
        $data = [
            [
                'slug' => '',
                'name' => 'Theme ' . __('Settings'),
            ],
            [
                'slug' => 'page_information',
                'name' => __('Page Information'),
                'icon' => 'tabler-info-circle',
            ],
            [
                'slug' => 'page_appearance',
                'name' => __('Page Appearance'),
                'icon' => 'tabler-palette',
            ],
            [
                'slug' => '',
                'name' => __('Settings'),
            ],
            [
                'slug' => 'backend',
                'name' => 'Login ' . __('Page'),
                'icon' => 'tabler-login',
            ],
            [
                'slug' => 'admin_panel',
                'name' => __('Admin Panel'),
                'icon' => 'tabler-layout-dashboard',
            ],
            [
                'slug' => 'global',
                'name' => __('Global Settings'),
                'icon' => 'tabler-world',
            ],
            [
                'slug' => '',
                'name' => __('Tools'),
            ],
            // [
            //     'slug' => 'redirection',
            //     'name' => __('Redirection'),
            // ],
            // [
            //     'slug' => 'backup',
            //     'name' => __('Backup'),
            // ],
            [
                'slug' => 'activity-log',
                'name' => __('Activity-log'),
                'icon' => 'tabler-activity',
            ],
            [
                'slug' => 'error-log',
                'name' => __('Error-log'),
                'icon' => 'tabler-alert-triangle',
            ],
        ];

        $collection = collect($data)->map(function ($item) {
            return (object) $item;
        });

    @endphp

    <grid-side class="grid grid-cols-11 h-full gap-6" x-data="{}">

        <aside class="col-start-1 col-end-3  border-r border-gray-200 h-full ">


            <nav class="flex flex-col" aria-label="asidenav">
                @foreach ($collection as $tab)
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

                    <div>
                        <h3 class="text-2xl font-bold">{{ __('Page Information') }}</h3>
                        <p class="text-base-content/60">
                            {{ __('Manage your website\'s basic information and SEO metadata.') }}</p>
                    </div>
                    <livewire:settings.page-information />
                </div>
                <div x-show="asidenav === 'backend'" x-cloak>
                    <div>
                        <h3 class="text-lg font-bold mb-1">Login {{ __('Page') }}</h3>
                        <p class="text-sm text-base-content/60">
                            {{ __('Customize the background of your authentication pages.') }}</p>
                    </div>
                    <livewire:settings.backend lazy />

                    <livewire:setup.background lazy />

                </div>
                <div x-show="asidenav === 'admin_panel'" x-cloak>

                    <div>
                        <h2 class="text-2xl font-bold">{{ __('Admin Panel') }}</h2>
                        <p class="text-base-content/60">
                            {{ __('Settings specifically for the administration interface.') }}</p>
                    </div>
                    <livewire:settings.admin-panel lazy />
                </div>
                <div x-show="asidenav === 'page_appearance'" class="py-6">

                    <div>
                        <h2 class="text-2xl font-bold">{{ __('Page Appearance') }}</h2>
                        <p class="text-base-content/60">{{ __('Change how your dashboard looks and feels.') }}</p>
                    </div>

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
                        <div x-show="tab == 'background'" class="w-full h-auto" x-cloak>

                        </div>
                        {{-- <div x-show="tab == 'color'" class="w-full h-auto" x-cloak>
                                <livewire:setup.color />
                         </div> --}}
                        {{-- <div x-show="tab == 'alignment'" class="w-full h-auto" x-cloak>
                                <livewire:setup.alignment />
                            </div> --}}
                        <div x-show="tab == 'favicon'" class="w-full h-auto" x-cloak>
                            <livewire:setup.favicon lazy />
                        </div>
                        <div x-show="tab == 'css'" class="w-full h-auto" x-cloak>
                            <livewire:setup.css lazy />
                        </div>

                        <div x-show="tab == 'meta'" class="w-full h-auto grid gap-4 max-w-xl" x-cloak>




                        </div>


                    </div>





                </div>
                <div x-show="asidenav === 'redirection'" x-cloak>
                    {{-- <livewire:redirect lazy />
                    <livewire:brokenlink lazy /> --}}
                </div>

                <div x-show="asidenav === 'backup'" x-cloak>


                </div>
                <div x-show="asidenav === 'activity-log'" x-cloak>

                    <div class="mb-6">
                        <h2 class="text-2xl font-bold">{{ __('Activity Log') }}</h2>
                        <p class="text-base-content/60">{{ __('Monitor all changes and actions within the system.') }}
                        </p>
                    </div>

                    <livewire:settings.activity-log lazy />

                </div>

                <div x-show="asidenav === 'error-log'" x-cloak>

                    <div class="mb-6">
                        <h2 class="text-2xl font-bold">{{ __('Error Log') }}</h2>
                        <p class="text-base-content/60">{{ __('Review 404 errors and other system exceptions.') }}</p>
                    </div>
                    <livewire:settings.error-log lazy />

                </div>

                <div x-show="asidenav === 'global'" x-cloak>

                    <div
                        class=" border-gray-200 py-4 whitespace-nowrap text-sm flex gap-8 justify-between items-center">

                        <div>
                            <h3 class="text-2xl font-bold">{{ __('Global Settings') }}</h3>
                            <p class="text-base-content/60">
                                {{ __('Key-value pairs for general application configuration.') }}</p>
                        </div>


                        <button wire:click="selectItem('', 'add')" class="btn btn-primary" @click="open = true">
                            <x-tabler-settings-plus stroke-width="1.5" />{{ __('New Setting') }}
                        </button>
                    </div>

                    <x-kompass::elements.global :settings="$settings" :type="$type" :getId="$getId" :selectedItem="$selectedItem"
                        :name="$name" :key="$key" :group="$group" :valuedata="$valuedata" />


                </div>


            </item-setting>



        </div>
    </grid-side>


</div>

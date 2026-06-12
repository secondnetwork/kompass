<div>
    <div class="py-4" >
        <div class="rounded-xl items-center shadow bg-center col-span-3"
            style="background-image: url({{ kompass_asset('kompass_bg.png') }})">

            @env('local')
                <div
                    class="flex items-center p-2 px-6 rounded-t-xl font-bold gap-1 bg-warning text-warning-content w-full text-center text-xs">
                    Developer Mode
                </div>
            @endenv
            <div class="p-6">
                <div class="text-gray-400">
                    @php
                        $h = date('G');
                    @endphp

                    @if ($h >= 0 && $h <= 11)
                        {{ __('Good morning') }}
                    @elseif ($h >= 12 && $h <= 16)
                        {{ __('Good afternoon') }}
                    @else
                        {{ __('Good evening') }}
                    @endif
                </div>
                <h3 class="text-white text-2xl font-bold">{{ auth()->user()->name }}</h3>
                <div class="text-gray-400">{{ now()->isoFormat('dddd, D. MMMM YYYY') }}</div>
            </div>
        </div>
    </div>
        <!-- Grid -->
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
            <!-- Pages Card -->
            @if (setting('global.show_pages', true))
            <x-kompass::dashboard.cart
                :title="__('Pages')"
                :value="$pagesCount"
                :footerLabel="__('Published')"
                :footerValue="$publishedPagesCount"
                :link="route('admin.pages')"
                icon="tabler-file-text"
                color="primary"
            />
            @endif

            <!-- Posts Card -->
            @if (setting('global.show_posts', true))
            <x-kompass::dashboard.cart
                :title="__('Posts')"
                :value="$postsCount"
                :footerLabel="__('Pub. / Draft')"
                :footerValue="$publishedPostsCount . ' / ' . $draftPostsCount"
                :trend="$postTrend"
                :trendUp="$postTrend >= 0"
                :link="route('admin.posts')"
                icon="tabler-news"
                color="success"
            />
            @endif

            <!-- Media Card -->
            @if (setting('global.show_medialibrary', true))
            <x-kompass::dashboard.cart
                :title="__('Media')"
                :value="$mediaCount"
                :link="route('admin.medialibrary')"
                icon="tabler-photo-video"
                color="info"
            />
            @endif
        </div>
        <!-- End Grid -->

        @if ($showDocsCard)
        <div class="mt-8 grid grid-cols-1 gap-8">
            {{-- Documentation Block --}}
            <div class="group bg-gradient-to-br from-base-100 to-orange-50/40 rounded-2xl border border-base-200 shadow-sm hover:shadow-md transition-shadow overflow-hidden flex flex-col md:flex-row items-center relative">
                {{-- Dismiss (toggles the global "Show documentation card" setting) --}}
                <button type="button"
                    wire:click="hideDocsCard"
                    title="{{ __('Hide') }}"
                    class="absolute top-3 right-3 z-30 p-1.5 rounded-full text-base-content/40 hover:text-base-content hover:bg-base-200 transition-colors">
                    <x-tabler-x class="w-4 h-4" />
                </button>

                <div class="w-full md:w-1/3 p-6 shrink-0">
                    {{-- Branded documentation illustration --}}
                    <div class="relative aspect-video rounded-xl overflow-hidden bg-gradient-to-br from-orange-500 to-amber-500 shadow-lg flex items-center justify-center">
                        {{-- dotted texture --}}
                        <div class="absolute inset-0 opacity-20"
                            style="background-image: radial-gradient(circle, #fff 1px, transparent 1.5px); background-size: 14px 14px;"></div>
                        {{-- floating doc card --}}
                        <div class="absolute -bottom-3 -right-2 w-16 h-20 bg-base-100 rounded-lg shadow-xl rotate-6 p-2 space-y-1.5 transition-transform duration-300 group-hover:rotate-3 group-hover:-translate-y-1">
                            <div class="w-2/3 h-1.5 bg-orange-400/70 rounded"></div>
                            <div class="w-full h-1 bg-base-300 rounded"></div>
                            <div class="w-full h-1 bg-base-300 rounded"></div>
                            <div class="w-4/5 h-1 bg-base-300 rounded"></div>
                        </div>
                        {{-- main icon --}}
                        <x-tabler-book class="w-14 h-14 text-white drop-shadow relative z-10 transition-transform duration-300 group-hover:scale-110" />
                    </div>
                </div>

                <div class="flex-1 p-8 lg:p-10 flex flex-col justify-center">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-orange-500/10 text-orange-500 mb-4">
                        <x-tabler-book class="w-6 h-6" />
                    </div>
                    <h3 class="text-xl font-semibold text-base-content">
                        <a href="https://kompass.secondnetwork.de" target="_blank" class="hover:text-orange-600 transition-colors">{{ __('Documentation') }}</a>
                    </h3>
                    <p class="mt-2 text-sm text-base-content/70 max-w-md">
                        {{ __("In this documentation we'll walk you through all information and tips.") }}
                    </p>
                    <a href="https://kompass.secondnetwork.de" target="_blank"
                        class="mt-5 inline-flex w-fit items-center gap-1 rounded-lg bg-orange-500 px-4 py-2 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-orange-600">
                        <span>{{ __('Explore the documentation') }}</span>
                        <x-tabler-arrow-narrow-right class="w-4 h-4" />
                    </a>
                </div>
            </div>
        </div>
        @endif

        <p class="alert alert-warning mt-6" wire:offline>
            {{ __('Whoops, your device has lost connection. The web page you are viewing is offline.') }}
        </p>
    </div>

<script>
    window.chartLabels = @json($chartLabels ?? []);
    window.chartPages = @json($chartPages ?? []);
    window.chartPagesPublished = @json($chartPagesPublished ?? []);
    window.chartPosts = @json($chartPosts ?? []);
    window.chartPostsPublished = @json($chartPostsPublished ?? []);

    document.addEventListener('livewire:navigated', function () {
        if (typeof initDashboardCharts === 'function') {
            initDashboardCharts();
        }
    });
</script>

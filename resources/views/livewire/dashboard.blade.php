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
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            <!-- Pages Card -->
            <x-kompass::dashboard.cart 
                :title="__('Pages')" 
                :value="$pagesCount" 
                :footerLabel="__('Published')" 
                :footerValue="$publishedPagesCount"
                link="/admin/pages"
                chartId="chartPagesSparkline"
            >
            </x-kompass::dashboard.cart>

            <!-- Posts Card -->
            <x-kompass::dashboard.cart 
                :title="__('Posts')" :footerLabel="__('Pub. / Draft')" :footerValue="$publishedPostsCount . ' / ' . $draftPostsCount" 
                :value="$postsCount" 
                :trend="$postTrend"
                :trendUp="$postTrend >= 0"
                link="/admin/posts"
                color="success"
                chartId="chartPostsSparkline"
            >
            </x-kompass::dashboard.cart>

            <!-- Media Card -->
            <x-kompass::dashboard.cart 
                :title="__('Media')" 
                :value="$mediaCount" 
                link="/admin/medialibrary"
                chartId="chartMediaSparkline"
            />

            <!-- Activity Card -->
            <x-kompass::dashboard.cart 
                :title="__('Activity')" 
                :value="$pagesCount + $postsCount" 
                chartId="chartActivitySparkline"
            />
        </div>
        <!-- End Grid -->

        <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-8">
            {{-- Documentation Block --}}
            <div class="bg-base-100 rounded-2xl border border-base-200 shadow-sm overflow-hidden flex flex-col md:flex-row items-stretch relative">
                <div class="w-full md:w-[60%] p-6 relative shrink-0">
                    {{-- Stylized Screenshot Placeholder (Dark Window on Solid Background) --}}
                    <div class="relative aspect-video rounded-lg overflow-hidden border border-base-300 bg-gray-50 group/shot shadow-sm">
                        
                        <div class="absolute inset-x-6 bottom-0 top-4 bg-[#0d1117] rounded-t-xl shadow-2xl border-x border-t border-zinc-800 flex flex-col overflow-hidden transform transition-transform group-hover/shot:translate-y-1">
                            {{-- Top Header / Search Bar (Dark) --}}
                            <div class="h-8 border-b border-zinc-800 flex items-center px-4 justify-between bg-zinc-900/80 backdrop-blur-md sticky top-0">
                                <div class="flex gap-1">
                                    <div class="w-2 h-2 rounded-full bg-red-500/40"></div>
                                    <div class="w-2 h-2 rounded-full bg-yellow-500/40"></div>
                                    <div class="w-2 h-2 rounded-full bg-green-500/40"></div>
                                </div>
                                <div class="w-32 h-4 bg-zinc-800 rounded-full border border-zinc-700 flex items-center px-2">
                                    <div class="w-2 h-2 bg-orange-500 rounded-full shadow-[0_0_8px_rgba(249,115,22,0.6)]"></div>
                                </div>
                                <div class="w-4 h-4 bg-zinc-800 rounded-full"></div>
                            </div>

                            <div class="flex flex-1 overflow-hidden">
                                {{-- Sidebar (Dark) --}}
                                <div class="w-14 border-r border-zinc-800 p-3 space-y-3 bg-black/20">
                                    <div class="w-full h-1 bg-orange-500/30 rounded"></div>
                                    <div class="w-4/5 h-1 bg-zinc-700 rounded"></div>
                                    <div class="w-5/6 h-1 bg-zinc-700 rounded"></div>
                                    <div class="w-3/4 h-1 bg-zinc-700 rounded"></div>
                                </div>
                                {{-- Content (Dark) --}}
                                <div class="flex-1 p-5 overflow-hidden">
                                    <div class="w-1/3 h-1.5 bg-orange-500/60 rounded mb-2"></div>
                                    <div class="w-3/4 h-4 bg-white/90 rounded-md mb-4"></div>
                                    
                                    {{-- Code Block Skeleton (Dark) --}}
                                    <div class="bg-black rounded-lg p-3 space-y-1.5 mb-4 border border-zinc-800 shadow-inner">
                                        <div class="w-1/2 h-0.5 bg-orange-400/40 rounded"></div>
                                        <div class="w-3/4 h-0.5 bg-orange-300/30 rounded"></div>
                                        <div class="w-2/3 h-0.5 bg-zinc-700 rounded"></div>
                                    </div>

                                    <div class="space-y-1.5 opacity-40">
                                        <div class="w-full h-0.5 bg-zinc-600 rounded"></div>
                                        <div class="w-full h-0.5 bg-zinc-600 rounded"></div>
                                        <div class="w-5/6 h-0.5 bg-zinc-600 rounded"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="w-full md:w-[50%] md:-ml-[10%] p-8 z-20 md:bg-base-100/60 md:backdrop-blur-sm border-l border-white/10 md:shadow-2xl shadow-black/5 flex flex-col justify-center">
                    <div class="flex items-center">
                        <x-tabler-book class="w-6 h-6 text-orange-500" />
                        <div class="ml-4 text-lg text-base-content leading-7 font-semibold">
                            <a href="https://kompass.secondnetwork.de" target="_blank">{{ __('Documentation') }}</a>
                        </div>
                    </div>
                    <div class="mt-3 text-sm text-base-content">
                        {{ __("In this documentation we'll walk you through all information and tips.") }}
                    </div>
                    <a href="https://kompass.secondnetwork.de" target="_blank" class="mt-4 inline-flex items-center text-sm font-semibold text-orange-600 hover:underline">
                        <span>{{ __('Explore the documentation') }}</span>
                        <x-tabler-arrow-narrow-right class="ml-1 w-4 h-4" />
                    </a>
                </div>
            </div>

            {{-- YouTube Block --}}
            <div class="bg-base-100 rounded-2xl border border-base-200 shadow-sm overflow-hidden flex flex-col md:flex-row items-stretch relative">
                <div class="w-full md:w-[60%] p-6 relative shrink-0">
                    {{-- Stylized Browser Placeholder (YouTube Style) --}}
                    <div class="relative aspect-video rounded-lg overflow-hidden border border-base-300 bg-gray-50 group/shot shadow-sm">
                        
                        <div class="absolute inset-x-6 bottom-0 top-4 bg-[#0F0F0F] rounded-t-xl shadow-2xl border-x border-t border-zinc-800 flex flex-col overflow-hidden transform transition-transform group-hover/shot:translate-y-1">
                            {{-- Top Header / Search Bar (YouTube Style) --}}
                            <div class="h-8 border-b border-zinc-800 flex items-center px-4 justify-between bg-[#0f0f0f] z-20">
                                <div class="flex gap-1">
                                    <div class="w-2 h-2 rounded-full bg-red-500/40"></div>
                                    <div class="w-2 h-2 rounded-full bg-yellow-500/40"></div>
                                    <div class="w-2 h-2 rounded-full bg-green-500/40"></div>
                                </div>
                                <div class="w-32 h-4 bg-zinc-900 rounded-full border border-zinc-700 flex items-center px-2">
                                    <div class="w-1.5 h-1.5 bg-red-600 rounded-full shadow-[0_0_8px_rgba(220,38,38,0.6)]"></div>
                                </div>
                                <div class="w-4 h-4 bg-zinc-800 rounded-full"></div>
                            </div>

                            {{-- Channel Page Content --}}
                            <div class="flex-1 flex flex-col overflow-hidden z-10">
                                {{-- Channel Banner --}}
                                <div class="h-10 w-full bg-gradient-to-r from-red-700/40 via-red-600/20 to-zinc-900 shrink-0"></div>

                                <div class="px-4 py-3 space-y-4">
                                    {{-- Channel Header --}}
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-full bg-gradient-to-tr from-red-600 to-orange-500 shadow-lg border-2 border-zinc-800 shrink-0 flex items-center justify-center">
                                            <x-tabler-signature class="w-6 h-6 text-white" />
                                        </div>
                                        <div class="space-y-1.5 flex-1">
                                            <div class="flex items-center gap-2">
                                                <div class="w-24 h-2 bg-white rounded-full"></div>
                                                <div class="w-3 h-3 bg-zinc-700 rounded-full flex items-center justify-center">
                                                    <div class="w-1.5 h-1 bg-white/40 rotate-45"></div>
                                                </div>
                                            </div>
                                            <div class="w-32 h-1 bg-zinc-700 rounded-full"></div>
                                        </div>
                                        <div class="px-3 py-1 bg-white rounded-full text-[8px] font-bold text-black uppercase">Subscribe</div>
                                    </div>

                                    {{-- Tabs --}}
                                    <div class="flex gap-4 border-b border-zinc-800 pb-1 px-1">
                                        <div class="w-10 h-1 bg-white rounded-full"></div>
                                        <div class="w-10 h-1 bg-zinc-800 rounded-full"></div>
                                        <div class="w-10 h-1 bg-zinc-800 rounded-full"></div>
                                        <div class="w-10 h-1 bg-zinc-800 rounded-full"></div>
                                    </div>

                                    {{-- Video Grid (Small) --}}
                                    <div class="grid grid-cols-3 gap-3">
                                        <div class="space-y-1.5">
                                            <div class="aspect-video bg-zinc-800 rounded-lg"></div>
                                            <div class="w-full h-1 bg-zinc-700 rounded-full"></div>
                                        </div>
                                        <div class="space-y-1.5">
                                            <div class="aspect-video bg-red-600/20 border border-red-600/30 rounded-lg flex items-center justify-center">
                                                <x-tabler-player-play class="w-3 h-3 text-red-600 fill-current" />
                                            </div>
                                            <div class="w-full h-1 bg-zinc-600 rounded-full"></div>
                                        </div>
                                        <div class="space-y-1.5">
                                            <div class="aspect-video bg-zinc-800 rounded-lg"></div>
                                            <div class="w-full h-1 bg-zinc-700 rounded-full"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="absolute inset-0 bg-gradient-to-t from-[#0f0f0f] via-transparent to-transparent opacity-40 pointer-events-none"></div>
                        </div>
                    </div>
                </div>

                <div class="w-full md:w-[65%] md:-ml-[10%] p-8 z-20 md:bg-base-100/60 md:backdrop-blur-sm border-l border-white/10 md:shadow-2xl shadow-black/5 flex flex-col justify-center">
                    <div class="flex items-center">
                        <x-tabler-device-tv class="w-6 h-6 text-red-600" />
                        <div class="ml-4 text-lg text-base-content leading-7 font-semibold">{{ __('YouTube tutorials') }}</div>
                    </div>
                    <div class="mt-3 text-sm text-base-content/70">
                        {{ __('Video tutorials for Kompass') }}
                    </div>
                    <a href="https://youtube.com/@secondnetwork" target="_blank" class="mt-4 inline-flex items-center text-sm font-semibold text-red-600 hover:underline">
                        <span>{{ __('Start watching') }}</span>
                        <x-tabler-arrow-narrow-right class="ml-1 w-4 h-4" />
                    </a>
                </div>
            </div>
        </div>

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

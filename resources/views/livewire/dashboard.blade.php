<div>
    <div>
        <div class="py-4" >
        <div class="rounded-xl items-center shadow bg-center bg-cover col-span-3"
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
                chartId="area-chart"
            >
                <div class="mt-4">
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-sm text-gray-600">{{ __('Published ratio') }}</span>
                        <span class="text-sm font-semibold text-primary">
                            @if($pagesCount > 0)
                                {{ round(($publishedPagesCount / $pagesCount) * 100) }}%
                            @else
                                0%
                            @endif
                        </span>
                    </div>
                    <div class="flex w-full h-1.5 bg-base-200 rounded-full overflow-hidden">
                        <div class="flex flex-col justify-center bg-primary transition duration-500" style="width: @if($pagesCount > 0){{ ($publishedPagesCount / $pagesCount) * 100 }}@else 0 @endif%"></div>
                    </div>
                </div>
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
                <div class="mt-4">
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-sm text-gray-600">{{ __('Published ratio') }}</span>
                        <span class="text-sm font-semibold text-success">
                            @if($postsCount > 0)
                                {{ round(($publishedPostsCount / $postsCount) * 100) }}%
                            @else
                                0%
                            @endif
                        </span>
                    </div>
                    <div class="flex w-full h-1.5 bg-base-200 rounded-full overflow-hidden">
                        <div class="flex flex-col justify-center bg-success transition duration-500" style="width: @if($postsCount > 0){{ ($publishedPostsCount / $postsCount) * 100 }}@else 0 @endif%"></div>
                    </div>
                </div>
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

        <p class="alert alert-warning" wire:offline>
            {{ __('Whoops, your device has lost connection. The web page you are viewing is offline.') }}
        </p>
    </div>
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

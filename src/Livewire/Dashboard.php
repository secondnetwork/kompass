<?php

namespace Secondnetwork\Kompass\Livewire;

use Carbon\Carbon;
use Illuminate\View\View;
use Livewire\Component;
use Secondnetwork\Kompass\Models\File;
use Secondnetwork\Kompass\Models\Page;
use Secondnetwork\Kompass\Models\Post;
use Secondnetwork\Kompass\Models\Setting;

use function view;

class Dashboard extends Component
{
    /**
     * Hide the documentation card by flipping the global setting that the
     * settings switch ("Show documentation card on dashboard") controls.
     */
    public function hideDocsCard(): void
    {
        Setting::updateOrCreate(
            ['key' => 'dashboard_docs_card', 'group' => 'global'],
            ['data' => '', 'name' => 'Dashboard Docs Card'],
        );
    }

    public function render(): View
    {
        $months = 6;
        $labels = [];
        $pagesCounts = [];
        $pagesPublished = [];
        $postsCounts = [];
        $postsPublished = [];

        for ($m = $months - 1; $m >= 0; $m--) {
            $start = Carbon::now()->subMonths($m)->startOfMonth();
            $end = Carbon::now()->subMonths($m)->endOfMonth();
            $labels[] = $start->format('Y-m');

            $pagesCounts[] = Page::whereBetween('created_at', [$start, $end])->count();
            $pagesPublished[] = Page::whereBetween('created_at', [$start, $end])->where('status', 'published')->count();

            $postsCounts[] = Post::whereBetween('created_at', [$start, $end])->count();
            $postsPublished[] = Post::whereBetween('created_at', [$start, $end])->where('status', 'published')->count();
        }

        $lastMonthPosts = end($postsCounts);
        $prevMonthPosts = prev($postsCounts) ?: 0;
        $postTrend = $prevMonthPosts > 0 ? (($lastMonthPosts - $prevMonthPosts) / $prevMonthPosts) * 100 : 0;

        // Read straight from the DB (not the cached setting() helper) so a fresh
        // toggle is reflected within the same request. Unset = shown by default.
        $docsCard = optional(
            Setting::where('group', 'global')->where('key', 'dashboard_docs_card')->first()
        )->data;
        $showDocsCard = $docsCard === null ? true : (bool) $docsCard;

        return view('kompass::livewire.dashboard', [
            'showDocsCard' => $showDocsCard,
            'pagesCount' => Page::count(),
            'publishedPagesCount' => Page::where('status', 'published')->count(),
            'postsCount' => Post::count(),
            'publishedPostsCount' => Post::where('status', 'published')->count(),
            'draftPostsCount' => Post::where('status', 'draft')->count(),
            'postTrend' => round($postTrend),
            'chartLabels' => $labels,
            'chartPages' => $pagesCounts,
            'chartPagesPublished' => $pagesPublished,
            'chartPosts' => $postsCounts,
            'chartPostsPublished' => $postsPublished,
            'mediaCount' => File::count(),
        ])->layout('kompass::admin.layouts.app');
    }
}

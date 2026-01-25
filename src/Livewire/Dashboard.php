<?php

namespace Secondnetwork\Kompass\Livewire;

use Livewire\Component;
use Carbon\Carbon as Carbon;
use function view;

use Secondnetwork\Kompass\Models\Page;
use Secondnetwork\Kompass\Models\Post;
use Secondnetwork\Kompass\Models\File;

class Dashboard extends Component
{
    public function render(): \Illuminate\View\View
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

        return view('kompass::livewire.dashboard', [
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

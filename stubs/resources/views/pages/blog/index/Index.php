<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Secondnetwork\Kompass\Models\Post;
use Secondnetwork\Kompass\Models\File;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

// Optional: Setzt den Seitentitel
new class extends Component
{
    public $amount = 6; // Anzahl der anzuzeigenden Posts

    #[Locked] // Sicherheit: Verhindert, dass Frontend-User diesen Wert manipulieren
    public $total;

    #[Locked]
    public $route_prefix = 'blog';

    public function mount()
    {
        // Performance: Zähle nur veröffentlichte Posts
        $this->total = Post::where('status', 'published')->count();
    }

    public function loadMore()
    {
        $this->amount += 6;
    }

    #[Computed]
    public function posts()
    {
        // Computed Property wird gecached für diesen Request
        return Post::query()
            ->where('status', 'published')
            ->orderBy('created_at', 'desc') // Sortierung direkt hier
            ->take($this->amount) // 'take' statt 'paginate' für Load More Button Logik
            ->get();
    }

    /**
     * Hilfsmethode, um Bilddaten abzurufen.
     * Dies bereinigt die Blade-View von komplexer PHP-Logik.
     */
    public function getPostImage($fileId)
    {
        if (!$fileId) return null;

        return Cache::rememberForever('kompass_imgId_' . $fileId, function () use ($fileId) {
            return File::find($fileId);
        });
    }


}
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
new #[Layout('layouts.main')] class extends Component
{
    public $amount = 6; // Anzahl der anzuzeigenden Posts

    #[Locked] // Sicherheit: Verhindert, dass Frontend-User diesen Wert manipulieren
    public $total;

    #[Locked]
    public $route_prefix = 'blog';

    public function mount($locale = null)
    {
        $localesData = setting('global.available_locales');
        if ($localesData) {
            $availableLocales = is_array($localesData) ? $localesData : json_decode($localesData, true);
        } else {
            $availableLocales = ['de', 'en'];
        }
        
        $defaultLocale = $availableLocales[0] ?? 'de';
        $land = in_array($locale, $availableLocales) ? $locale : $defaultLocale;
        app()->setLocale($land);
        
        $this->total = Post::where('status', 'published')->where('land', $land)->count();
    }

    public function loadMore()
    {
        $this->amount += 6;
    }

    #[Computed]
    public function posts()
    {
        $localesData = setting('global.available_locales');
        if ($localesData) {
            $availableLocales = is_array($localesData) ? $localesData : json_decode($localesData, true);
        } else {
            $availableLocales = ['de', 'en'];
        }
        
        $defaultLocale = $availableLocales[0] ?? 'de';
        $land = app()->getLocale() ?: $defaultLocale;

        return Post::query()
            ->where('status', 'published')
            ->where('land', $land)
            ->orderBy('created_at', 'desc')
            ->take($this->amount)
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
<?php

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Laravel\Head\Enums\Media;
use Laravel\Head\Facades\Head;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Secondnetwork\Kompass\Models\Block;
use Secondnetwork\Kompass\Models\Datafield;
use Secondnetwork\Kompass\Models\ErrorLog;
use Secondnetwork\Kompass\Models\File;
use Secondnetwork\Kompass\Models\Page;
use Secondnetwork\Kompass\Models\Redirect;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

new #[Layout('layouts::Main')] class extends Component
{
    public $page;

    public $page_frontNotFound = false;

    public $blocks = [];

    public $fields;

    public $settings;

    private bool $canSeeDrafts = false;

    public function mount(Request $request, $locale = null, $slug = null)
    {
        try {
            $this->canSeeDrafts = $this->userCanSeeDrafts();

            $localesData = setting('global.available_locales');
            if ($localesData) {
                $availableLocales = is_array($localesData) ? $localesData : json_decode($localesData, true);
            } else {
                $availableLocales = ['de', 'en'];
            }
            $defaultLocale = $availableLocales[0] ?? 'de';

            if (setting('global.multilingual')) {
                // Handle routes like /{slug} where only one param is passed
                if ($slug === null && $locale !== null) {
                    if (! in_array($locale, $availableLocales)) {
                        $slug = $locale;
                        $locale = $defaultLocale;
                    }
                }
                $land = $locale ?? $defaultLocale;
            } else {
                if ($slug === null && $locale !== null) {
                    $slug = $locale;
                }
                $land = $defaultLocale;
            }

            app()->setLocale($land);

            $this->resolvePageAndRedirect($land, $slug);

            if ($this->page instanceof Redirect) {
                $this->sendRedirect($this->page->new_url, (int) $this->page->status_code);
            }
            if (! empty($this->page->new_url)) {
                $this->sendRedirect($this->page->new_url, (int) $this->page->status_code);
            }
            if ($this->page && ! $this->page_frontNotFound) {
                $this->loadBlocks($this->page->slug);
            }

            if (! empty($this->page->slug)) {
                $this->setHeadMetadata();
            }

        } catch (NotFoundHttpException $e) {
            $this->log404Error($request->path(), $e);
            throw $e;
        }
    }

    private function resolvePageAndRedirect($land, $slug): void
    {
        $localesData = setting('global.available_locales');
        if ($localesData) {
            $availableLocales = is_array($localesData) ? $localesData : json_decode($localesData, true);
        } else {
            $availableLocales = ['de', 'en', 'tr'];
        }
        $defaultLocale = $availableLocales[0] ?? 'de';

        $isMultilingual = setting('global.multilingual');
        $landurl = ($isMultilingual && in_array($land, $availableLocales)) ? $land : $defaultLocale;
        $canSeeDrafts = $this->canSeeDrafts;

        if ($slug === null) {
            $this->page = Page::query()
                ->where(function ($query) use ($landurl, $isMultilingual) {
                    if ($isMultilingual) {
                        $query->where('land', $landurl)
                            ->orWhere('land', '')
                            ->orWhereNull('land');
                    }
                })
                ->where('layout', 'is_front_page')
                ->when(! $canSeeDrafts, fn ($query) => $query->where('status', 'published'))
                ->when($isMultilingual, function ($query) use ($landurl) {
                    $query->orderByRaw('CASE WHEN land = ? THEN 0 ELSE 1 END', [$landurl]);
                })
                ->first();

            if (! $this->page) {
                // Fallback to any front page if specific language not found
                $this->page = Page::query()
                    ->where('layout', 'is_front_page')
                    ->when(! $canSeeDrafts, fn ($query) => $query->where('status', 'published'))
                    ->first();
            }

            if (! $this->page) {
                $this->page_frontNotFound = true;

                return;
            }
        } else {
            $this->page = Page::query()
                ->where(function ($query) use ($landurl) {
                    $query->where('land', $landurl)
                        ->orWhere('land', '')
                        ->orWhereNull('land');
                })
                ->where('slug', $slug)
                ->when(! $canSeeDrafts, fn ($query) => $query->whereNot('status', 'draft'))
                ->orderByRaw('CASE WHEN land = ? THEN 0 ELSE 1 END', [$landurl])
                ->first();
        }

        if (! $this->page) {
            $this->page = Redirect::where('old_url', '/'.$slug)->first();
        }
        if (! $this->page && ! $this->page_frontNotFound) {
            throw new NotFoundHttpException('Page not found');
        }
    }

    private function loadBlocks($slug)
    {
        // Previewing a draft page only bypasses the page-level status check (see
        // resolvePageAndRedirect()) — individual blocks still need to be published
        // to render, even for privileged users. That makes the query result
        // identical for every visitor, so it can be cached per page. Block::boot()
        // flushes the whole cache on any block create/update/delete, so this never
        // goes stale. The cached classes (Block, Datafield, Meta, Collection) are
        // allow-listed for cache deserialization by KompassServiceProvider.
        $this->blocks = cache()->rememberForever(
            "blocks-page-{$this->page->id}",
            fn () => Block::where('blockable_type', 'page')
                ->where('blockable_id', $this->page->id)
                ->where('status', 'published')
                ->orderBy('order', 'asc')
                ->where('subgroup', null)
                ->with(['children' => function ($query): void {
                    $query->where('status', 'published');
                }, 'datafield', 'meta'])
                ->get(),
        );
    }

    private function userCanSeeDrafts(): bool
    {
        $user = auth()->user();

        return $user && $user->hasAnyRole(['admin', 'manager', 'editor', 'author', 'writer']);
    }

    private function setHeadMetadata(): void
    {
        $webtitle = setting('global.webtitle', 'Kompass');
        $supline = setting('global.supline', 'A Laravel CMS');

        Head::title($this->page->layout === 'is_front_page'
                ? $webtitle.' | '.$supline
                : ($this->page->title ?? $webtitle).' | '.$webtitle)
            ->description(filled($this->page->meta_description) ? $this->page->meta_description : setting('global.description', ''))
            ->og(locale: str_replace('_', '-', app()->getLocale()))
            ->twitter(site: setting('global.twitter_handle'))
            ->viewport('width=device-width, initial-scale=1.0')
            ->themeColor(setting('global.favicon_theme_color', '#ffffff'))
            ->meta('csrf-token', csrf_token())
            ->meta('url', url('/'));

        if ($ogImage = setting('global.ogimage_src')) {
            Head::ogImage(asset($ogImage), width: 1200, height: 630);
        }

        if ($faviconLight = setting('global.favicon_light_image_path')) {
            Head::icon(url($faviconLight), media: Media::Light)
                ->manifest(asset('favicon/site.webmanifest'))
                ->appleTouchIcon(asset('favicon/apple-touch-icon.png'));
        }

        if ($faviconDark = setting('global.favicon_dark_image_path', '')) {
            Head::icon(url($faviconDark), media: Media::Dark);
        }

        if (in_array($this->page->slug, ['datenschutz', 'impressum'])) {
            Head::hiddenFromRobots();
        }
    }

    private function loadFields($slug)
    {
        $blockIds = $this->blocks->pluck('id');

        $this->fields = Datafield::whereIn('block_id', $blockIds)->get()->groupBy('block_id');
    }

    public function getGallery($blockId = null)
    {
        if (! isset($this->fields[$blockId])) {
            return '';
        }

        $dataarray = [];

        foreach ($this->fields[$blockId] as $value) {
            if ($value->type === 'gallery' && $value->data !== null) {
                $file = File::find($value->data);
                if ($file) {
                    $dataarray[] = $this->generateImageTag($file);
                }
            }
        }

        return implode('', $dataarray);
    }

    private function generateImageTag($file)
    {
        return '<picture>
            <source type="image/avif" srcset="'.asset('storage'.$file->path.'/'.$file->slug).'.avif">
            <img class="aspect-square max-w-[clamp(10rem,28vmin,20rem)] rounded-md object-cover shadow-md"
            src="'.asset('storage'.$file->path.'/'.$file->slug.'.'.$file->extension).'" alt="'.$file->alt.'" />
            </picture>';
    }

    public function getField($type, $blockId = null, $class = null, $size = null)
    {
        if (! isset($this->fields[$blockId])) {
            return '';
        }

        foreach ($this->fields[$blockId] as $value) {
            if ($value->type === $type && $value->data !== null) {
                $file = File::find($value->data);
                if ($file && in_array($value->type, ['video', 'poster', 'image'])) {
                    return $this->generateMediaTag($file, $value->type, $class, $size);
                }

                return $value->data;
            }
        }

        return '';
    }

    private function generateMediaTag($file, $type, $class, $size)
    {
        $sizes = $size ? '_'.$size : '';
        if ($type === 'image') {
            return '<picture>
                <source type="image/avif" srcset="'.asset('storage/'.$file->path.'/'.$file->slug).'.avif">
                <img class="'.$class.'" src="'.asset('storage'.$file->path.'/'.$file->slug.$sizes.'.'.$file->extension).'" alt="'.$file->alt.'" />
                </picture>';
        }

        return $file->path.'/'.$file->slug.'.'.$file->extension;
    }

    /**
     * Issue a real HTTP redirect from within a full-page Livewire mount.
     *
     * Livewire ignores the return value of mount(), so a plain `return redirect()`
     * does not halt rendering. Throwing an HttpResponseException short-circuits the
     * request while preserving the configured status code (301/302). A 410 status
     * is treated as "Gone".
     */
    protected function sendRedirect(?string $url, int $statusCode): void
    {
        if ($statusCode === 410) {
            abort(410);
        }

        if (empty($url)) {
            return;
        }

        throw new HttpResponseException(new RedirectResponse($url, $statusCode));
    }

    protected function log404Error($url, $e)
    {
        ErrorLog::create([
            'url' => $url,
            'message' => $e->getMessage(),
            'user_id' => auth()->id(), // Optional, um Benutzer-ID zu loggen
            'ip_address' => request()->ip(),
            'status_code' => 404, // Setze den Statuscode auf 404
        ]);
    }

    // public function render()
    // {
    //     return view('livewire.pageview')->layout('layouts.main');
    // }
};

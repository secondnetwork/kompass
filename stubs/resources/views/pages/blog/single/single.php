<?php

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Secondnetwork\Kompass\Models\Block;
use Secondnetwork\Kompass\Models\Datafield;
use Secondnetwork\Kompass\Models\ErrorLog;
use Secondnetwork\Kompass\Models\File;
use Secondnetwork\Kompass\Models\Post;
use Secondnetwork\Kompass\Models\Redirect;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

new #[Layout('layouts.main')] class extends Component
{
    public $post;

    public $blocks = [];

    public $blocks_id;

    public $datafields;

    public $fields;

    public $blocktemplates;

    public $blocks_collapse;

    public function mount(Request $request, $locale = null, $slug = null)
    {
        try {
            $localesData = setting('global.available_locales');
            if ($localesData) {
                $availableLocales = is_array($localesData) ? $localesData : json_decode($localesData, true);
            } else {
                $availableLocales = ['de', 'en'];
            }

            $defaultLocale = $availableLocales[0] ?? 'de';

            if ($slug === null && $locale !== null) {
                if (! in_array($locale, $availableLocales)) {
                    $slug = $locale;
                    $locale = $defaultLocale;
                }
            }

            $land = $locale ?? $defaultLocale;
            app()->setLocale($land);

            $this->ResolvePath($slug, $land);
            if ($this->post instanceof Redirect) {
                $this->sendRedirect($this->post->new_url, (int) $this->post->status_code);
            }
            // blockable_type
            $blocks = Block::where('blockable_type', 'post')->where('blockable_id', $this->post->id)->where('status', 'published')->orderBy('order', 'asc')->where('subgroup', null)->with('children')->get();

            if ($blocks->isNotEmpty()) {
                $this->blocks = $blocks;
                $blocks_id = Block::where('blockable_id', $this->post->id)->orderBy('order', 'asc')->pluck('id');

                Arr::collapse($blocks_id);

                $this->fields = Datafield::whereIn('block_id', $blocks_id)->get();
            }

        } catch (NotFoundHttpException $e) {
            $this->log404Error($request->path(), $e);
            throw $e;
        }

    }

    public function ResolvePath($slug, $land = null)
    {
        $user = auth()->user();

        $privilegedRoles = ['admin', 'manager', 'editor', 'author', 'writer'];
        $canSeeDrafts = $user && $user->hasAnyRole($privilegedRoles);

        $query = Post::where('slug', $slug);

        if ($land && setting('global.multilingual')) {
            $query->where('land', $land);
        }

        if (! $canSeeDrafts) {
            $query->whereNot('status', 'draft');
        }

        $this->post = $query->first();

        // Redirect Prüfung
        if (! $this->post) {
            $this->post = Redirect::where('old_url', '/'.$slug)->first();
        }

        if (! $this->post) {
            throw new NotFoundHttpException('Post not found - '.$slug);
        }
    }

    public function get_gallery($blockis = null)
    {

        foreach ($this->fields[$blockis] as $value) {

            if ($value->type == 'gallery' && $value->data != null) {
                $file = File::where('id', $value->data)->first();
                if ($file) {
                    // $dataarray[] =   asset('storage' . $file->path . '/' . $file->slug) . '.avif';

                    $dataarray[] = '<picture>
                    <source type="image/avif" srcset="'.asset('storage'.$file->path.'/'.$file->slug).'.avif">
                    <img class="aspect-square max-w-[clamp(10rem,28vmin,20rem)] rounded-md object-cover shadow-md"
                    src="'.asset('storage'.$file->path.'/'.$file->slug.'.'.$file->extension).'" alt="'.$file->alt.'" />
                    </picture>';
                }
            }

        }

        $str = implode('', $dataarray);

        return $str;
    }

    public function get_field($type, $blockis = null, $class = null, $size = null)
    {

        foreach ($this->fields as $value) {

            if ($blockis == $value->block_id) {
                if ($value->type == $type) {
                    if ($value->type == 'video' && $value->data != null) {
                        $file = File::where('id', $value->data)->first();

                        return $file->path.'/'.$file->slug.'.'.$file->extension;
                    }
                    if ($value->type == 'poster' && $value->data != null) {
                        $file = File::where('id', $value->data)->first();

                        return $file->path.'/'.$file->slug.'.'.$file->extension;
                    }
                    if ($value->type == 'image' && $value->data != null) {
                        $file = File::where('id', $value->data)->first();
                        if ($file) {
                            if ($size) {
                                $sizes = '_'.$size;

                                return '<picture>
                                <source type="image/avif" srcset="'.asset('storage/'.$file->path.'/'.$file->slug).'.avif">
                                <img class="'.$class.'" src="'.asset('storage'.$file->path.'/'.$file->slug.$sizes.'.'.$file->extension).'" alt="'.$file->alt.'" />
                                </picture>
                                ';
                            } else {
                                return '<picture>
                                <source type="image/avif" srcset="'.asset('storage/'.$file->path.'/'.$file->slug).'.avif">
                                <img class="'.$class.'" src="'.asset('storage'.$file->path.'/'.$file->slug.'.'.$file->extension).'" alt="'.$file->alt.'" />
                                </picture>
                                ';
                            }
                        }

                        return '';
                    }

                    return $value->data;
                }

            }

        }
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

    public function getPostImage($fileId)
    {
        if (! $fileId) {
            return null;
        }

        return Cache::rememberForever('kompass_imgId_'.$fileId, function () use ($fileId) {
            return File::find($fileId);
        });
    }

    // public function render()
    // {
    //     return view('livewire.pages.blog.single', [
    //         // 'SEOData' => $this->getDynamicSEOData()
    //     ])->layout('layouts.main');
    // }
};

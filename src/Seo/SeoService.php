<?php

namespace Secondnetwork\Kompass\Seo;

class SeoService
{
    protected array $data = [];

    protected array $tags = [];

    protected array $twitter = [];

    protected bool $twitterEnabled = false;

    public function title(string $title, ?string $default = null, ?callable $modify = null): self
    {
        if ($default && ! isset($this->data['title'])) {
            $this->data['title'] = $title;
            $this->data['title_default'] = $default;
        } else {
            $this->data['title'] = $title;
        }

        if ($modify && isset($this->data['title_default'])) {
            $this->data['title'] = $modify($this->data['title_default']);
        }

        return $this;
    }

    public function description(?string $description = null): self
    {
        $this->data['description'] = $description;

        return $this;
    }

    public function keywords(?string $keywords = null): self
    {
        $this->data['keywords'] = $keywords;

        return $this;
    }

    public function locale(?string $locale = null): self
    {
        $this->data['locale'] = $locale ?? \Illuminate\Support\Facades\App::getLocale();

        return $this;
    }

    public function site(?string $site = null): self
    {
        $this->data['site'] = $site;

        return $this;
    }

    public function url(?string $url = null): self
    {
        $this->data['url'] = $url;

        return $this;
    }

    public function image(?string $image = null): self
    {
        $this->data['image'] = $image;

        return $this;
    }

    public function type(string $type = 'website'): self
    {
        $this->data['type'] = $type;

        return $this;
    }

    public function twitter(): self
    {
        $this->twitterEnabled = true;
        $this->twitter['site'] = setting('global.twitter_handle') ?? '';

        return $this;
    }

    public function tag(string $key, string $value): self
    {
        $this->tags[$key] = $value;

        return $this;
    }

    public function get(string $key, $default = null)
    {
        return $this->data[$key] ?? $default;
    }

    public function all(): array
    {
        return $this->data;
    }

    public function tags(): array
    {
        return $this->tags;
    }

    public function hasTag(string $key): bool
    {
        return isset($this->tags[$key]);
    }

    public function isTwitterEnabled(): bool
    {
        return $this->twitterEnabled;
    }

    public function twitterData(): array
    {
        return $this->twitter;
    }

    public function setFromArray(array $data): self
    {
        foreach ($data as $key => $value) {
            match ($key) {
                'title' => $this->title($value),
                'description' => $this->description($value),
                'keywords' => $this->keywords($value),
                'locale' => $this->locale($value),
                'site' => $this->site($value),
                'url' => $this->url($value),
                'image' => $this->image($value),
                'type' => $this->type($value),
                default => null,
            };
        }

        return $this;
    }

    public function reset(): void
    {
        $this->data = [];
        $this->tags = [];
        $this->twitter = [];
        $this->twitterEnabled = false;
    }

    public function render(string|array $key = null, $default = null): ?string
    {
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                $this->setFromArray([$k => $v]);
            }
            return null;
        }

        if ($key === null) {
            return $this->data['title'] ?? $default;
        }

        if ($key === 'title') {
            return $this->data['title'] ?? $default;
        }

        if (str_starts_with($key, 'twitter.')) {
            $twitterKey = substr($key, 8);
            return $this->twitter[$twitterKey] ?? $default;
        }

        return $this->data[$key] ?? $default;
    }
}

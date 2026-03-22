<?php

namespace Secondnetwork\Kompass\Seo;

use Illuminate\Support\Facades\Facade;

/**
 * @method static SeoService title(string $title, ?string $default = null, ?callable $modify = null)
 * @method static SeoService description(string $description = null)
 * @method static SeoService keywords(string $keywords = null)
 * @method static SeoService locale(string $locale = null)
 * @method static SeoService site(string $site = null)
 * @method static SeoService url(string $url = null)
 * @method static SeoService image(string $image = null)
 * @method static SeoService type(string $type = 'website')
 * @method static SeoService twitter()
 * @method static SeoService tag(string $key, string $value)
 * @method static SeoService setFromArray(array $data)
 * @method static mixed get(string $key, $default = null)
 * @method static array all()
 * @method static array tags()
 * @method static bool hasTag(string $key)
 * @method static bool isTwitterEnabled()
 * @method static array twitterData()
 * @method static SeoService reset()
 */
class SeoFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'seo';
    }
}

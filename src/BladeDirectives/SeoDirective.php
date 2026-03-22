<?php

namespace Secondnetwork\Kompass\BladeDirectives;

class SeoDirective
{
    public static function handle($expression): string
    {
        $expression = trim($expression, '()');

        return "<?php seo()->setFromArray({$expression}); ?>";
    }
}

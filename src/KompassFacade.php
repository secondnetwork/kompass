<?php

namespace Secondnetwork\Kompass;

use Illuminate\Support\Facades\Facade;
use Secondnetwork\Kompass\Skeleton\SkeletonClass;

/**
 * @see SkeletonClass
 */
class KompassFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'kompass';
    }
}

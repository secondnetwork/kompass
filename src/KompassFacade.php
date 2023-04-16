<?php

namespace Secondnetwork\Kompass;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Secondnetwork\Kompass\Skeleton\SkeletonClass
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

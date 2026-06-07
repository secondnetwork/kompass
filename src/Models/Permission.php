<?php

namespace Secondnetwork\Kompass\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    /**
     * Thin wrapper around Spatie's Permission model so the package can expose
     * permission management through its own Models namespace while reusing the
     * underlying `permissions` table and guard logic.
     */
}

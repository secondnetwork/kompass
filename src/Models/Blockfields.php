<?php

namespace Secondnetwork\Kompass\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Secondnetwork\Kompass\Traits\LogsActivity;

class Blockfields extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $table = 'blockfields';

    protected $guarded = [];

    /**
     * The block template this field definition belongs to.
     */
    public function blocktemplate(): BelongsTo
    {
        return $this->belongsTo(Blocktemplates::class, 'blocktemplate_id');
    }
}

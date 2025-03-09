<?php

namespace Secondnetwork\Kompass\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Blockfields extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $table = 'blockfields';

    // protected $casts = [
    //     'content' => 'array',
    // ];

    // protected $fillable = [
    //     'id','status', 'name', 'blocktemplate_id', 'slug', 'type', 'grid', 'layout','content'
    // ];
    protected $guarded = [];

    public function blocktemplates()
    {
        return $this->belongsToMany('Secondnetwork\Kompass\Models\blocktemplates');
        // return $this->hasOne('Rote');
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['name']);
        // Chain fluent methods for configuration options
    }
}

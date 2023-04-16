<?php

namespace Secondnetwork\Kompass\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blockfields extends Model
{
    use HasFactory;

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
}

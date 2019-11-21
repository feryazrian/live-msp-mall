<?php

namespace Marketplace;

use Illuminate\Database\Eloquent\Model;

use Cviebrock\EloquentSluggable\Sluggable;

class PpobType extends Model
{
    use Sluggable;

    protected $table = 'ppob_types';

    public function sluggable() {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function plan() {
        return $this->belongsTo('Marketplace\PpobPlan');
    }
}

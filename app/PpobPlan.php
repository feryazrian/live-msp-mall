<?php

namespace Marketplace;

use Illuminate\Database\Eloquent\Model;

use Cviebrock\EloquentSluggable\Sluggable;

class PpobPlan extends Model
{
    use Sluggable;

    protected $table = 'ppob_plans';

    public function sluggable() {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }
}

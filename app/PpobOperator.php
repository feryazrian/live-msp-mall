<?php

namespace Marketplace;

use Illuminate\Database\Eloquent\Model;

use Cviebrock\EloquentSluggable\Sluggable;

class PpobOperator extends Model
{
    use Sluggable;

    protected $table = 'ppob_operators';

    public function sluggable() {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function type() {
        return $this->belongsTo('Marketplace\PpobType');
    }
}

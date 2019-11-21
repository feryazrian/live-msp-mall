<?php

namespace Marketplace;

use Illuminate\Database\Eloquent\Model;

use Cviebrock\EloquentSluggable\Sluggable;

class Page extends Model
{
    use Sluggable;

    protected $table = 'pages';

    public function sluggable() {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function user() {
        return $this->belongsTo('Marketplace\User');
    }

    public function footer() {
        return $this->belongsTo('Marketplace\Footer');
    }
}

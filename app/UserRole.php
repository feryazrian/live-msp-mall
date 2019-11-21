<?php

namespace Marketplace;

use Illuminate\Database\Eloquent\Model;

use Cviebrock\EloquentSluggable\Sluggable;

class UserRole extends Model
{
    use Sluggable;

    protected $table = 'user_roles';

    public function sluggable() {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }
}

<?php

namespace Marketplace;

use Illuminate\Database\Eloquent\Model;

use Cviebrock\EloquentSluggable\Sluggable;

class LoyaltyMember extends Model
{
    use Sluggable;

    protected $table = 'loyalty_member';

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
}

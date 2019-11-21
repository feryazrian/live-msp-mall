<?php

namespace Marketplace;

use Illuminate\Database\Eloquent\Model;

use Cviebrock\EloquentSluggable\Sluggable;

class Category extends Model
{
    use Sluggable;

    protected $table = 'categories';

    public function sluggable() {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function product() {
        return $this->hasMany('Marketplace\Product')
            ->where('status', 1);
    }

    public function child() {
        return $this->hasMany('Marketplace\Category', 'parent_id');
    }

    public function parent() {
        return $this->belongsTo('Marketplace\Category', 'parent_id');
    }

    public function product_highlight() {
        return $this->hasMany('Marketplace\Product')
            // ->orderBy('created_at', 'DESC')
            ->inRandomOrder()
            ->where('status', 1)
            ->where('stock', '>', 0)
            ->limit(5);
    }

    public function random_product() {
        return $this->hasMany('Marketplace\Product')
            ->with([
                'productphoto' => function ($qy) {
                    $qy->inRandomOrder();
                },
                'user' => function ($qy) {
                    $qy->with(['kabupaten', 'merchant' => function($qm){
                        $qm->with(['address' => function ($qa){
                            $qa->with('kabupaten');
                        }]);
                    }]);
                }
            ])
            ->where('status', 1)
            ->where('stock', '>', 0)
            ->inRandomOrder();
    }

    public function user() {
        return $this->belongsTo('Marketplace\User');
    }
}

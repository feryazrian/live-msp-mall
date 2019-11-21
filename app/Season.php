<?php

namespace Marketplace;

use Illuminate\Database\Eloquent\Model;

use Cviebrock\EloquentSluggable\Sluggable;

class Season extends Model
{
    use Sluggable;

    protected $table = 'seasons';

    public function sluggable() {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function seasonproduct() {
        return $this->hasMany('Marketplace\SeasonProduct')
            ->whereHas('product', function($q) {
				$q->where('status', 1);
			})
            ->limit(5);
    }

    public function random_products(){
        return $this->hasMany('Marketplace\SeasonProduct')
            ->inRandomOrder()
            ->with(['product' => function ($q) {
                $q->where('status', 1)
                    ->where('sale', 0)
                    ->where('stock', '>', 0)
                    ->with([
                        'category',
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
                    ]);
            }]);
    }

    public function user() {
        return $this->belongsTo('Marketplace\User');
    }
}

<?php

namespace Marketplace;
use Marketplace\Promo;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $table = 'banners';
    protected $fillable = [
        'id',
        'title',
        'description',
        'image_path',
        'link',
        'flag',
        'publish_date',
        'end_date',
        'created_at',
        'updated_at',
        'deleted_at',
        'slug'
    ];
    protected $primaryKey = 'id';

    public function promoppob()
    {
        // return $this->belongsToMany(businesstype::class, 'banner_business_type','banner_id', 'business_type_id')->withTimestamps();

        return $this->belongsToMany(Promo::class,'promo_banner',"banner_id",'promo_id' )
            // ->wherePivotIn('ppob_type_id', $data)
            // ->withPivot([
            //     'promo_id',
            //     'ppob_type_id'
            // ])
            ->withTimestamps();
    }
}

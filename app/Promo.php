<?php

namespace Marketplace;

use Illuminate\Database\Eloquent\Model;
use Marketplace\PpobType;
use Marketplace\PromoProductType;
use Marketplace\ProductType;

use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Sluggable;

class Promo extends Model
{
    use Sluggable;

    protected $table = 'promo';
    protected $fillable =[
        'user_id',
        'type_id',
        'name',
        'code',
        'expired',
        'discount_type_id',
        'transaction_min',
        'discount_price',
        'shipping_code',
        'discount_max',
        'discount_percent',
        'quota',
        'total_quota',
        'term_condition',
        'quota_user_day',
        'quota_user_total'
    ];
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

    public function type() {
        return $this->belongsTo('Marketplace\PromoType');
    }
    public function product_type()
    {
        return $this->belongsToMany(ProductType::class,'promo_product_type','promo_id','product_type_id');
    }
    public function promoppob()
    {
        // return $this->belongsToMany(businesstype::class, 'banner_business_type','banner_id', 'business_type_id')->withTimestamps();

        return $this->belongsToMany(PpobType::class,'ppob_promo','promo_id', "ppob_type_id")
            // ->wherePivotIn('ppob_type_id', $data)
            // ->withPivot([
            //     'promo_id',
            //     'ppob_type_id'
            // ])
            ->withTimestamps();
    }

    public static function getDetailPromoByCode($promo_code){
        $promo = Promo::where('code', $promo_code)
				->where('expired', '>', Carbon::now()->format('Y-m-d H:i:s'))
                ->first();

        return $promo;
    }
}

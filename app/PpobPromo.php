<?php 
namespace Marketplace;

use Illuminate\Database\Eloquent\Model;

class PpobPromo extends Model
{
    protected $table = 'ppob_promo';
    protected $fillable =[
        'promo_id',
        'ppob_type_id'
    ];
}
<?php

namespace Marketplace;

use Illuminate\Database\Eloquent\Model;

class Live extends Model
{
    protected $table = 'lives';
    protected $fillable =[
        "id",
        "episode",
        "title",
        "description",
        "start_time",
        "end_time",
    ];
    public $timestamps = true;
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Image extends Model
{
    //
    use SoftDeletes;
    protected $fillable = ['image_link', 'rank', 'product_id'];

    function user() {
        return $this->hasOne('App\User');
    }

    function slider() {
        return $this->hasOne('App\Slider');
    }

    function product() {
        return $this->belongsTo('App\Product');
    }
    
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Slider extends Model
{
    //
    use SoftDeletes;
    protected $fillable = ['slider_name', 'slider_status', 'image_id', 'order', 'role_id'];

    function image() {
        return $this->beLongsTo('App\Image');
    }
}



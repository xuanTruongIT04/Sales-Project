<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Banner extends Model
{
        //
        use SoftDeletes;
        protected $fillable = ['banner_name', 'banner_status', 'image_id', 'role_id'];
    
        function image() {
            return $this->beLongsTo('App\Image');
        }
}

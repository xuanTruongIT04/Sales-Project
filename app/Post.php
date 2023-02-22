<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    //
    use SoftDeletes;
    protected $fillable = ['post_title', 'post_desc', 'post_content', 'slug', 'post_status', 'post_cat_id', 'image_id' ];

    function image() {
        return $this->beLongsTo('App\Image');
    }
}

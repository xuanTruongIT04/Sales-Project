<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostCat extends Model
{
    //
    use SoftDeletes;
    protected $fillable = ['post_cat_title', 'slug', 'level', 'post_cat_status', 'cat_parent_id'];

}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    //
    use SoftDeletes;
    protected $fillable = ['page_title', 'slug', 'page_desc', 'page_content', 'page_status' ];
}

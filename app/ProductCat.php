<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductCat extends Model
{
    //
    use SoftDeletes;
    protected $fillable = ['product_cat_title', 'slug', 'level', 'product_cat_status', 'cat_parent_id'];
}

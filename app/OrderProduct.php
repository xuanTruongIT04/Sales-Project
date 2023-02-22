<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderProduct extends Model
{
    //
    use SoftDeletes;
    protected $fillable = ["order_id", "product_id", "number_order"];

    
    function order() {
        return $this->belongsTo('App\Order');
    }

    function product() {
        return $this->belongsTo('App\Product');
    }
    
}

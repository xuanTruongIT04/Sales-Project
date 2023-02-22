<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    //
    use SoftDeletes;

    protected $fillable = ['customer_name', 'number_phone', "email"];

    function order() {
        return $this->hasMany('App\Order');
    }


}

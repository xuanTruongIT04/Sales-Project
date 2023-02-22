<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    //
    use SoftDeletes;
    protected $fillable = ['product_code', 'product_name', 'slug', 'product_desc', 'product_detail', 'product_status', 'price_old', 'price_new', 'qty_sold', 'qty_remain', 'sold_most', 'product_cat_id'];

    public function image()
    {
        return $this->hasMany('App\Image');
    }

    public function order()
    {
        return $this->belongsToMany('App\Order');
    }

    public function orderProduct()
    {
        return $this->hasMany('App\OrderProduct');
    }

    public function scopeSearch($query)
    {
        if (request("key")) {
            $key = request("key");
            $query = $query->where("product_name", "LIKE", "%" . $key . "%")->limit(8);
        }
        return $query;
    }

    public function scopeSearchNav($query)
    {
        if (request("key_word")) {
            $key_word = request("key_word");
            $query = $query->where("product_name", "LIKE", "%" . $key_word . "%");
        }
        return $query;
    }

    public function scopeFilterSidebar($sql)
    {
        $price = (int)(request("ajax_filter_price_sidebar"));
        $method = (request("ajax_filter_method_sidebar"));
        if (!empty($price)) {
            if ($price == 500000) {
                $sql = Product::where("price_new", "<", $price)
                    ->where("product_status", "licensed") -> limit(48);
            } else if ($price == 1000000) {
                $sql = Product::where("price_new", ">", "5000000")
                    ->where("price_new", "<=", $price)
                    ->where("product_status", "licensed") -> limit(48);
            } else if ($price == 5000000) {
                $sql = Product::where("price_new", ">=", "1000000")
                    ->where("price_new", "<=", $price)
                    ->where("product_status", "licensed") -> limit(48);
            } else if ($price == 10000000) {
                $sql = Product::where("price_new", ">=", "5000000")
                    ->where("price_new", "<=", $price)
                    ->where("product_status", "licensed") -> limit(48);
            } else if ($price == 10000001) {
                $sql = Product::where("price_new", ">=", $price)
                    ->where("product_status", "licensed") -> limit(48);
            }else if ($price == 999999999){
                $sql = Product::where("product_status", "licensed") -> limit(48);
            }
        } else if(!empty($method)){
            if ($method == "product_name_asc") {
                $sql = Product::where("product_status", "licensed")->orderBy("product_name") -> limit(48);
            }else if ($method == "product_name_desc") {
                $sql = Product::where("product_status", "licensed")->orderByDesc("product_name") -> limit(48);
            }else if ($method == "product_price_asc") {
                $sql = Product::where("product_status", "licensed")->orderBy("price_new") -> limit(48);
            }else if ($method == "product_price_desc") {
                $sql = Product::where("product_status", "licensed")->orderByDesc("price_new") -> limit(48);
            }
        }

        
        return $sql;
    }

}

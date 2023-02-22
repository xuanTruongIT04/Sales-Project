<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Product;

class ApiController extends Controller
{
    //
    public function ajaxSearch() {
        $data = Product::search() -> get();
        if(!empty($data) && count($data)>0) {
            foreach($data as &$item) {
                $item->product_thumb = get_product_main_thumb($item->id);
                $item->price_new = currency_format($item->price_new);
                $item->price_old = currency_format($item->price_old);
                $item->link_detail_product = link_detail_product($item -> id);
            }
            return $data;
        }
    }

    public function ajaxFilter(Request $requests)
    {   
        $data = Product::filterSidebar()-> get();
        foreach($data as $item) {
            $item->product_thumb = get_product_main_thumb($item->id);
            $item->product_name = brief_name($item->product_name, 10);
            $item->price_new = currency_format($item->price_new);
            $item->price_old = currency_format($item->price_old);
            $item->link_detail_product = link_detail_product($item -> id);
            $item->url_add_cart =  url_add_cart($item->slug);
            $item->url_buy_now =  url_buy_now($item->slug);
        }
        return $data;
    }
    
}

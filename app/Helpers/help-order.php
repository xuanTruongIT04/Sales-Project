<?php
use App\Customer;
use App\Order;
use App\Product;
use App\ProductCat;
use Illuminate\Support\Facades\DB;

if (!function_exists('get_product_id')) {
    function get_product_id($slug)
    {
        $sql = Product::where("slug", $slug)
            ->where("product_status", "licensed")
            ->select("id")
            ->first();
        if (!empty($sql)) {
            return $sql->id;
        }
        return false;
    }

}

if (!function_exists('get_product')) {
    function get_product($slug)
    {
        $sql = Product::where("slug", $slug)
            ->where("product_status", "licensed")
            ->first();
        if (!empty($sql)) {
            return $sql;
        }
        return false;
    }
}

if (!function_exists('link_detail_product')) {
    function link_detail_product($product_id)
    {
        if (!empty($product_id)) {
            $product_cat_id = Product::find($product_id)
                ->product_cat_id;
            $product_slug = Product::find($product_id)
                ->slug;
            $product_cat_slug = ProductCat::find($product_cat_id)
                ->slug;
            return "san-pham/{$product_cat_slug}/{$product_slug}";
        }
        return false;
    }
}


if (!function_exists('url_buy_now')) {
    function url_buy_now($slug)
    {
        return url("/mua-ngay/{$slug}");
    }
}

if (!function_exists('url_add_cart')) {
    function url_add_cart($slug)
    {
        return url("/them-san-pham/{$slug}");
    }
}

if (!function_exists('url_delete_cart')) {
    function url_delete_cart($rowId)
    {
        return url("/xoa-san-pham/{$rowId}");
    }
}

if (!function_exists('url_delete_all_cart')) {
    function url_delete_all_cart()
    {
        return url("/xoa-gio-hang");
    }
}

//SEND MAIL
if (!function_exists('get_info_customer')) {
    function get_info_customer($customer_id)
    {
        $sql = Customer::where("id", $customer_id)
            ->first();
        if (!empty($sql)) {
            return $sql;
        }
        return false;
    }
}

if (!function_exists('get_info_order')) {
    function get_info_order($order_id)
    {
        $sql = Order::find($order_id)
            ->first();
        if (!empty($sql)) {
            return $sql;
        }

        return false;
    }
}

if (!function_exists('get_list_product_by_order_id')) {
    function get_list_product_by_order_id($order_id)
    {
        $sql = DB::table("order_product")
            ->where("order_id", $order_id)
            ->select("product_id")
            ->get();
        $list_product_temp = array();
        $sql = json_decode(json_encode($sql), true);
        foreach ($sql as $item) {
            $list_product_temp[] = get_info_product_t1($item["product_id"]);
        }

        if (!empty($list_product_temp)) {
            return $list_product_temp;
        }

        return false;
    }
}

if (!function_exists('get_info_product_t1')) {
    function get_info_product_t1($product_id)
    {
        $sql = Product::where("id", $product_id)
            ->first();
        if (!empty($sql)) {
            $sql->link_detail_product =  link_detail_product($sql->id);
            $sql->url_add_cart =  url_add_cart($sql->slug);
            return $sql;
        }
        return false;
    }
}

if (!function_exists('get_qty_order')) {
    function get_qty_order($product_id)
    {
        $sql = DB::table("order_product")
            ->select("number_order")
            ->where("product_id", $product_id)
            ->first();

        if (!empty($sql)) {
            return $sql->number_order;
        }

        return false;
    }
}


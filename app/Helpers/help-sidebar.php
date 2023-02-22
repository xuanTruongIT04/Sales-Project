<?php
use App\Product;
use App\ProductCat;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\DB;

function get_list_product_order()
{

    $product_order = Cart::content();
    if (!empty($product_order)) {
        return $product_order;
    }
    return false;
}

function update_sold_most()
{
    // SET AGAIN PRODUCT NO SOLD MOST
    $data_1 = array(
        'sold_most' => '0',
    );
    $list_sold_most_real_time = DB::table("products")
        ->where("sold_most", "1")
        ->where("product_status", "licensed")
        ->get();

    $list_sold_most_real_time = json_decode(json_encode($list_sold_most_real_time), true);
    $cnt_update_agin = 0;
    $update_query_1 = 0;

    foreach ($list_sold_most_real_time as $sold_most_real_time) {
        $update_query_1 = DB::table("products")
            ->where("id", $sold_most_real_time['id'])
            ->update($data_1);
        if ($update_query_1 > 0) {
            $cnt_update_agin++;
        }

    }

    // SET  PRODUCT  SOLD MOST
    $list_sold_most = DB::table("products")
        ->where("product_status", "licensed")
        ->orderByDesc("qty_sold", "qty_remain")
        ->limit("8")
        ->get();
    $cnt_update = 0;
    $data_2 = array(
        'sold_most' => '1',
    );
    $update_query_2 = 0;
    $list_sold_most = json_decode(json_encode($list_sold_most), true);
    foreach ($list_sold_most as $sold_most) {
        $update_query_2 = DB::table("products")
            ->where("id", $sold_most['id'])
            ->update($data_2);
        if ($update_query_2 > 0) {
            $cnt_update++;
        }

    }
    if ($cnt_update >= 8) {
        return true;
    }

    return false;
}

function get_list_banner()
{
    $list_banner = DB::table("banners")
        ->where("banner_status", "licensed")
        ->orderByDesc("created_at")
        ->get();
    if (!empty($list_banner)) {
        return $list_banner;
    }

    return false;
}

function get_list_product_cat_parent()
{
    $sql = DB::table("product_cats")
        ->where("product_cat_status", "licensed")
        ->where("level", "0")
        ->select("id", "product_cat_title", "cat_parent_id")
        ->get();
    $list_product_cat_parent = array();
    if (!empty($sql)) {
        foreach ($sql as $item) {
            $list_product_cat_parent[] = $item;
        }
    }
    if (!empty($list_product_cat_parent)) {
        return $list_product_cat_parent;
    }

    return false;
}

function get_list_product_sold_most()
{
    $sql = DB::table("products")
        ->where("product_status", "licensed")
        ->where("sold_most", "1")
        ->get();
    if (!empty($sql)) {
        foreach ($sql as &$item) {
            $item->link_detail_product =  link_detail_product($item->id);
            $item->url_add_cart =  url_add_cart($item->slug);
            $item->url_buy_now =  url_buy_now($item->slug);
        }
        return $sql;
    }
    return false;
}

function get_product_thumb($product_id)
{
    $sql = DB::table("images")
        ->where("level", "1")
        ->where("product_id", $product_id)
        ->select("image_link")
        ->first();
    if (!empty($sql)) {
        return $sql['image_link'];
    }

    return false;
}

function get_product_main_thumb($product_id)
{
    $sql = DB::table("images")
        ->join("products", "images.product_id", "=", "products.id")
        ->where("product_status", "licensed")
        ->where("products.id", $product_id)
        ->where("images.rank", "1")
        ->select("image_link")
        ->first();
    if (!empty($sql)) {
        return url($sql->image_link);
    }
    return false;
}

function get_product_main_thumb_sendmail($product_id)
{
    $sql = DB::table("images")
        ->join("products", "image.product_id", "=", "products.id")
        ->where("product_status", "licensed")
        ->where("products.id", $product_id)
        ->where("images.rank" . "1")
        ->select("image_link")
        ->first();
    if (!empty($sql)) {
        return "xuantruong.unitopcv.com/admin/" . $sql['image_link'];
    }

    return false;
}

function get_branch_product_cat()
{
    $sql = DB::table("product_cats")
        ->where("product_status", "licensed")
        ->where("cat_parent_id", "-1")
        ->get();
    if (!empty($sql)) {
        return $sql;
    }

    return false;
}

function get_total_price()
{
    if (!empty(Cart::content()) && count(Cart::content()) > 0) {
        return Cart::total();
    }

    return false;
}

function get_slug_product_cat($product_cat_id)
{
    $sql = DB::table("product_cats")
        ->where("product_cat_status", "licensed")
        ->where("id", $product_cat_id)
        ->select("slug")
        ->first();
    if (!empty($sql)) {
        return $sql->slug;
    }
    return false;
}

function set_slug_product_cat($slug)
{
    return "san-pham/" . $slug;
}

function get_slug_product($product_id)
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
}

function get_slug_product_same_cat($product_id)
{
    if (!empty($product_id)) {
        $product_cat_id = Product::find($product_id)
            ->product_cat_id;
        $product_slug = Product::find($product_id)
            ->slug;
        $product_cat_slug = ProductCat::find($product_cat_id)
            ->slug;
        return "{$product_cat_slug}/{$product_slug}";
    }
}

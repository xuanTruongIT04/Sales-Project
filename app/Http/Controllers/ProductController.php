<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;

class ProductController extends Controller
{
    //
    public function __construct()
    {
        
    }

    function index(Request $request)
    {
        $slug = isset(Request() -> slug) ? Request() -> slug : 1;
        $product_id = isset($_GET['id']) ? (int) $_GET['id'] : get_product_id($slug);
        $info_product = get_info_product($slug);
        $list_product_same_cat = get_list_product_same_cat($info_product['product_cat_id']);
        
        // if (!empty($request -> btn_add_order)) {

            // $_SESSION['client']['cart']['order_buy'][$product_id] = array(
                //     'product_id_order' => $product_id,
                //     'number_order_bonus' => $_GET['num_order_bonus'],
            // );
            // dd($_SESSION['client']['cart']['order_buy'][$product_id]);
            // dd(Cart::Content());
            // dd("/them-san-pham/{$slug}");
            // if (!empty($_SESSION['client']['cart']['order_buy'][$product_id])) {
            // }
        // }

        $info_product = (object)$info_product;
        if(!empty($list_product_same_cat)) {
            foreach($list_product_same_cat as &$item){
                $item = (object)$item;
            }
        }

        return view("client.product.index", compact("info_product", "list_product_same_cat"));
    }


    function add(Request $request) {
        $product = get_product($request -> slug);
        $id = get_product_id($request -> slug);
        $qty = $request -> num_order_bonus;
        Cart::add([
            "id" => $id,
            'name' => $product->product_name,
            'qty' => $qty,
            'price' => $product->price_new,
            'options' => [
                "product_code" => $product->product_code,
                "product_name" => $product->product_name,
                "slug" => $product->slug,
                "product_desc" => $product->product_desc,
                "product_detail" => $product->product_detail,
                "product_status" => $product->product_status,
                "price_old" => $product->price_old,
                "price_new" => $product->price_new,
                "qty_sold" => $product->qty_sold,
                "qty_remain" => $product->qty_remain,
                "sold_most" => $product->sold_most,
                "product_cat_id" => $product->product_cat_id,
                'product_thumb' => get_product_main_thumb($product->id),
            ],
        ]);

        return redirect("gio-hang.html")->with("status", "Đã thêm {$qty} sản phẩm có tên {$product->product_name} vào giỏ hàng thành công!");
    }
}

function get_info_product($slug)
{
    // $sql = db_fetch_row("SELECT * FROM `tbl_products` WHERE `slug` = '" . $slug . "' AND `product_status` = 'licensed'");
    $sql = Product::where("slug", $slug)
        ->where("product_status", "licensed")
        ->first();
    $sql = json_decode(json_encode($sql), true);
    if (!empty($sql)) {
        // $sql['url_add_cart'] = "?mod=order&action=add&product_id=" . $sql['id'];
        $sql['url_add_cart'] =  url_add_cart($sql["slug"]);
        return $sql;
    }

    return false;
}

function get_list_product_same_cat($product_cat_id)
{
    $sql = Product::where("product_cat_id", $product_cat_id)
        ->where("product_status", "licensed")
        ->get();
    $sql = json_decode(json_encode($sql), true);
    // Route::get('/san-pham/{name_cat}/{slug}', 'ProductController@index')->name('client.product.index');
    foreach ($sql as &$item) {
        // $item['link_detail_product'] = "?mod=product&product_id=" . $item['id'];
        // $item['link_detail_product'] = get_slug_product_same_cat($item['id']);
        $item['link_detail_product'] =  link_detail_product($item["id"]);
        // $item['url_add_cart'] = "?mod=order&action=add&product_id=" . $item['id'];
        $item['url_add_cart'] =  url_add_cart($item["slug"]);
        // $item['url_buy_now'] = "?mod=order&action=buyNow&product_id=" . $item['id'];
        $item['url_buy_now'] =  url_buy_now($item["slug"]);
    }
    if (!empty($sql)) {
        return $sql;
    }

    return false;
}


function update_sold_most()
{
    // SET AGAIN PRODUCT NO SOLD MOST

    // $data_1 = array(
    //     'sold_most' => 0,
    // );
    // $list_sold_most_real_time = db_fetch_array("SELECT * FROM `tbl_products` WHERE `sold_most` = '1'" . " AND `product_status` = 'licensed'");

    $list_sold_most_real_time = Product::where("sold_most", "1")
        ->where("product_status", "licensed")
        ->get();
    $list_sold_most_real_time = json_decode(json_encode($list_sold_most_real_time), true);

    $cnt_update_agin = 0;
    foreach ($list_sold_most_real_time as $sold_most_real_time) {
        $id_updated = Product::where("id", $sold_most_real_time['id'])
            ->where("product_status", "licensed")
            ->update(
                ["sold_most" => "0"]
            );

        // if (db_update('tbl_products', $data_1, "`product_id` = " . $sold_most_real_time['product_id']) > 0) {
        //     $cnt_update_agin++;
        // }

        if ($id_updated > 0) {
            $cnt_update_agin++;
        }

    }

    // SET  PRODUCT  SOLD MOST
    // $list_sold_most = db_fetch_array("SELECT * FROM `tbl_products` WHERE `product_status` = 'licensed' ORDER BY `qty_sold` DESC LIMIT 8");
    $list_sold_most = Product::where("product_status", "licensed")
        ->orderByDesc("qty_sold")
        ->limit(8)
        ->get();

    $cnt_update = 0;

    // $data_1 = array(
    //     'sold_most' => 1,
    // );
    foreach ($list_sold_most as $sold_most) {
        $id_sold_most = Product::where("id", $sold_most['id'])
            ->where("product_status", "licensed")
            ->update(
                ["sold_most" => "1"]
            );

        //    if (db_update('tbl_products', $data_1, "`product_id` = " . $sold_most['product_id']) > 0) {
        //     $cnt_update++;
        // }

        if ($id_sold_most > 0) {
            $cnt_update++;
        }

    }
    if ($cnt_update >= 8) {
        return true;
    }

    return false;
}

// function get_list_product_by_kw($key_word)
// {

//     if (empty($key_word)) {
//         $sql = db_fetch_array("SELECT * FROM `tbl_products` WHERE `product_status` = 'licensed'");
//     } else {
//         $where = "`product_name` LIKE  N'%{$key_word}%' OR `product_cat_title` LIKE N'%{$key_word}%' ";

//         $sql = db_fetch_array("SELECT `tbl_products`.`product_id`,  `tbl_products`.`product_code`, `tbl_products`.`product_name`, `tbl_products`.`slug`,
//         `tbl_products`.`price_old`, `tbl_products`.`price_new`, `tbl_products`.`qty_sold`, `tbl_products`.`qty_remain`, `tbl_products`.`product_detail`,
//         `tbl_products`.`product_desc`, `tbl_products`.`brand_name`, `tbl_products`.`create_by`, `tbl_products`.`create_date`, `tbl_products`.`edit_by`,
//         `tbl_products`.`edit_date`, `tbl_products`.`product_status`, `tbl_products`.`sold_most`, `tbl_product_cat`.`product_cat_title`
//          FROM `tbl_products`  INNER JOIN `tbl_product_cat` ON `tbl_products`.`product_cat_id` = `tbl_product_cat`.`product_cat_id` WHERE `product_status` = 'licensed' AND `product_cat_status` = 'licensed' AND {$where}");
//     }
//     if (!empty($sql)) {
//         foreach ($sql as &$item) {
//             $item['link_detail_product'] = "?mod=product&product_id=" . $item['product_id'];
//             $item['url_add_cart'] = "?mod=order&action=add&product_id=" . $item['product_id'];
//             $item['url_buy_now'] = "?mod=order&action=buyNow&product_id=" . $item['product_id'];
//         }
//         return $sql;
//     }
//     return false;
// }
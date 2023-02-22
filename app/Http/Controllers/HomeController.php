<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function __construct()
    {
        update_sold_most();
    }

    public function index()
    {
        $list_slider = get_list_slider();
        $list_product_sold_most = get_list_product_sold_most();
        $list_phone_demo = get_list_product_demo("Điện thoại");
        $list_laptop_demo = get_list_product_demo("Laptop");

        return view('client.home.index', compact("list_slider", "list_product_sold_most", "list_phone_demo", "list_laptop_demo"));
    }

    public function search(Request $requests)
    {
        $key_word = $requests->input("key_word");
        $list_product_search = get_list_product_by_kw($key_word);
        // dd($list_product_search);
        return view("client.home.search", compact("list_product_search"));
    }
}

// ======================================= MODEL FUNCTION ==========================================
function get_list_slider()
{
    $sql = DB::table("sliders")
        ->where("slider_status", "licensed")
        ->orderBy("order")
        ->orderByDesc("created_at")
        ->get();
    if (!empty($sql)) {
        return $sql;
    }

    return false;
}

function get_product_cat_id_by_title($title = "")
{
    $sql = DB::table("product_cats")
        ->where("product_cat_status", "licensed")
        ->where("product_cat_title", "LIKE", $title)
        ->select("id")
        ->first();

    if (!empty($sql)) {
        return $sql->id;
    }
    return false;
}

function get_list_cat_id($data, $parent_id)
{
    $list_cat = "";
    $list_cat_temp = "";
    foreach ($data as $item) {
        if (!empty($item)) {
            if ($item['cat_parent_id'] == $parent_id) {
                $list_cat .= ", {$item['id']}";
                $list_cat_temp = get_list_cat_id($data, $item['id']);
                if (!empty($list_cat_temp)) {
                    $list_cat .= $list_cat_temp;
                }
            }
        }
    }
    return $list_cat;
}

function get_list_product_demo($product_cat_title)
{
    $sql_total = DB::table("product_cats")
        ->where("product_cat_status", "licensed")
        ->get();
    // Swap oject -> array
    $sql_total_1 = json_decode(json_encode($sql_total), true);

    $parent_id = get_product_cat_id_by_title($product_cat_title);
    $list_cat_id = $parent_id;
    $list_cat_id .= get_list_cat_id($sql_total_1, $parent_id);
    if (!empty($list_cat_id)) {
        $list_cat_id = explode(", ", $list_cat_id);
        $sql = DB::table("products")
            ->where("product_status", "licensed")
            ->whereIn("product_cat_id", $list_cat_id)
            ->orderBy("price_new", "DESC")
            ->limit(8)
            ->get();
    } else {
        $sql = DB::table("products")
            ->where("product_status", "licensed")
            ->orderBy("price_new", "DESC")
            ->limit(8)
            ->get();

    }
    if (!empty($sql)) {
        foreach ($sql as &$item) {
            $item->link_detail_product = link_detail_product($item->id);
            $item->url_add_cart = url_add_cart($item->slug);
            $item->url_buy_now = url_buy_now($item->slug);
        }
        return $sql;
    }
    return false;
}

function get_list_product_by_kw($key_word)
{
    if (empty($key_word)) {
        $sql = Product::where("product_status", "licensed")
            ->get();
    } else {
        $sql = Product::searchNav()-> get();
        foreach ($sql as &$item) {
            $item->link_detail_product = link_detail_product($item->id);
            $item->url_add_cart = url_add_cart($item->slug);
            $item->url_buy_now = url_buy_now($item->slug);
        }
    }
    return $sql;

    return false;
}

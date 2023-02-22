<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class ProductCatController extends Controller
{
    //
    public function __contruct()
    {

    }

    public function index()
    {
        $slug = isset(Request()->slug) ? Request()->slug : '';
        $total_list_product_cat = get_total_list_product_cat($slug);
        $num_total = get_num_total_product();
        // ĐẾM SỐ SẢN PHẨM HIỆN THỜI
        $cnt_product = 0;
        if (!empty($total_list_product_cat) && is_array($total_list_product_cat)) {
            foreach ($total_list_product_cat as $product_cat) {
                $product_cat_id = $product_cat['id'];
                $list_product = get_list_product($product_cat_id);
                if (!empty($list_product) && is_array($list_product)) {
                    foreach ($list_product as $product) {
                        $cnt_product++;
                    }
                }
            }
        }
        if (!empty($total_list_product_cat)) {
            foreach ($total_list_product_cat as &$item) {
                $item = (object) ($item);
            }
        }
        // LOC
        // if (isset($_POST['btn_filter_product'])) {
        //     $error = array();
        //     if (empty($_POST['filter_product'])) {
        //         $error['filter_product'] = "Bạn chưa chọn phương thức lọc";
        //     } else {
        //         $type_filter = $_POST['filter_product'];
        //         redirect_to("loc-san-pham-{$product_cat_id}-{$type_filter}");
        //     }

        // }

        return view("client.product.cat.index", compact("total_list_product_cat", "cnt_product", "num_total"));
    }

}

// function filterAction()
// {
//     global $error, $type_filter;
//     $product_cat_id = isset($_GET['product_cat_id']) ? $_GET['product_cat_id'] : "";
//     $product_cat_title = get_info_product_cat($product_cat_id, 'product_cat_title');
//     $type_filter = isset($_GET['type_filter']) ? $_GET['type_filter'] : "";
//     $list_product = get_list_product($product_cat_id);
//     if ($type_filter == "product_name_asc")
//         $list_product_filter = sort_array_by_field($list_product, 'product_name');
//     else if ($type_filter == "product_name_desc")
//         $list_product_filter = sort_array_by_field($list_product, 'product_name', 1);
//     else if ($type_filter == "product_price_asc")
//         $list_product_filter = sort_array_by_field($list_product, 'price_new');
//     else
//         $list_product_filter = sort_array_by_field($list_product, 'price_new', 1);

//     $list_product_filter_nempty = array();
//     if (!empty($list_product_filter) && is_array($list_product_filter)) {
//         foreach ($list_product_filter as $item) {
//             if (empty($item))
//                 unset($item);
//             else
//                 $list_product_filter_nempty[] = $item;
//         }
//     }

//     $cnt_product = count($list_product_filter_nempty);
//     $num_total = get_num_total_product();

//     if (isset($_POST['btn_filter_product_more'])) {
//         $error = array();
//         if (empty($_POST['filter_product_more'])) {
//             $error['filter_product_more'] = "Bạn chưa chọn phương thức lọc";
//         }else {
//             $type_filter = $_POST['filter_product_more'];
//             redirect_to("loc-san-pham-{$product_cat_id}-{$type_filter}");
//         }
//     }

//     $data['list_product'] = $list_product_filter_nempty;
//     $data['product_cat_title'] = $product_cat_title;
//     $data['cnt_product'] = $cnt_product;
//     $data['num_total'] = $num_total;
//     load_view('filter_cat', $data);
// }





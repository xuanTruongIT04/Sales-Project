<?php
// ========================= FUNCTION MODEL ===========================
use App\Product;
use App\ProductCat;
if (!function_exists('get_product_cat')) {
    function get_product_cat($product_cat_id)
    {
        $sql = ProductCat::find($product_cat_id)
            ->where("product_cat_status", "licensed")
            ->get();
        if (!empty($sql)) {
            return $sql;
        }

        return false;
    }
}

if (!function_exists('get_info_product_cat')) {
    function get_info_product_cat($product_cat_id, $field = "")
    {
        if (empty($field)) {
            $sql = ProductCat::find($product_cat_id)
                ->where("product_cat_status", "licensed")
                ->get();
            if (!empty($sql)) {
                return $sql;
            }

        } else {
            $sql = ProductCat::find($product_cat_id)
                ->where("product_cat_status", "licensed")
                ->select($field)
                ->first();
            if (!empty($sql)) {
                return $sql[$field];
            }

        }

        return false;
    }
}

if (!function_exists('get_info_product')) {
    function get_info_product($product_cat_id, $str)
    {        if(!empty($str)) {
        $string = trim($str, ", ");
        $array = explode( ", ", $string);
    }
        if (!empty($product_cat_id)) {
            $sql = Product::whereIn("product_cat_id", $array)
                ->where("product_status", "licensed")
                ->get();
            $sql = json_decode(json_encode($sql), true);
            if (!empty($sql)) {
                foreach ($sql as &$item) {
                    // $item['url_add_cart'] = "?mod=order&action=add&product_id=" . $item['id'];
                    $item['url_add_cart'] =  url_add_cart($item["slug"]);
                    $item['url_buy_now'] =  url_buy_now($item["slug"]);
                }
                return $sql;
            }
        }
        return false;
    }
}

if (!function_exists('get_info_product_highlight')) {
    function get_info_product_highlight($product_cat_id, $str)
    {
        if(!empty($str)) {
            $array = explode( ", ", $str);
        }
        if (!empty($product_cat_id)) {
            $sql = Product::select("*")
                ->whereIn("product_cat_id", $array)
                ->where("product_status", "licensed")
                ->orderByDesc("qty_sold")
                ->limit(8)
                ->get();
            $sql = json_decode(json_encode($sql), true);
            if (!empty($sql)) {
                foreach ($sql as &$item) {
                    $item['url_add_cart'] =  url_add_cart($item["slug"]);
                }
                return $sql;
            }
        }
        return false;
    }
}
if (!function_exists('get_list_cat_id')) {
    function get_list_cat_id($data, $parent_id)
    {
        $list_cat = "";
        $list_cat_temp = "";
        if (!empty($data) && is_array($data)) {
            foreach ($data as $item) {
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
}

if (!function_exists('get_parent_cat_id')) {
    function get_parent_cat_id($product_cat_id)
    {
        $sql = ProductCat::find($product_cat_id)
            ->where("product_cat_status", "licensed")
            ->select("cat_parent_id")
            ->get();
        if (!empty($sql)) {
            return $sql->cat_parent_id;
        }

        return false;
    }
}

if (!function_exists('get_list_product')) {
    function get_list_product($product_cat_id)
    {
        $list_product_temp = array();
        $list_product_cat = ProductCat::where("product_cat_status", "licensed")
            ->get();
        $list_product_cat = json_decode(json_encode($list_product_cat), true);
        $string = $product_cat_id;
        $string .= get_list_cat_id($list_product_cat, $product_cat_id);
        $list_product_temp = get_info_product($product_cat_id, $string);
        if (!empty($list_product_temp) && is_array($list_product_temp)) {
            foreach ($list_product_temp as &$item) {
                $item['link_detail_product'] =  link_detail_product($item["id"]);
                $item['url_add_cart'] =  url_add_cart($item["slug"]);
                $item['url_buy_now'] =  url_buy_now($item["slug"]);
            }
        }
        return $list_product_temp;
    }
}

if (!function_exists('get_list_product_highlight')) {
    function get_list_product_highlight($product_cat_id)
    {
        $list_product_temp = array();
        $list_product_cat = ProductCat::where("product_cat_status", "licensed")
                            ->get();
        $list_product_cat = json_decode(json_encode($list_product_cat), true);

        $string = $product_cat_id;
        $string .= get_list_cat_id($list_product_cat, $product_cat_id);
        $list_product_temp = get_info_product_highlight($product_cat_id, $string);
        if (!empty($list_product_temp) && is_array($list_product_temp)) {
            foreach ($list_product_temp as &$item) {
                $item['link_detail_product'] =  link_detail_product($item["id"]);
                $item['url_add_cart'] =  url_add_cart($item["slug"]);
                $item['url_buy_now'] =  url_buy_now($item["slug"]);
                $item = (object)$item;
            }
        }
        return $list_product_temp;
    }
}

if (!function_exists('get_num_total_product')) {
    function get_num_total_product()
    {
        $sql = Product::where("product_status", "licensed");
        $sql = json_decode(json_encode($sql), true);
        if (!empty($sql)) {
            return count($sql);
        }

        return false;
    }
}

if (!function_exists('get_total_list_product_cat')) {
    function get_total_list_product_cat($slug = "")
    {
        if (!empty($slug)) {
            $sql = ProductCat::where("product_cat_status", "licensed")
                ->where('slug', $slug)
                ->get();
        } else {
            $sql = ProductCat::where("product_cat_status", "licensed")
                ->get();    
        }
        $sql = json_decode(json_encode($sql), true);
        $list_product_cat_temp = array();
        if (!empty($sql)) {
            foreach ($sql as $item) {
                if ($slug == "") {
                    if ($item['cat_parent_id'] == -1) {
                        $list_product_cat_temp[] = $item;
                    }
                } else {
                    $list_product_cat_temp[] = $item;
                }
            }
        }
        if (!empty($list_product_cat_temp)) {
            return $list_product_cat_temp;
        }
        return false;
    }
}

if (!function_exists('get_total_list_product')) {

    function get_total_list_product()
    {
        $sql = Product::where("product_status", "licensed")
            ->get();
        $sql = json_decode(json_encode($sql), true);
        if (!empty($sql)) {
            return $sql;
        }

        return false;
    }
}
if (!function_exists('add_product_in_session')) {
    function add_product_in_session($product)
    {
        $SESSION['client']['product']['list_product_filter'][] = $product;
    }
}
if (!function_exists('sort_array_by_field')) {
    function sort_array_by_field($list_product_filter = array(), $field = "", $type = 0)
    {

        if ($type == 0) {
            $temp = array();
            for ($i = 0; $i < count($list_product_filter) - 1; $i++) {
                for ($j = $i + 1; $j < count($list_product_filter); $j++) {
                    if (!empty($list_product_filter[$i]) && !empty($list_product_filter[$j] && $type == 0)) {
                        if (!is_numeric($list_product_filter[$i][$field]) && strcmp($list_product_filter[$i][$field], $list_product_filter[$j][$field]) > 0 || is_numeric($list_product_filter[$i][$field]) && $list_product_filter[$i][$field] > $list_product_filter[$j][$field]) {
                            $temp = $list_product_filter[$i];
                            $list_product_filter[$i] = $list_product_filter[$j];
                            $list_product_filter[$j] = $temp;
                        }
                    }
                }
            }
        } else {
            for ($i = 0; $i < count($list_product_filter) - 1; $i++) {
                for ($j = $i + 1; $j < count($list_product_filter); $j++) {
                    if (!empty($list_product_filter[$i]) && !empty($list_product_filter[$j] && $type == 1)) {
                        if (!is_numeric($list_product_filter[$i][$field]) && strcmp($list_product_filter[$i][$field], $list_product_filter[$j][$field]) < 0 || is_numeric($list_product_filter[$i][$field]) && $list_product_filter[$i][$field] < $list_product_filter[$j][$field]) {
                            $temp = $list_product_filter[$i];
                            $list_product_filter[$i] = $list_product_filter[$j];
                            $list_product_filter[$j] = $temp;
                        }
                    }
                }
            }
        }
        return $list_product_filter;
    }
}
if (!function_exists('set_slug_product')) {
    function set_slug_product($slug)
    {
        return "san-pham/" . $slug;
    }
}
if (!function_exists('get_product_cat_id_by_slug')) {
    function get_product_cat_id_by_slug($slug)
    {
        $sql = ProductCat::where("product_cat_status", "licensed")
            ->where("slug", $slug)
            ->select("id")
            ->first();
        $sql = json_decode(json_encode($sql), true);
        if (!empty($sql)) {
            return $sql['id'];
        }

        return 0;
    }
}

if (!function_exists('paging_list_product')) {
function paging_list_product($list_products = array(), $start = 1, $num_per_page = 20)
{
    if (!empty($list_products)) {
        $list_u_paging = array();
        foreach ($list_products as $key => $user) {
            if ($key >= $start && $key < $start + $num_per_page) {
                $list_u_paging[] = $user;
            }
        }
        foreach($list_u_paging as &$item) {
            $item = (object)$item;
        }
        return $list_u_paging;
    }
    return FALSE;
}
}
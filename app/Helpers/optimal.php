
<?php


if (!function_exists('get_list_cat_id_product')) {
    function get_list_cat_id_product($data, $parent_id)
    {
        $list_cat = "";
        $list_cat_temp = "";
        if (!empty($data) && is_array($data)) {
            foreach ($data as $item) {
                if ($item['cat_parent_id'] == $parent_id) {
                    $list_cat .= "{$item['product_cat_id']}, ";
                    $list_cat_temp = get_list_cat_id_product($data, $item['product_cat_id']);
                    if (!empty($list_cat_temp)) {
                        $list_cat .= $list_cat_temp;
                    }
                }
            }
        }
        return $list_cat;
    }
}

if (!function_exists('get_list_cat_id_post')) {
    function get_list_cat_id_post($data, $parent_id)
    {
        $list_cat = "";
        $list_cat_temp = "";
        if (!empty($data) && is_array($data)) {
            foreach ($data as $item) {
                if ($item['cat_parent_id'] == $parent_id) {
                    $list_cat .= "{$item['post_cat_id']}, ";
                    $list_cat_temp = get_list_cat_id_post($data, $item['post_cat_id']);
                    if (!empty($list_cat_temp)) {
                        $list_cat .= $list_cat_temp;
                    }
                }
            }
        }
        return $list_cat;
    }
}


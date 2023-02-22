<?php
use Illuminate\Support\Facades\DB;

if (!function_exists('field_status_user')) {
    function field_status_user($status)
    {
        if ($status == 'licensed') {
            return '<span class="badge badge-success">Đã cấp quyền</span>';
        } else if ($status == "pending") {
            return '<span class="badge badge-primary">Chờ xét duyệt</span>';
        }

        return '<span class="badge badge-dark">Trong thùng rác</span>';
    }
}

if (!function_exists('field_status_user_vi')) {
    function field_status_user_vi($status)
    {
        if ($status == "Chờ xét duyệt") {
            return 'pending';
        } else if ($status == "Đã cấp quyền") {
            return 'licensed';
        }

    }
}

if (!function_exists('field_status')) {
    function field_status($status)
    {
        if ($status == 'licensed') {
            return '<span class="badge badge-success">Đã đăng</span>';
        } else if ($status == 'pending') {
            return '<span class="badge badge-primary">Chờ xét duyệt</span>';
        } else if ($status == 'trashed') {
            return '<span class="badge badge-dark">Trong thùng rác</span>';
        }

    }
}

if (!function_exists('field_status_vi')) {
    function field_status_vi($status)
    {
        if ($status == 'Đã đăng') {
            return 'licensed';
        } else if ($status == 'Chờ xét duyệt') {
            return 'pending';
        } else if ($status == 'Trong thùng rác') {
            return 'trashed';
        }

    }
}

if (!function_exists('field_status_order')) {
    function field_status_order($status)
    {
        if ($status == 'delivery_successful') {
            return '<span class="badge badge-success">Thành công</span>';
        } else if ($status == 'pending') {
            return '<span class="badge badge-primary">Chờ xét duyệt</span>';
        } else if ($status == 'shipping') {
            return '<span class="badge badge-warning">Đang vận chuyển</span>';
        } else if ($status == 'trashed') {
            return '<span class="badge badge-dark">Vô hiệu hoá</span>';
        }

    }
}

if (!function_exists('field_status_order_vi')) {
    function field_status_order_vi($status)
    {
        if ($status == 'Thành công') {
            return 'delivery_successful';
        } else if ($status == 'Chờ xét duyệt') {
            return 'pending';
        } else if ($status == 'Đang vận chuyển') {
            return 'shipping';
        }

    }
}

if (!function_exists('field_thumb')) {
    function field_thumb($thumb)
    {
        if (!empty($thumb)) {
            echo $thumb;
        } else {
            echo "public/images/img-thumb.png";
        }

    }
}

if (!function_exists('field_level')) {
    function field_level($level)
    {
        if ($level >= 0) {
            echo $level;
        } else {
            echo '<span style="color: #008900db;">Chờ phân quyền</span>';
        }

    }
}

if (!function_exists('template_update_status')) {
    function template_update_status($status)
    {
        $str = "<select name='status' id='status' class='form-control'>";

        $data = array(
            'licensed' => 'Đã đăng',
            'pending' => 'Chờ xét duyệt',
            'trashed' => 'Trong thùng rác',
        );

        foreach ($data as $item => $ele) {
            $sel = "";
            if ($status == $item) {
                $sel = "selected='selected'";
            }

            $str .= "<option value=" . $item . " " . $sel . " >" . $ele . "</option>";
        }

        $str .= "</select>";

        return $str;
    }
}

if (!function_exists('template_update_status_checked')) {
    function template_update_status_checked($status, $id)
    {
        $str = "<select name=\"status_{$id}\" id=\"status_{$id}\">";

        $data = array(
            'licensed' => 'Đã đăng',
            'pending' => 'Chờ xét duyệt',
            'trashed' => 'Trong thùng rác',
        );

        foreach ($data as $item => $ele) {
            $sel = "";
            if ($status == $item) {
                $sel = "selected='selected'";
            }

            $str .= "<option value=" . $item . " " . $sel . " >" . $ele . "</option>";
        }

        $str .= "</select>";

        return $str;
    }
}

// Trạng thái người dùng
if (!function_exists('template_update_status_user')) {
    function template_update_status_user($status)
    {
        $str = "<select name='status' id='status' class='form-control'>";

        $data = array(
            'licensed' => 'Đã cấp quyền',
            'pending' => 'Chờ xét duyệt',
            'trashed' => 'Trong thùng rác',
        );

        foreach ($data as $item => $ele) {
            $sel = "";
            if ($status == $item) {
                $sel = "selected='selected'";
            }

            $str .= "<option value=" . $item . " " . $sel . " >" . $ele . "</option>";
        }

        $str .= "</select>";

        return $str;
    }
}

if (!function_exists('template_update_status_user_checked')) {
    function template_update_status_user_checked($status, $id)
    {
        $str = "<select name=\"status_{$id}\" id='status'>";

        $data = array(
            'licensed' => 'Đã cấp quyền',
            'pending' => 'Chờ xét duyệt',
            'trashed' => 'Trong thùng rác',
        );

        foreach ($data as $item => $ele) {
            $sel = "";
            if ($status == $item) {
                $sel = "selected='selected'";
            }

            $str .= "<option value=" . $item . " " . $sel . " >" . $ele . "</option>";
        }

        $str .= "</select>";

        return $str;
    }
}

if (!function_exists('template_field_empty')) {
    function template_field_empty()
    {
        return "<span style='color: #333;'>#</span>";
    }
}

if (!function_exists('template_get_field_status')) {
    function template_get_field_status($array_available, $name_field_fixed, $field_status = "")
    {
        $cnt_interation_field_licensed = 0;
        $cnt_interation_field_pending = 0;
        $cnt_interation_field_trashed = 0;
        $cnt_interation_field_shipping = 0;
        $cnt_interation_field_delivery_successful = 0;
        if (!empty($field_status)) {
            foreach ($array_available as $item) {
                if (array_key_exists($name_field_fixed, $item)) {
                    if ($item[$name_field_fixed] == 'licensed') {
                        $cnt_interation_field_licensed++;
                    } else if ($item[$name_field_fixed] == 'pending') {
                        $cnt_interation_field_pending++;
                    } else if ($item[$name_field_fixed] == 'trashed') {
                        $cnt_interation_field_trashed++;
                    } else if ($item[$name_field_fixed] == 'shipping') {
                        $cnt_interation_field_shipping++;
                    } else if ($item[$name_field_fixed] == 'delivery_successful') {
                        $cnt_interation_field_delivery_successful++;
                    }
                }
            }
            if ($field_status == 'licensed') {
                return $cnt_interation_field_licensed;
            } else if ($field_status == 'pending') {
                return $cnt_interation_field_pending;
            } else if ($field_status == 'trashed') {
                return $cnt_interation_field_trashed;
            } else if ($field_status == 'shipping') {
                return $cnt_interation_field_shipping;
            } else if ($field_status == 'delivery_successful') {
                return $cnt_interation_field_delivery_successful;
            }

        } else {
            $cnt_interation_field_all = count($array_available);
            return $cnt_interation_field_all;
        }
    }
}

// Order
if (!function_exists('order_status')) {
    function show_order_status($order_status)
    {
        $str = "<select class='form-control w-17' name='order_status' id='status'>";

        $data = array(
            'delivery_successful' => 'Thành công',
            'shipping' => 'Đang vận chuyển',
            'pending' => 'Chờ xét duyệt',
        );

        foreach ($data as $item => $ele) {
            $sel = "";
            if ($order_status == $item) {
                $sel = "selected='selected'";
            }

            $str .= "<option value=" . $item . " " . $sel . " >" . $ele . "</option>";
        }

        $str .= "</select>";

        return $str;
    }
}
if (!function_exists('show_payment_method')) {
    function show_payment_method($method)
    {
        $str = "<select class='form-control w-17' name='payment_method' id='payment-method'>";

        $data = array(
            'payment_home' => 'Thanh toán tại nhà',
            'payment_store' => 'Thanh toán tại cửa hàng',
        );

        foreach ($data as $item => $ele) {
            $sel = "";
            if ($method == $item) {
                $sel = "selected='selected'";
            }
            $str .= "<option value=" . $item . " " . $sel . " >" . $ele . "</option>";
        }

        $str .= "</select>";

        return $str;
    }
}

// =========================================== SIDEBAR ===================================================
if (!function_exists('get_list_product_cat_children')) {
    function get_list_product_cat_children($cat_parent_id)
    {
        $sql = DB::table("product_cats")
            ->where("product_cat_status", "licensed")
            ->where("cat_parent_id", $cat_parent_id)
            ->select("id", "product_cat_title")
            ->get();
        $list_product_cat_children = array();
        if (!empty($sql)) {
            foreach ($sql as $item) {
                $list_product_cat_children[] = $item;
            }
        }
        if (!empty($list_product_cat_children)) {
            return $list_product_cat_children;
        }

        return false;
    }
}
if (!function_exists('template_get_sidebar')) {
    function template_get_sidebar($list_product_cat_parent = array(), $base_url = "?mod=product&controller=list_cat")
    {
        $str_sidebar_temp = "";
        $list_product_cat_children = array();
        $str_sidebar = "";
        if (!empty($list_product_cat_parent)) {
            $cnt_cat_parent = 0;
            foreach ($list_product_cat_parent as $item) {
                if (isset($item->cat_parent_id)) {
                    $cnt_cat_parent++;
                }
            }
            if ($cnt_cat_parent > 0) {
                $str_sidebar = "<ul class=\"list-item\">";
            } else {
                $str_sidebar = "<ul class=\"sub-menu\">";
            }
        }
        if (!empty($list_product_cat_parent)) {
            foreach ($list_product_cat_parent as $product_cat) {
                if (!empty($product_cat->id)) {
                    $list_product_cat_children = get_list_product_cat_children($product_cat->id);
                    $str_sidebar_temp = template_get_sidebar($list_product_cat_children);
                    $slug_product_cat = set_slug_product_cat(get_slug_product_cat($product_cat->id));
                    $str_sidebar .= "<li><a href=\"{$slug_product_cat}\" title=\"{$product_cat->product_cat_title}\">{$product_cat->product_cat_title}</a>";
                    if (!empty($str_sidebar_temp)) {
                        $str_sidebar .= $str_sidebar_temp;
                    }

                    $str_sidebar .= "</li>";
                }
            }
        }

        if (!empty($list_product_cat_parent)) {
            $str_sidebar .= "</ul>";
        }

        $cnt_cat_parent = 0;
        return $str_sidebar;
    }
}

if (!function_exists('template_no_product_found')) {

    function template_no_product_found()
    {
        $str_npf = "<div class='no-product-found'>
        <img src='https://www.foodworldmd.com/templates/default-new/images/no-product-found.png' class='no-product-found-icon'>
        <div class='no-product-found-title'>Tiếc quá, không tìm thấy kết quả nào</div>
        <div class='no-product-found-hint'>Hãy thử sử dụng các từ khóa chung hơn</div>
    </div>";

        return $str_npf;
    }
}
if (!function_exists('template_empty_cart')) {

    function template_empty_cart()
    {
        $str_npf = "<div class='empty-cart'>
        <img src='https://www.beaba.com/on/demandware.static/Sites-BEABA-EMEA-Site/-/default/dw91747f81/images/empty_cart.png' class='empty-cart-icon'>
        <h2 class='empty-cart-title'>Không có sản phẩm nào trong giỏ hàng</h2>
        <div class='empty-cart-hint'>Hãy quay trở lại cửa hàng và lựa chọn cho mình những sản phẩm tuyệt vờI nhé!</div>
    </div>";

        return $str_npf;
    }
}
if (!function_exists('template_empty_product')) {

    function template_empty_product()
    {
        $str_npf = "<div class='empty-product'>
        <img src='https://chryslergroup.navigation.com/static/WFS/Shop-Site/-/Shop/en_US/Product%20Not%20Found.png' class='empty-product-icon'>
        <h2 class='empty-product-title'>Không tồn tại sản phẩm nào trong danh sách</h2>
        <p class='empty-product-hint'>Hãy quay trở lại trang chủ để tiếp tục tham khảo các sản phẩm khác nhé </p>
        <a href='?' class='return-home'>Trở về trang chủ</a>
    </div>";

        return $str_npf;
    }
}
if (!function_exists('template_empty_page')) {

    function template_empty_page()
    {
        $str_npf = "<div class='empty-product'>
        <img src='https://www.dpmarketingcommunications.com/wp-content/uploads/2016/11/404-Page-Featured-Image.png' class='empty-product-icon'>
        <h2 class='empty-product-title'>Không tồn tại trang này</h2>
        <p class='empty-product-hint'>Hãy quay trở lại trang chủ để tiếp tục tham khảo các sản phẩm khác nhé </p>
        <a href='' class='return-home'>Trở về trang chủ</a>
    </div>";

        return $str_npf;
    }
}

function get_paging($page, $num_page, $base_url)
{
    $str_paging = "<ul id=\"list-paging\" >";
    if ($page >= 1) {
        $page_prev = $page - 1;
        if($page == 1) {
            $class = "class='no-hover-tag-a'";
            $style = 'style="cursor: not-allowed; color: #CCC;"';
            $str_paging .= "<li><a {$class} {$style} href=\"javascript:void(0)\"><</a></li>";
        }else if($page > 1) {
            $str_paging .= "<li><a href=\"{$base_url}?page={$page_prev}\"><</a></li>";
        }
      
    }


    for ($i = 1; $i <= $num_page; $i++) {
        $active = "";
        if ($i == $page)
            $active = "class = 'active'";
        $str_paging .= "<li {$active}><a href=\"{$base_url}?page={$i}\">{$i}</a></li>";
    }


    if ($page <= $num_page) {
        $page_next = $page + 1;
        if($page == $num_page) {
            $class = "class='no-hover-tag-a'";
            $style = 'style="cursor: not-allowed; color: #CCC;"';
            $str_paging .= "<li><a {$class} {$style} href=\"javascript:void(0)\">></a></li>";
        }else if($page < $num_page) {
            $str_paging .= "<li><a href=\"{$base_url}?page={$page_next}\">></a></li>";
        }
       
    }
    $str_paging .= "</ul>";


    return $str_paging;
}

function get_paging_list_product($page, $num_page, $base_url)
{
    $str_paging = "<ul id=\"list-paging\" >";
    if ($page >= 1) {
        $page_prev = $page - 1;
        if($page == 1) {
            $class = "class='no-hover-tag-a'";
            $style = 'style="cursor: not-allowed; color: #CCC;"';
            $str_paging .= "<li><a {$class} {$style} href=\"javascript:void(0)\"><</a></li>";
        }else if($page > 1) {
            $str_paging .= "<li><a href=\"{$base_url}&page={$page_prev}\"><</a></li>";
        }
      
    }


    for ($i = 1; $i <= $num_page; $i++) {
        $active = "";
        if ($i == $page)
            $active = "class = 'active'";
        $str_paging .= "<li {$active}><a href=\"{$base_url}&page={$i}\">{$i}</a></li>";
    }


    if ($page <= $num_page) {
        $page_next = $page + 1;
        if($page == $num_page) {
            $class = "class='no-hover-tag-a'";
            $style = 'style="cursor: not-allowed; color: #CCC;"';
            $str_paging .= "<li><a {$class} {$style} href=\"javascript:void(0)\">></a></li>";
        }else if($page < $num_page) {
            $str_paging .= "<li><a href=\"{$base_url}&page={$page_next}\">></a></li>";
        }
       
    }
    $str_paging .= "</ul>";


    return $str_paging;
}


function template_status_product($qty_remain) {
    if($qty_remain > 0) {
        return "Còn hàng";
    }else {
        return "Hết hàng";
    }
}

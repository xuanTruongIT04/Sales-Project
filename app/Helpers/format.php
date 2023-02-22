<?php
if (!function_exists('currency_format')) {
    function currency_format($number, $suffix = ' VNĐ')
    {
        if (!empty($number))
            return number_format($number) . $suffix;
        return "<span class='text-muted'>Chưa cập nhật</span>";

    }
}

if (!function_exists('slug_format')) {
    function slug_format($slug, $create_date)
    {
        if (strlen(strstr($slug, date("Ymd", $create_date) . ".html")) > 4) {
            return $slug;
        }
        return $slug . "-" . date("Ymd", $create_date) . ".html";
    }
}

if (!function_exists('code_product_format')) {
    function code_product_format($slug)
    {
        return "ISMART#" . substr(md5($slug), 10, 8);
    }
}

if (!function_exists('code_order_format')) {

    function code_order_format($order_id)
    {
        return "ISMART#" . substr(md5($order_id), 0, 10);
    }
}

function get_words($str, $nWords) {
    if(strlen($str) <= $nWords)
        return $str;
    else {
        $str_temp_1 = explode(" ", $str);
        $str_temp_2 = array();
        for($i = 0; $i < $nWords; $i++) {
            if(isset($str_temp_1[$i]))
                $str_temp_2[] = $str_temp_1[$i];
        }
        $str_or =  implode(" ", $str_temp_2) . " [...]";
        return $str_or;
    }
    
}

function brief_name($str, $nWords) {
    if(strlen($str) <= $nWords)
        return $str;
    else {
        $str_temp_1 = explode(" ", $str);
        $str_temp_2 = array();
        for($i = 0; $i < $nWords; $i++) {
            if(isset($str_temp_1[$i]))
                $str_temp_2[] = $str_temp_1[$i];
        }
        $str_or =  implode(" ", $str_temp_2) . " ...";
        return $str_or;
    }
    
}
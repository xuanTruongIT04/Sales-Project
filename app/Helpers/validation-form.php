<?php
if (!function_exists('is_email')) {
    function is_email($email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            return FALSE;
        return TRUE;
    }
}

if (!function_exists('is_fullname')) {
    function is_fullname($fullname)
    {
        $pattern = '/^[a-zA-Z- ]{1, 40}$/';
        if (preg_match($pattern, $fullname, $matchs))
            return TRUE;
        return FALSE;
    }
}

if (!function_exists('is_username')) {
    function is_username($username)
    {
        $pattern = '/^[A-Za-z0-9_\.]{6,32}$/';
        if (preg_match($pattern, $username, $matchs))
            return TRUE;
        return FALSE;
    }
}

if (!function_exists('is_password')) {
    function is_password($password)
    {
        $pattern = '/^([A-Z]){1}([a-z0-9_\.!@#$%^&*()+]){5,31}$/';
        if (preg_match($pattern, $password, $matchs))
            return TRUE;
        return FALSE;
    }
}

if (!function_exists('is_tel')) {
    function is_tel($tel)
    {
        $pattern = '/^[0-9\-\+]{9,15}$/';
        if (preg_match($pattern, $tel, $matchs))
            return TRUE;
        return FALSE;
    }
}

if (!function_exists('is_title')) {
    function is_title($title)
    {
        $pattern = '/^([\"a-zA-Z0-9ÀÁÂÃÈÉÊÌÍÒÓÔÕÙÚĂĐĨŨƠàáâãèéêìíòóôõùúăđĩũơƯĂẠẢẤẦẨẪẬẮẰẲẴẶẸẺẼỀỀỂưăạảấầẩẫậắằẳẵặẹẻẽếềểỄỆỈỊỌỎỐỒỔỖỘỚỜỞỠỢỤỦỨỪễệỉịọỏốồổỗộớờởỡợụủứừỬỮỰỲỴÝỶỸửữựỳỵỷỹ%()?+_\,.\-\|\/\s\'\"]{2,400})$/';
        if (preg_match($pattern, $title, $match))
            return TRUE;
        return FALSE;
    }
}

if (!function_exists('is_slug')) {
    function is_slug($slug)
    {
        $pattern = '/^[a-z0-9]+(?:-[a-z0-9\.]+)*(.html)?$/';
        if (preg_match($pattern, $slug, $match))
            return TRUE;
        return FALSE;
    }
}

if (!function_exists('form_error')) {
    function form_error($label_field)
    {
        global $error;
        if (!empty($error[$label_field])) return "<p class='error'>{$error[$label_field]}</p>";
    }
}

if (!function_exists('form_alert')) {
    function form_alert($result)
    {
        global $alert;
        if (!empty($alert[$result])) return "<p class='alert'>{$alert[$result]}</p>";
    }
}

if (!function_exists('set_value')) {
    function set_value($label_field)
    {
        global $$label_field;
        if (!empty($$label_field)) return $$label_field;
    }
}

if (!function_exists('set_value_style_level')) {
    function set_value_style_level($label_field_style_level)
    {
        global $$label_field_style_level;
        if (!empty($$label_field_style_level)) return $$label_field_style_level;
        else return '0';
    }
}

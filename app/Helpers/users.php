<?php
if (!function_exists('check_login')) {
    function check_login($username, $password)
    {
        global $conn;
        if (!empty($user))
            return TRUE;
        return FALSE;
    }
}

if (!function_exists('is_login')) {
    function is_login()
    {
        if (!empty($_SESSION['is_login']))
            return true;
        return false;
    }
}

if (!function_exists('user_login')) {
    function user_login()
    {
        if (!empty($_SESSION['user_login']))
            return $_SESSION['user_login'];
        return FALSE;
    }
}

if (!function_exists('infor_user_login')) {
    function infor_user_login($field = 'id')
    {
        global $conn;
        if (!empty($info_user))
            return $info_user[$field];
        return FALSE;
    }
}

if (!function_exists('count_record_search')) {
    function count_record_search($table, $status = "")
    {
        $cnt = 0;
        if (!empty($table)) {
            if (empty($status)) {
                return count($table);
            } else {
                foreach ($table as $item) {
                    if ($item['user_status'] == $status) {
                        $cnt++;
                    }
                }
            }
        }
        return $cnt;
    }
}

if (!function_exists('count_record_filter')) {
    function count_record_filter($filter_name = "")
    {
        $cnt = 0;
        if (empty($filter_name)) {
            $cnt = !empty($_SESSION['admin']['users']['list_user_search']) ? count($_SESSION['admin']['users']['list_user_search']) : 0;
            return $cnt;
        } else {
            if (!empty($_SESSION['admin']['users']['list_user_search'])) {
                foreach ($_SESSION['admin']['users']['list_user_search'] as $item) {
                    if ($item['user_status'] == $filter_name) {
                        $cnt++;
                    }
                }
            }
            return $cnt;
        }
    }
}

<?php
use Illuminate\Support\Facades\DB;

// =============================== MODEL FUNCTION ===============================
if (!function_exists('get_slug')) {
    function get_slug($post_id)
    {
        $sql = DB::table("posts")->select('slug')
            ->where('post_status', 'licensed')
            ->where('id', $post_id)
            ->first();
        if (!empty($sql)) {
            return $sql->slug;
        }

        return false;
    }
}
if (!function_exists('set_slug')) {

    function set_slug($slug)
    {
        return 'bai-viet/' . $slug;
    }
}

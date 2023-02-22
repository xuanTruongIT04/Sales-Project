<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    //
    public function __construct()
    {

    }

    public function index()
    {
        $slug = isset(request() -> slug) ? request() -> slug : "";
        $info_post = get_info_post($slug);
        return view("client.post.index", compact("info_post"));
    }
}

if (!function_exists('get_info_post')) {
    function get_info_post($slug)
    {
        // $info_post = db_fetch_row("SELECT * FROM `tbl_posts` WHERE `slug` = '". $slug ."' AND `post_status` = 'licensed'");
        $info_post = DB::table("posts")
            ->where("slug", $slug)
            ->where("post_status", "licensed")
            ->first();
        if (!empty($info_post)) {
            return $info_post;
        }

        return false;
    }
}

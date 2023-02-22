<?php

namespace App\Http\Controllers;

use App\PostCat;
use App\Post;
use Illuminate\Support\Facades\DB;

class PostCatController extends Controller
{
    //
    public function __construct()
    {

    }

    public function index()
    {
        // PHÂN TRANG
        global $page, $num_page, $start;
        // Trang hiện tại
        $page = isset($_GET['page']) ? (int) ($_GET['page']) : 1;
        // Số bản ghi trên mỗi trang
        $num_per_page = 20;
        $start = ($page - 1) * $num_per_page;

        // Tổng bản ghi
        // $total_row = db_num_rows("SELECT * FROM `tbl_post_cat`");
        $total_row = Post::get()->count();

        // Tổng số trang cần tìm
        $num_page = ceil($total_row / $num_per_page);
        $list_post_cat = get_list_post();



        return view("client.post.cat.index", compact("list_post_cat"));
    }
}

if (!function_exists('get_list_post_cat')) {
    function get_list_post_cat()
    {
        // $sql = db_fetch_array("SELECT `post_cat_title`, `post_title`, `post_id`, `post_desc`, `post_content`, `upload_file_image`,
        //  `post_status`, `tbl_posts`.`create_by`, `tbl_posts`.`create_date`
        //  FROM `tbl_post_cat` INNER JOIN `tbl_posts` ON `tbl_post_cat`.`post_cat_id` = `tbl_posts`.`post_cat_id`
        //  WHERE `post_status` = 'licensed' AND `post_cat_status` = 'licensed'");

        $sql = PostCat::select("post_cat_title", "post_title", "posts.id", "post_desc", "post_desc", "post_content", "posts.image_id", "post_status", "posts.created_at")
            ->join("posts", "id", "=", "posts.id")
            ->where("post_status", "licensed")->where("post_cat_status", "licensed")
            ->orderbyDesc("created_at")
            ->Paginate(20);

        if (!empty($sql)) {
            return $sql;
        }

        return false;
    }
}
if (!function_exists('get_detail_post')) {
    function get_detail_post($post_id)
    {
        return "?mod=post&post_id=" . $post_id;
    }
}

if (!function_exists('get_list_post')) {
    function get_list_post($start = 1, $num_per_page = 20, $where = "")
    {
        if (!empty($where)) {
            $where = " AND {$where}";
        }

        // $result = db_fetch_array("SELECT `post_cat_title`, `post_title`, `post_id`, `post_desc`, `post_content`, `upload_file_image`, `post_status`, `tbl_posts`.`create_by`, `tbl_posts`.`create_date`
        //                       FROM `tbl_post_cat` INNER JOIN `tbl_posts` ON `tbl_post_cat`.`post_cat_id` = `tbl_posts`.`post_cat_id`
        //                       WHERE `post_status` = 'licensed' AND `post_cat_status` = 'licensed' {$where}
        //                       ORDER BY `tbl_posts`.`create_date` DESC
        //                       LIMIT {$start}, {$num_per_page}");

        if (!empty($where)) {
            $result = DB::table("post_cats")
                ->select("post_cat_title", "post_title", "posts.id", "post_desc", "post_desc", "post_content", "posts.image_id", "post_status", "posts.created_at")
                ->join("posts", "post_cats.id", "=", "posts.post_cat_id")
                ->where("post_status", "licensed")->where("post_cat_status", "licensed")
                ->where($where)
                ->orderByDesc("posts.created_at")
                ->limit($start, $num_per_page)
                ->orderbyDesc("created_at")
                ->Paginate(20);
        } else {
            $result = $result = DB::table("post_cats")
                ->select("post_cat_title", "post_title", "posts.id", "post_desc", "post_desc", "post_content", "posts.image_id", "post_status", "posts.created_at")
                ->join("posts", "post_cats.id", "=", "posts.post_cat_id")
                ->where("post_status", "licensed")->where("post_cat_status", "licensed")
                ->orderByDesc("posts.created_at")
                ->limit($start, $num_per_page)
                ->orderbyDesc("created_at")
                ->Paginate(20);
        }
        if (!empty($result)) {
            return $result;
        }
        return false;
    }
}

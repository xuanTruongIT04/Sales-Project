<?php

namespace App\Http\Controllers;

use App\Post;
use App\PostCat;
use Illuminate\Http\Request;

class AdminPostCatController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => "post_cat"]);
            return $next($request);
        });
    }

    function list(Request $requests) {
        $status = !empty(request()->input('status')) ? $requests->input('status') : 'active';
        $list_act = [
            "licensed" => "Đã đăng",
            "pending" => "Chờ xét duyệt",
            "delete" => "Xoá tạm thời",
        ];

        $key_word = "";

        if ($requests->input("key_word")) {
            $key_word = $requests->input("key_word");
        }

        if ($status == "active") {
            $post_cats = PostCat::withoutTrashed()->where("post_cat_title", "LIKE", "%{$key_word}%")->orderBy("level")->orderbyDesc("created_at")->Paginate(20);
        } else if ($status == "licensed") {
            $list_act = [
                "pending" => "Chờ xét duyệt",
                "delete" => "Xoá tạm thời",
            ];
            $post_cats = PostCat::withoutTrashed()->where("post_cat_status", "licensed")->where("post_cat_title", "LIKE", "%{$key_word}%")->orderBy("level")->orderbyDesc("created_at")->Paginate(20);
        } else if ($status == "pending") {
            $list_act = [
                "licensed" => "Đã đăng",
                "delete" => "Xoá tạm thời",
            ];
            $post_cats = PostCat::withoutTrashed()->where("post_cat_status", "pending")->where("post_cat_title", "LIKE", "%{$key_word}%")->orderBy("level")->orderbyDesc("created_at")->Paginate(20);
        } else if ($status == "trashed") {
            $list_act = [
                "restore" => "Khôi phục",
                "delete_permanently" => "Xoá vĩnh viễn",
            ];
            $post_cats = PostCat::onlyTrashed()->where("post_cat_title", "LIKE", "%{$key_word}%")->orderBy("level")->orderbyDesc("created_at")->Paginate(20);
        }

        $count_post_cat = $post_cats->total();
        $cnt_post_cat_active = PostCat::withoutTrashed()->count();
        $cnt_post_cat_licensed = PostCat::withoutTrashed()->where("post_cat_status", "licensed")->count();
        $cnt_post_cat_pending = PostCat::withoutTrashed()->where("post_cat_status", "pending")->count();
        $cnt_post_cat_trashed = PostCat::onlyTrashed()->count();
        $count_post_cat_status = [$cnt_post_cat_active, $cnt_post_cat_licensed, $cnt_post_cat_pending, $cnt_post_cat_trashed];

        // Truyền các role:
        return view("admin.post.cat.list", compact('post_cats', "count_post_cat", "count_post_cat_status", "list_act"));
    }

    public function add()
    {
        $list_post_cat = PostCat::all();
        return view('admin.post.cat.add', compact("list_post_cat"));
    }

    public function store(Request $requests)
    {
        if ($requests->input('btn_add')) {
            $post_cat_title = $requests->input('post_cat_title');

            $requests->validate(
                [
                    'post_cat_title' => ['required', 'string', 'max:255'],
                    'slug' => ['required', 'string', 'max:300'],
                    'level' => ['required', 'integer', 'min:0'],
                    'cat_parent' => ['required', 'integer'],
                ],
                [
                    'required' => ":attribute không được để trống",
                    "max" => [
                        "numeric" => ":attribute không được lớn hơn :max.",
                        "file" => ":attribute không được nhiều hơn :max KB.",
                        "string" => ":attribute không được nhiều hơn :max kí tự.",
                        "array" => ":attribute không được nhiều hơn :max mục.",
                    ],
                    "min" => [
                        "numeric" => ":attribute không được bé hơn :min.",
                        "file" => ":attribute không được ít hơn :min KB.",
                        "string" => ":attribute không được ít hơn :min kí tự.",
                        "array" => ":attribute phải có ít nhất :min mục.",
                    ],
                ],
                [
                    "post_cat_title" => "Tiêu đề danh mục",
                    "slug" => "Đường dẫn thân thiện",
                    "level" => "Cấp độ",
                    "cat_parent" => "Danh mục cha",
                ]
            );

            PostCat::create([
                'post_cat_title' => $requests->input("post_cat_title"),
                'slug' => $requests->input("slug"),
                'level' => $requests->input("level"),
                'cat_parent_id' => $requests->input("cat_parent"),
            ]);

            return redirect("admin/post/cat/list")->with("status", "Đã thêm danh mục bài viết có tiêu đề {$post_cat_title} thành công");
        }
    }

    public function edit($id)
    {
        $post_cat = PostCat::find($id);
        $list_post_cat = PostCat::all();
        return view("admin.post.cat.edit", compact("post_cat", "list_post_cat"));
    }

    public function update(Request $requests, $id)
    {
        if ($requests->input('btn_update')) {
            $post_cat_title = $requests->input("post_cat_title");
            $requests->validate(
                [
                    'post_cat_title' => ['required', 'string', 'max:255'],
                    'slug' => ['required', 'string', 'max:300'],
                    'level' => ['required', 'integer', 'min:0'],
                    'cat_parent' => ['required', 'integer'],
                ],
                [
                    'required' => ":attribute không được để trống",
                    "max" => [
                        "numeric" => ":attribute không được lớn hơn :max.",
                        "file" => ":attribute không được nhiều hơn :max KB.",
                        "string" => ":attribute không được nhiều hơn :max kí tự.",
                        "array" => ":attribute không được nhiều hơn :max mục.",
                    ],
                    "min" => [
                        "numeric" => ":attribute không được bé hơn :min.",
                        "file" => ":attribute không được ít hơn :min KB.",
                        "string" => ":attribute không được ít hơn :min kí tự.",
                        "array" => ":attribute phải có ít nhất :min mục.",
                    ],
                ],
                [
                    "post_cat_title" => "Tiêu đề danh mục",
                    "slug" => "Đường dẫn thân thiện",
                    "level" => "Cấp độ",
                    "cat_parent" => "Danh mục cha",
                ]
            );

            PostCat::where('id', $id)->update([
                'post_cat_title' => $requests->input("post_cat_title"),
                'slug' => $requests->input("slug"),
                'level' => $requests->input("level"),
                'post_cat_status' => $requests->input("status"),
                'cat_parent_id' => $requests->input("cat_parent"),
            ]);

            return redirect("admin/post/cat/list")->with("status", "Đã cập nhật thông tin danh mục bài viết có tiêu đề {$post_cat_title} thành công");
        }
    }

    public function action(Request $requests)
    {
        $list_checked = $requests->input("list_check");
        $act = $requests->input('act');
        if ($act != "") {
            if ($list_checked) {
                $cnt_member = count($list_checked);
                if ($cnt_member > 0) {
                    if ($act == "delete") {
                        foreach ($list_checked as $id) {
                            PostCat::where('cat_parent_id', $id)->update([
                                'cat_parent_id' => -1,
                            ]);
                            Post::where('post_cat_id', $id)->update([
                                'post_cat_id' => -1,
                            ]);
                            PostCat::where('id', $id)->update([
                                'post_cat_status' => "trashed",
                            ]);
                        }
                        PostCat::destroy($list_checked);
                        return redirect("admin/post/cat/list")->with("status", "Bạn đã xoá tạm thời {$cnt_member} danh mục bài viết thành công!");
                    } else if ($act == "licensed") {
                        foreach ($list_checked as $id) {
                            PostCat::where('id', $id)->update([
                                'post_cat_status' => "licensed",
                            ]);
                        }
                        return redirect("admin/post/cat/list")->with("status", "Bạn đã cấp quyền {$cnt_member} danh mục bài viết thành công");
                    } else if ($act == "pending") {
                        foreach ($list_checked as $id) {
                            PostCat::where('id', $id)->update([
                                'post_cat_status' => "pending",
                            ]);
                        }
                        return redirect("admin/post/cat/list")->with("status", "Bạn đã xét trạng thái chờ {$cnt_member} danh mục bài viết thành công");
                    } else if ($act == "delete_permanently") {
                        foreach ($list_checked as $id) {
                            PostCat::where('cat_parent_id', $id)->update([
                                'cat_parent_id' => -1,
                            ]);
                            Post::where('post_cat_id', $id)->update([
                                'post_cat_id' => -1,
                            ]);
                        }
                        PostCat::onlyTrashed()
                            ->whereIn("id", $list_checked)
                            ->forceDelete();
                        return redirect("admin/post/cat/list")->with("status", "Bạn đã xoá vĩnh viễn {$cnt_member} danh mục bài viết thành công");
                    } else if ($act == "restore") {
                        PostCat::onlyTrashed()
                            ->whereIn("id", $list_checked)
                            ->restore();
                        foreach ($list_checked as $id) {
                            PostCat::where('id', $id)->update([
                                'post_cat_status' => "pending",
                            ]);
                        }
                        return redirect("admin/post/cat/list")->with("status", "Bạn đã khôi phục {$cnt_member} danh mục bài viết thành công");
                    }
                } else {
                    return redirect()->back()->with("status", "Không tìm thấy danh mục bài viết nào!");
                }
            } else {
                return redirect()->back()->with("status", "Bạn chưa chọn danh mục bài viết nào để thực hiện hành động!");
            }
        } else {
            return redirect()->back()->with("status", "Bạn chưa chọn hành động nào để thực hiện!");
        }
    }

    public function delete($id)
    {
        PostCat::where('cat_parent_id', $id)->update([
            'cat_parent_id' => -1,
        ]);
        Post::where('post_cat_id', $id)->update([
            'post_cat_id' => -1,
        ]);

        $post_cat = PostCat::withTrashed()->find($id);
        $post_cat_title = $post_cat->post_cat_title;

        if (empty($post_cat->deleted_at)) {
            PostCat::where('id', $id)->update([
                'post_cat_status' => "trashed",
            ]);
            $post_cat->delete();
            return redirect()->back()->with("status", "Bạn đã xoá tạm thời danh mục bài viết có tiêu đề {$post_cat_title} thành công");
        } else {
            $post_cat->forceDelete();
            return redirect()->back()->with("status", "Bạn đã xoá vĩnh viễn danh mục bài viết có tiêu đề {$post_cat_title} thành công");
        }

    }

    public function restore($id)
    {
        $post_cat = PostCat::withTrashed()->find($id);
        $post_cat_title = $post_cat->post_cat_title;
        $post_cat->restore();
        PostCat::where('id', $id)->update([
            'post_cat_status' => "pending",
        ]);
        return redirect("admin/post/cat/list")->with("status", "Bạn đã khôi phục danh mục bài viết có tiêu đề {$post_cat_title} thành công");

    }
}

<?php

namespace App\Http\Controllers;

use App\Image;
use App\Post;
use App\PostCat;
use Illuminate\Http\Request;

class AdminPostController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => "post"]);
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
            $posts = Post::withoutTrashed()->where("post_title", "LIKE", "%{$key_word}%")->orderbyDesc("created_at")->Paginate(20);
        } else if ($status == "licensed") {
            $list_act = [
                "pending" => "Chờ xét duyệt",
                "delete" => "Xoá tạm thời",
            ];
            $posts = Post::withoutTrashed()->where("post_status", "licensed")->where("post_title", "LIKE", "%{$key_word}%")->orderbyDesc("created_at")->Paginate(20);
        } else if ($status == "pending") {
            $list_act = [
                "licensed" => "Đã đăng",
                "delete" => "Xoá tạm thời",
            ];
            $posts = Post::withoutTrashed()->where("post_status", "pending")->where("post_title", "LIKE", "%{$key_word}%")->orderbyDesc("created_at")->Paginate(20);
        } else if ($status == "trashed") {
            $list_act = [
                "restore" => "Khôi phục",
                "delete_permanently" => "Xoá vĩnh viễn",
            ];
            $posts = Post::onlyTrashed()->where("post_title", "LIKE", "%{$key_word}%")->orderbyDesc("created_at")->Paginate(20);
        }

        $count_post = $posts->total();
        $cnt_post_active = Post::withoutTrashed()->count();
        $cnt_post_licensed = Post::withoutTrashed()->where("post_status", "licensed")->count();
        $cnt_post_pending = Post::withoutTrashed()->where("post_status", "pending")->count();
        $cnt_post_trashed = Post::onlyTrashed()->count();
        $count_post_status = [$cnt_post_active, $cnt_post_licensed, $cnt_post_pending, $cnt_post_trashed];

        // Truyền các role:
        return view("admin.post.list", compact('posts', "count_post", "count_post_status", "list_act"));
    }

    public function add()
    {
        $list_post_cat = PostCat::all();
        return view('admin.post.add', compact("list_post_cat"));
    }

    public function store(Request $requests)
    {
        if ($requests->input('btn_add')) {
            $post_title = $requests->input('post_title');

            $requests->validate(
                [
                    'post_title' => ['required', 'string', 'max:255'],
                    'slug' => ['required', 'string', 'max:300'],
                    'post_desc' => ['required'],
                    'post_content' => ['required'],
                    "post_thumb" => ['required', 'file', "mimes:jpeg,png,jpg,gif", 'max:21000'],
                    'post_cat' => ['required', 'integer'],
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
                    "post_title" => "Tiêu đề bài viết",
                    "slug" => "Đường dẫn thân thiện",
                    "post_desc" => "Mô tả bài viết",
                    "post_content" => "Nội dung bài viết",
                    "post_thumb" => "Đường dẫn ảnh",
                    "post_cat" => "Danh mục bài viết",
                ]
            );

            if ($requests->hasFile("post_thumb")) {
                $file = $requests->post_thumb;
                $file_name = $file->getClientOriginalName();

                $file_ext = $file->getClientOriginalExtension();

                $file_size = $file->getSize();

                $path = $file->move("public/uploads", $file->getClientOriginalName());

                $thumbnail = "public/uploads/" . $file_name;
            }

            $item_image = Image::withoutTrashed()->where("image_link", "=", $thumbnail)->first();
            if (!empty($item_image)) {
                $image_id = $item_image->id;
            } else {
                $image = Image::create([
                    'image_link' => $thumbnail,
                ]);
                $image_id = $image->id;
            }

            Post::create([
                'post_title' => $requests->input("post_title"),
                'post_desc' => $requests->input("post_desc"),
                'post_content' => $requests->input("post_content"),
                'slug' => $requests->input("slug"),
                'post_cat_id' => $requests->input("post_cat"),
                'image_id' => $image_id,
            ]);

            return redirect("admin/post/list")->with("status", "Đã thêm bài viết có tiêu đề {$post_title} thành công");
        }
    }

    public function edit($id)
    {
        $post = Post::find($id);
        $list_post_cat = PostCat::all();
        return view('admin.post.edit', compact("list_post_cat", "post"));
    }

    public function update(Request $requests, $id)
    {
        if ($requests->input('btn_update')) {
            $post_title = $requests->input("post_title");
            $requests->validate(
                [
                    'post_title' => ['required', 'string', 'max:255'],
                    'slug' => ['required', 'string', 'max:300'],
                    'post_desc' => ['required'],
                    'post_content' => ['required'],
                    "post_thumb" => ['required', 'file', "mimes:jpeg,png,jpg,gif", 'max:21000'],
                    'post_cat' => ['required', 'integer'],
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
                    "confirmed" => ":attribute không giống nhau",
                ],
                [
                    "post_title" => "Tiêu đề bài viết",
                    "slug" => "Đường dẫn thân thiện",
                    "post_desc" => "Mô tả bài viết",
                    "post_content" => "Nội dung bài viết",
                    "post_thumb" => "Đường dẫn ảnh",
                    "post_cat" => "Danh mục bài viết",
                ]
            );

            if ($requests->hasFile("post_thumb")) {
                $file = $requests->post_thumb;
                $file_name = $file->getClientOriginalName();

                $file_ext = $file->getClientOriginalExtension();

                $file_size = $file->getSize();

                $path = $file->move("public/uploads", $file->getClientOriginalName());

                $thumbnail = "public/uploads/" . $file_name;
            }

            $item_image = Image::withoutTrashed()->where("image_link", "=", $thumbnail)->first();
            if (!empty($item_image)) {
                $image_id = $item_image->id;
            } else {
                $image = Image::create([
                    'image_link' => $thumbnail,
                ]);
                $image_id = $image->id;
            }

            Post::where('id', $id)->update([
                'post_title' => $requests->input("post_title"),
                'post_desc' => $requests->input("post_desc"),
                'post_content' => $requests->input("post_content"),
                'slug' => $requests->input("slug"),
                'post_cat_id' => $requests->input("post_cat"),
                'image_id' => $image_id,
                'post_status' => $requests->input("status"),
            ]);

            return redirect("admin/post/list")->with("status", "Đã cập nhật thông tin bài viết có tiêu đề {$post_title} thành công");
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
                            Post::where('id', $id)->update([
                                'post_status' => "trashed",
                            ]);
                        }
                        Post::destroy($list_checked);
                        return redirect("admin/post/list")->with("status", "Bạn đã xoá tạm thời {$cnt_member} bài viết  thành công!");
                    } else if ($act == "licensed") {
                        foreach ($list_checked as $id) {
                            Post::where('id', $id)->update([
                                'post_status' => "licensed",
                            ]);
                        }
                        return redirect("admin/post/list")->with("status", "Bạn đã cấp quyền {$cnt_member} bài viết  thành công");
                    } else if ($act == "pending") {
                        foreach ($list_checked as $id) {
                            Post::where('id', $id)->update([
                                'post_status' => "pending",
                            ]);
                        }
                        return redirect("admin/post/list")->with("status", "Bạn đã xét trạng thái chờ {$cnt_member} bài viết  thành công");
                    } else if ($act == "delete_permanently") {
                        Post::onlyTrashed()
                            ->whereIn("id", $list_checked)
                            ->forceDelete();
                        return redirect("admin/post/list")->with("status", "Bạn đã xoá vĩnh viễn {$cnt_member} bài viết  thành công");
                    } else if ($act == "restore") {
                        Post::onlyTrashed()
                            ->whereIn("id", $list_checked)
                            ->restore();
                        foreach ($list_checked as $id) {
                            Post::where('id', $id)->update([
                                'post_status' => "pending",
                            ]);
                        }
                        return redirect("admin/post/list")->with("status", "Bạn đã khôi phục {$cnt_member} bài viết  thành công");
                    }
                } else {
                    return redirect()->back()->with("status", "Không tìm thấy bài viết nào!");
                }
            } else {
                return redirect()->back()->with("status", "Bạn chưa chọn bài viết nào để thực hiện hành động!");
            }
        } else {
            return redirect()->back()->with("status", "Bạn chưa chọn hành động nào để thực hiện!");
        }
    }

    public function delete($id)
    {
        $post = Post::withTrashed()->find($id);
        $post_title = $post->post_title;

        if (empty($post->deleted_at)) {
            Post::where('id', $id)->update([
                'post_status' => "trashed",
            ]);
            $post->delete();
            return redirect()->back()->with("status", "Bạn đã xoá tạm thời bài viết có tiêu đề {$post_title} thành công");
        } else {
            $post->forceDelete();
            return redirect()->back()->with("status", "Bạn đã xoá vĩnh viễn bài viết có tiêu đề {$post_title} thành công");
        }

    }

    public function restore($id)
    {
        $post = Post::withTrashed()->find($id);
        $post_title = $post->post_title;
        $post->restore();
        Post::where('id', $id)->update([
            'post_status' => "pending",
        ]);
        return redirect("admin/post/list")->with("status", "Bạn đã khôi phục bài viết có tiêu đề {$post_title} thành công");

    }
}

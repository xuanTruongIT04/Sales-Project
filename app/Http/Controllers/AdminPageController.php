<?php

namespace App\Http\Controllers;

use App\Page;
use Illuminate\Http\Request;

class AdminPageController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => "page"]);
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
            $pages = Page::withoutTrashed()->where("page_title", "LIKE", "%{$key_word}%")->orderbyDesc("created_at")->Paginate(20);
        } else if ($status == "licensed") {
            $list_act = [
                "pending" => "Chờ xét duyệt",
                "delete" => "Xoá tạm thời",
            ];
            $pages = Page::withoutTrashed()->where("page_status", "licensed")->where("page_title", "LIKE", "%{$key_word}%")->orderbyDesc("created_at")->Paginate(20);
        } else if ($status == "pending") {
            $list_act = [
                "licensed" => "Đã đăng",
                "delete" => "Xoá tạm thời",
            ];
            $pages = Page::withoutTrashed()->where("page_status", "pending")->where("page_title", "LIKE", "%{$key_word}%")->orderbyDesc("created_at")->Paginate(20);
        } else if ($status == "trashed") {
            $list_act = [
                "restore" => "Khôi phục",
                "delete_permanently" => "Xoá vĩnh viễn",
            ];
            $pages = Page::onlyTrashed()->where("page_title", "LIKE", "%{$key_word}%")->orderbyDesc("created_at")->Paginate(20);
        }

        $count_page = $pages->total();
        $cnt_page_active = Page::withoutTrashed()->count();
        $cnt_page_licensed = Page::withoutTrashed()->where("page_status", "licensed")->count();
        $cnt_page_pending = Page::withoutTrashed()->where("page_status", "pending")->count();
        $cnt_page_trashed = Page::onlyTrashed()->count();
        $count_page_status = [$cnt_page_active, $cnt_page_licensed, $cnt_page_pending, $cnt_page_trashed];

        // Truyền các role:
        return view("admin.page.list", compact('pages', "count_page", "count_page_status", "list_act"));
    }

    public function add()
    {
        return view('admin.page.add');
    }

    public function store(Request $requests)
    {
        if ($requests->input('btn_add')) {
            $page_title = $requests->input('page_title');

            $requests->validate(
                [
                    'page_title' => ['required', 'string', 'max:255'],
                    'slug' => ['required', 'string', 'max:300'],
                    'page_desc' => ['required'],
                    'page_content' => ['required'],
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
                    "page_title" => "Tiêu đề trang",
                    "slug" => "Đường dẫn thân thiện",
                    "page_desc" => "Mô tả trang",
                    "page_content" => "Nội dung trang",
                ]
            );

            Page::create([
                'page_title' => $requests->input("page_title"),
                'page_desc' => $requests->input("page_desc"),
                'page_content' => $requests->input("page_content"),
                'slug' => $requests->input("slug"),
            ]);

            return redirect("admin/page/list")->with("status", "Đã thêm trang có tiêu đề {$page_title} thành công");
        }
    }

    public function edit($id)
    {
        $page = Page::find($id);

        return view("admin.page.edit", compact("page"));
    }

    public function update(Request $requests, $id)
    {
        if ($requests->input('btn_update')) {
            $page_title = $requests->input("page_title");
            $requests->validate(
                [
                    'page_title' => ['required', 'string', 'max:255'],
                    'slug' => ['required', 'string', 'max:300'],
                    'page_desc' => ['required'],
                    'page_content' => ['required'],
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
                    "page_title" => "Tiêu đề trang",
                    "slug" => "Đường dẫn thân thiện",
                    "page_desc" => "Mô tả trang",
                    "page_content" => "Nội dung trang",
                ]
            );

            Page::where('id', $id)->update([
                'page_title' => $requests->input("page_title"),
                'page_desc' => $requests->input("page_desc"),
                'page_content' => $requests->input("page_content"),
                'slug' => $requests->input("slug"),
                'page_status' => $requests->input("status"),
            ]);

            return redirect("admin/page/list")->with("status", "Đã cập nhật thông tin trang có tiêu đề {$page_title} thành công");
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
                            Page::where('id', $id)->update([
                                'page_status' => "trashed",
                            ]);
                        }
                        Page::destroy($list_checked);
                        return redirect("admin/page/list")->with("status", "Bạn đã xoá tạm thời {$cnt_member} trang thành công!");
                    } else if ($act == "licensed") {
                        foreach ($list_checked as $id) {
                            Page::where('id', $id)->update([
                                'page_status' => "licensed",
                            ]);
                        }
                        return redirect("admin/page/list")->with("status", "Bạn đã cấp quyền {$cnt_member} trang thành công");
                    } else if ($act == "pending") {
                        foreach ($list_checked as $id) {
                            Page::where('id', $id)->update([
                                'page_status' => "pending",
                            ]);
                        }
                        return redirect("admin/page/list")->with("status", "Bạn đã xét trạng thái chờ {$cnt_member} trang thành công");
                    } else if ($act == "delete_permanently") {
                        Page::onlyTrashed()
                            ->whereIn("id", $list_checked)
                            ->forceDelete();
                        return redirect("admin/page/list")->with("status", "Bạn đã xoá vĩnh viễn {$cnt_member} trang thành công");
                    } else if ($act == "restore") {
                        Page::onlyTrashed()
                            ->whereIn("id", $list_checked)
                            ->restore();
                        foreach ($list_checked as $id) {
                            Page::where('id', $id)->update([
                                'page_status' => "pending",
                            ]);
                        }
                        return redirect("admin/page/list")->with("status", "Bạn đã khôi phục {$cnt_member} trang thành công");
                    }
                } else {
                    return redirect()->back()->with("status", "Không tìm thấy trang nào!");
                }
            } else {
                return redirect()->back()->with("status", "Bạn chưa chọn trang nào để thực hiện hành động!");
            }
        } else {
            return redirect()->back()->with("status", "Bạn chưa chọn hành động nào để thực hiện!");
        }
    }

    public function delete($id)
    {
        $page = Page::withTrashed()->find($id);
        $page_title = $page->page_title;

        if (empty($page->deleted_at)) {
            Page::where('id', $id)->update([
                'page_status' => "trashed",
            ]);
            $page->delete();
            return redirect()->back()->with("status", "Bạn đã xoá tạm thời trang có tiêu đề {$page_title} thành công");
        } else {
            $page->forceDelete();
            return redirect()->back()->with("status", "Bạn đã xoá vĩnh viễn trang có tiêu đề {$page_title} thành công");
        }

    }

    public function restore($id)
    {
        $page = Page::withTrashed()->find($id);
        $page_title = $page->page_title;
        $page->restore();
        Page::where('id', $id)->update([
            'page_status' => "pending",
        ]);
        return redirect("admin/page/list")->with("status", "Bạn đã khôi phục trang có tiêu đề {$page_title} thành công");

    }
}

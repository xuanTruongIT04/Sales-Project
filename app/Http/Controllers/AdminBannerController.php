<?php

namespace App\Http\Controllers;

use App\Banner;
use App\Image;
use Illuminate\Http\Request;

class AdminBannerController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => "banner"]);
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
            $banners = Banner::withoutTrashed()->where("banner_name", "LIKE", "%{$key_word}%")->orderbyDesc("created_at")->Paginate(20);
        } else if ($status == "licensed") {
            $list_act = [
                "pending" => "Chờ xét duyệt",
                "delete" => "Xoá tạm thời",
            ];
            $banners = Banner::withoutTrashed()->where("banner_status", "licensed")->where("banner_name", "LIKE", "%{$key_word}%")->orderbyDesc("created_at")->Paginate(20);
        } else if ($status == "pending") {
            $list_act = [
                "licensed" => "Đã đăng",
                "delete" => "Xoá tạm thời",
            ];
            $banners = Banner::withoutTrashed()->where("banner_status", "pending")->where("banner_name", "LIKE", "%{$key_word}%")->orderbyDesc("created_at")->Paginate(20);
        } else if ($status == "trashed") {
            $list_act = [
                "restore" => "Khôi phục",
                "delete_permanently" => "Xoá vĩnh viễn",
            ];
            $banners = Banner::onlyTrashed()->where("banner_name", "LIKE", "%{$key_word}%")->orderbyDesc("created_at")->Paginate(20);
        }

        $count_banner = $banners->total();
        $cnt_banner_active = Banner::withoutTrashed()->count();
        $cnt_banner_licensed = Banner::withoutTrashed()->where("banner_status", "licensed")->count();
        $cnt_banner_pending = Banner::withoutTrashed()->where("banner_status", "pending")->count();
        $cnt_banner_trashed = Banner::onlyTrashed()->count();
        $count_banner_status = [$cnt_banner_active, $cnt_banner_licensed, $cnt_banner_pending, $cnt_banner_trashed];

        // Truyền các role:
        return view("admin.banner.list", compact('banners', "count_banner", "count_banner_status", "list_act"));
    }

    public function add()
    {
        return view('admin.banner.add');
    }

    public function store(Request $requests)
    {
        if ($requests->input('btn_add')) {
            $banner_name = $requests->input('banner_name');

            $requests->validate(
                [
                    'banner_name' => ['required', 'string', 'max:255'],
                    'banner_thumb' => ['required', 'file', "mimes:jpeg,png,jpg,gif", 'max:21000'],
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
                    "banner_name" => "Tên banner",
                    "banner_thumb" => "Đường dẫn ảnh",
                ]
            );

            if ($requests->hasFile("banner_thumb")) {
                $file = $requests->banner_thumb;
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

            Banner::create([
                'banner_name' => $requests->input("banner_name"),
                'image_id' => $image_id,
            ]);

            return redirect("admin/banner/list")->with("status", "Đã thêm banner tên {$banner_name} thành công");
        }
    }

    public function edit($id)
    {
        $banner = Banner::find($id);
        return view("admin.banner.edit", compact("banner"));
    }

    public function update(Request $requests, $id)
    {
        if ($requests->input('btn_update')) {
            $banner_name = $requests->input("banner_name");
            $requests->validate(
                [
                    'banner_name' => ['required', 'string', 'max:255'],
                    'banner_thumb' => ['required', 'file', 'max:21000'],
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
                    "banner_name" => "Tên banner",
                    "banner_thumb" => "Đường dẫn ảnh",
                ]
            );

            if ($requests->hasFile("banner_thumb")) {
                $file = $requests->banner_thumb;
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

            Banner::where('id', $id)->update([
                'banner_name' => $requests->input("banner_name"),
                'image_id' => $image_id,
                'banner_status' => $requests->input("status"),
            ]);

            return redirect("admin/banner/list")->with("status", "Đã cập nhật thông tin banner tên {$banner_name} thành công");
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
                            Banner::where('id', $id)->update([
                                'banner_status' => "trashed",
                            ]);
                        }
                        Banner::destroy($list_checked);
                        return redirect("admin/banner/list")->with("status", "Bạn đã xoá tạm thời {$cnt_member} banner thành công!");
                    } else if ($act == "licensed") {
                        foreach ($list_checked as $id) {
                            Banner::where('id', $id)->update([
                                'banner_status' => "licensed",
                            ]);
                        }
                        return redirect("admin/banner/list")->with("status", "Bạn đã cấp quyền {$cnt_member} banner thành công");
                    } else if ($act == "pending") {
                        foreach ($list_checked as $id) {
                            Banner::where('id', $id)->update([
                                'banner_status' => "pending",
                            ]);
                        }
                        return redirect("admin/banner/list")->with("status", "Bạn đã xét trạng thái chờ {$cnt_member} banner thành công");
                    } else if ($act == "delete_permanently") {
                        Banner::onlyTrashed()
                            ->whereIn("id", $list_checked)
                            ->forceDelete();
                        return redirect("admin/banner/list")->with("status", "Bạn đã xoá vĩnh viễn {$cnt_member} banner thành công");
                    } else if ($act == "restore") {
                        Banner::onlyTrashed()
                            ->whereIn("id", $list_checked)
                            ->restore();
                        foreach ($list_checked as $id) {
                            Banner::where('id', $id)->update([
                                'banner_status' => "pending",
                            ]);
                        }
                        return redirect("admin/banner/list")->with("status", "Bạn đã khôi phục {$cnt_member} banner thành công");
                    }
                } else {
                    return redirect()->back()->with("status", "Không tìm thấy banner nào!");
                }
            } else {
                return redirect()->back()->with("status", "Bạn chưa chọn banner nào để thực hiện hành động!");
            }
        } else {
            return redirect()->back()->with("status", "Bạn chưa chọn hành động nào để thực hiện!");
        }
    }

    public function delete($id)
    {
        $banner = Banner::withTrashed()->where('id', $id);
        $banner_name = $banner->banner_name;

        if (empty($banner->deleted_at)) {
            Banner::where('id', $id)->update([
                'banner_status' => "trashed",
            ]);
            $banner->delete();
            return redirect()->back()->with("status", "Bạn đã xoá tạm thời banner tên {$banner_name} thành công");
        } else {
            $banner->forceDelete();
            return redirect()->back()->with("status", "Bạn đã xoá vĩnh viễn banner tên {$banner_name} thành công");
        }
    }

    public function restore($id)
    {
        $banner = Banner::withTrashed()->where('id', $id);
        $banner_name = $banner->banner_name;
        $banner->restore();
        Banner::where('id', $id)->update([
            'banner_status' => "pending",
        ]);
        return redirect("admin/banner/list")->with("status", "Bạn đã khôi phục banner tên {$banner_name} thành công");

    }
}

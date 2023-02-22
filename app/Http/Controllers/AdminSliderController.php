<?php

namespace App\Http\Controllers;

use App\Image;
use App\Slider;
use Illuminate\Http\Request;

class AdminSliderController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => "slider"]);
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
            $sliders = Slider::withoutTrashed()->where("slider_name", "LIKE", "%{$key_word}%")->orderBy("order")->orderbyDesc("created_at")->Paginate(20);
        } else if ($status == "licensed") {
            $list_act = [
                "pending" => "Chờ xét duyệt",
                "delete" => "Xoá tạm thời",
            ];
            $sliders = Slider::withoutTrashed()->where("slider_status", "licensed")->where("slider_name", "LIKE", "%{$key_word}%")->orderBy("order")->orderbyDesc("created_at")->Paginate(20);
        } else if ($status == "pending") {
            $list_act = [
                "licensed" => "Đã đăng",
                "delete" => "Xoá tạm thời",
            ];
            $sliders = Slider::withoutTrashed()->where("slider_status", "pending")->where("slider_name", "LIKE", "%{$key_word}%")->orderBy("order")->orderbyDesc("created_at")->Paginate(20);
        } else if ($status == "trashed") {
            $list_act = [
                "restore" => "Khôi phục",
                "delete_permanently" => "Xoá vĩnh viễn",
            ];
            $sliders = Slider::onlyTrashed()->where("slider_name", "LIKE", "%{$key_word}%")->orderBy("order")->orderbyDesc("created_at")->Paginate(20);
        }

        $count_slider = $sliders->total();
        $cnt_slider_active = Slider::withoutTrashed()->count();
        $cnt_slider_licensed = Slider::withoutTrashed()->where("slider_status", "licensed")->count();
        $cnt_slider_pending = Slider::withoutTrashed()->where("slider_status", "pending")->count();
        $cnt_slider_trashed = Slider::onlyTrashed()->count();
        $count_slider_status = [$cnt_slider_active, $cnt_slider_licensed, $cnt_slider_pending, $cnt_slider_trashed];

        // Truyền các role:
        return view("admin.slider.list", compact('sliders', "count_slider", "count_slider_status", "list_act"));
    }

    public function add()
    {
        return view('admin.slider.add');
    }

    public function store(Request $requests)
    {
        if ($requests->input('btn_add')) {
            $slider_name = $requests->input('slider_name');

            $requests->validate(
                [
                    'slider_name' => ['required', 'string', 'max:255'],
                    'slider_thumb' => ['required', 'file', "mimes:jpeg,png,jpg,gif", 'max:21000'],
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
                    "slider_name" => "Tên slider",
                    "slider_thumb" => "Đường dẫn slider",
                    "order" => "Thứ tự",
                ]
            );

            if ($requests->hasFile("slider_thumb")) {
                $file = $requests->slider_thumb;
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

            Slider::create([
                'slider_name' => $requests->input("slider_name"),
                'order' => $requests->input("order"),
                'image_id' => $image_id,
            ]);

            return redirect("admin/slider/list")->with("status", "Đã thêm slider tên {$slider_name} thành công");
        }
    }

    public function edit($id)
    {
        $slider = Slider::find($id);

        return view("admin.slider.edit", compact("slider"));
    }

    public function update(Request $requests, $id)
    {
        if ($requests->input('btn_update')) {
            $slider_name = $requests->input("slider_name");
            $requests->validate(
                [
                    'slider_name' => ['required', 'string', 'max:255'],
                    'slider_thumb' => ['required', 'file', 'max:21000'],
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
                    "slider_name" => "Tên slider",
                    "slider_thumb" => "Đường dẫn ảnh",
                ]
            );

            if ($requests->hasFile("slider_thumb")) {
                $file = $requests->slider_thumb;
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

            Slider::where('id', $id)->update([
                'slider_name' => $requests->input("slider_name"),
                'image_id' => $image_id,
                'order' => $requests->input("order"),
                'slider_status' => $requests->input("status"),
            ]);

            return redirect("admin/slider/list")->with("status", "Đã cập nhật thông tin slider tên {$slider_name} thành công");
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
                            Slider::where('id', $id)->update([
                                'slider_status' => "trashed",
                            ]);
                        }
                        Slider::destroy($list_checked);
                        return redirect("admin/slider/list")->with("status", "Bạn đã xoá tạm thời {$cnt_member} slider thành công!");
                    } else if ($act == "licensed") {
                        foreach ($list_checked as $id) {
                            Slider::where('id', $id)->update([
                                'slider_status' => "licensed",
                            ]);
                        }
                        return redirect("admin/slider/list")->with("status", "Bạn đã cấp quyền {$cnt_member} slider thành công");
                    } else if ($act == "pending") {
                        foreach ($list_checked as $id) {
                            Slider::where('id', $id)->update([
                                'slider_status' => "pending",
                            ]);
                        }
                        return redirect("admin/slider/list")->with("status", "Bạn đã xét trạng thái chờ {$cnt_member} slider thành công");
                    } else if ($act == "delete_permanently") {
                        Slider::onlyTrashed()
                            ->whereIn("id", $list_checked)
                            ->forceDelete();
                        return redirect("admin/slider/list")->with("status", "Bạn đã xoá vĩnh viễn {$cnt_member} slider thành công");
                    } else if ($act == "restore") {
                        Slider::onlyTrashed()
                            ->whereIn("id", $list_checked)
                            ->restore();
                        foreach ($list_checked as $id) {
                            Slider::where('id', $id)->update([
                                'slider_status' => "pending",
                            ]);
                        }
                        return redirect("admin/slider/list")->with("status", "Bạn đã khôi phục {$cnt_member} slider thành công");
                    }
                } else {
                    return redirect()->back()->with("status", "Không tìm thấy slider nào!");
                }
            } else {
                return redirect()->back()->with("status", "Bạn chưa chọn slider nào để thực hiện hành động!");
            }
        } else {
            return redirect()->back()->with("status", "Bạn chưa chọn hành động nào để thực hiện!");
        }
    }

    public function delete($id)
    {
        $slider = Slider::withTrashed()->find($id);
        $slider_name = $slider->slider_name;

        if (empty($slider->deleted_at)) {
            Slider::where('id', $id)->update([
                'slider_status' => "trashed",
            ]);
            $slider->delete();
            return redirect()->back()->with("status", "Bạn đã xoá tạm thời slider tên {$slider_name} thành công");
        } else {
            $slider->forceDelete();
            return redirect()->back()->with("status", "Bạn đã xoá vĩnh viễn slider tên {$slider_name} thành công");
        }
    }

    public function restore($id)
    {
        $slider = Slider::withTrashed()->find($id);
        $slider_name = $slider->slider_name;
        $slider->restore();
        Slider::where('id', $id)->update([
            'slider_status' => "pending",
        ]);
        return redirect("admin/slider/list")->with("status", "Bạn đã khôi phục slider tên {$slider_name} thành công");
    }
}

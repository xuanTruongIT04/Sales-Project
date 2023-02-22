<?php

namespace App\Http\Controllers;

use App\Image;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminImageController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => "image"]);
            return $next($request);
        });
    }

    function list(Request $requests) {
        $status = !empty(request()->input('status')) ? request()->input('status') : 'active';
        $list_act = [
            "delete" => "Xoá tạm thời",
        ];

        $key_word = "";

        if ($requests->input("key_word")) {
            $key_word = $requests->input("key_word");
        }

        if ($status == "trashed") {
            $list_act = [
                "restore" => "Khôi phục",
                "delete_permanently" => "Xoá vĩnh viễn",
            ];
            $images = Image::onlyTrashed()->where("image_link", "LIKE", "%{$key_word}%")->orderbyDesc("created_at")->Paginate(20);
        } else {
            $images = Image::withoutTrashed()->where("image_link", "LIKE", "%{$key_word}%")->orderbyDesc("created_at")->Paginate(20);
        }

        $count_image = $images->total();
        $cnt_image_active = Image::withoutTrashed()->count();
        $cnt_image_trashed = Image::onlyTrashed()->count();
        $count_image_status = [$cnt_image_active, $cnt_image_trashed];
        return view("admin.image.list", compact('images', "count_image", "count_image_status", "list_act"));
    }

    public function add()
    {
        $images = Image::all();
        return view('admin.image.add', compact("images"));
    }

    public function store(Request $requests)
    {
        if ($requests->input('btn_add')) {
            $requests->validate(
                [
                    'image_link' => ['required', 'file', "mimes:jpeg,png,jpg,gif", 'max:21000'],
                ],
                [
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
                    "image_link" => "Đường dẫn ảnh",
                ]
            );

            if ($requests->hasFile("image_link")) {

                $file = $requests->image_link;

                $file_name = $file->getClientOriginalName();

                $file_ext = $file->getClientOriginalExtension();

                $file_size = $file->getSize();

                $path = $file->move("public/uploads", $file->getClientOriginalName());

                $thumbnail = "public/uploads/" . $file_name;

            }

            $item_image = Image::withoutTrashed()->where("image_link", "=", $thumbnail)->first();
            if (!empty($item_image)) {
                $image_link = $item_image->image_link;
                return redirect("admin/image/list")->with("status", "Đã tồn tại ảnh có đường dẫn {$image_link}");
            } else {
                $image = Image::create([
                    'image_link' => $thumbnail,
                ]);
                $image_link = $image->image_link;
                return redirect("admin/image/list")->with("status", "Đã thêm ảnh có đường dẫn {$image_link} thành công");
            }

        }
    }

    public function addMulti($id)
    {
        $images = Image::all();
        $link_img_main = Image::where("product_id", $id)->where("rank", "1") -> first() -> image_link;
        $product_name = Product::find($id)->product_name;
        $list_product_thumb_sub = Image::where("product_id", $id)
                                       ->where("deleted_at", null)
                                       ->get();
        return view('admin.image.addMulti', compact("images", "link_img_main", "product_name", "list_product_thumb_sub"));
    }

    public function storeMulti(Request $requests, $id)
    {
        if ($requests->input('btn_add')) {
            $requests->validate(
                [
                    'image_link.*' => ['required', "mimes:jpeg,png,jpg,gif", 'max:21000'],
                    'image_link' => ['required'],
                ],
                [
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
                    "image_link" => "Đường dẫn ảnh",
                ]
            );

            if ($requests->hasFile("image_link")) {

                $list_image = $requests->file("image_link");
                $thumbnail = array();
                foreach ($list_image as $image) {
                    $file_name = $image->getClientOriginalName();
                    echo $file_name . "<BR>";
                    $image->move("public/uploads", $image->getClientOriginalName());
                    $thumbnail[] = "public/uploads/" . $file_name;
                }
            }

            $count_image_create_success = 0;
            foreach ($thumbnail as $k => $item) {
                $image = Image::create([
                    'image_link' => $item,
                    'rank' => "0",
                    'product_id' => $id,
                ]);
                $count_image_create_success++;
            }

            $product_name = Product::find($id)->product_name;
            return redirect(url("admin/product/list"))->with("status", "Bạn đã thêm {$count_image_create_success} hình ảnh vào sản phẩm {$product_name}!");
        }
    }

    public function edit($id)
    {
        $images = Image::all();
        $image = Image::find($id);

        return view("admin.image.edit", compact("image", "images"));
    }

    public function update(Request $requests, $id)
    {
        if ($requests->input('btn_update')) {
            $requests->validate(
                [
                    'image_link' => ['required', 'string', 'max:300'],
                ],
                [
                    'required' => ":attribute không được để trống",
                    'max' => ":attribute có độ dài ít nhất :max ký tự",
                ],
                [
                    "image_link" => "Đường dẫn ảnh",
                ]
            );
            $image_link = $requests->input("image_link");

            $path_image_old = Image::find($id)->image_link;
            rename($path_image_old, $image_link);

            Image::where('id', $id)->update([
                'image_link' => $image_link,
            ]);

            return redirect("admin/image/list")->with("status", "Đã cập nhật thông tin ảnh có đường dẫn {$image_link} thành công");
        }
    }

    public function action(Request $requests)
    {
        $list_checked = $requests->input("list_check");
        $act = $requests->input('act');
        if ($act != "") {
            if ($list_checked) {
                foreach ($list_checked as $k => $id) {
                    if (Auth::id() == $id) {
                        unset($list_checked[$k]);
                    }
                }
                $cnt_member = count($list_checked);
                if ($cnt_member > 0) {
                    if ($act == "delete") {
                        Image::destroy($list_checked);
                        return redirect("admin/image/list")->with("status", "Bạn đã xoá tạm thời {$cnt_member} ảnh thành công!");
                    } else if ($act == "delete_permanently") {
                        Image::onlyTrashed()
                            ->whereIn("id", $list_checked)
                            ->forceDelete();
                        return redirect("admin/image/list")->with("status", "Bạn đã xoá vĩnh viễn {$cnt_member} ảnh thành công");
                    } else if ($act == "restore") {
                        Image::onlyTrashed()
                            ->whereIn("id", $list_checked)
                            ->restore();
                        return redirect("admin/image/list")->with("status", "Bạn đã khôi phục {$cnt_member} ảnh thành công");
                    }
                } else {
                    return redirect()->back()->with("status", "Không tìm thấy hình ảnh nào!");
                }
            } else {
                return redirect()->back()->with("status", "Bạn chưa chọn ảnh nào để thực hiện hành động!");
            }
        } else {
            return redirect()->back()->with("status", "Bạn chưa chọn hành động nào để thực hiện!");
        }
    }

    public function delete($id)
    {
        if (Auth::id() != $id) {
            $image = Image::withTrashed()->find($id);
            $image_link = $image->image_link;

            if (empty($image->deleted_at)) {
                $image->delete();
                return redirect()->back()->with("status", "Bạn đã xoá tạm thời ảnh có đường dẫn {$image_link} thành công");
            } else {

                $image->forceDelete();
                return redirect()->back()->with("status", "Bạn đã xoá vĩnh viễn ảnh có đường dẫn {$image_link} thành công");
            }
        } else {
            return redirect()->back()->with("status", "Bạn không thể tự xoá chính mình ra khỏi hệ thống");
        }

    }

    public function restore($id)
    {
        $image = Image::withTrashed()->find($id);
        $image_link = $image->image_link;
        $image->restore();
        return redirect("admin/image/list")->with("status", "Bạn đã khôi phục hình ảnh có đường dẫn {$image_link} thành công");
    }

}

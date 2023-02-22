<?php

namespace App\Http\Controllers;

use App\ProductCat;
use Illuminate\Http\Request;

class AdminProductCatController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => "product_cat"]);
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
            $product_cats = ProductCat::withoutTrashed()->where("product_cat_title", "LIKE", "%{$key_word}%")->orderBy("level")->orderbyDesc("created_at")->Paginate(20);
        } else if ($status == "licensed") {
            $list_act = [
                "pending" => "Chờ xét duyệt",
                "delete" => "Xoá tạm thời",
            ];
            $product_cats = ProductCat::withoutTrashed()->where("product_cat_status", "licensed")->where("product_cat_title", "LIKE", "%{$key_word}%")->orderBy("level")->orderbyDesc("created_at")->Paginate(20);
        } else if ($status == "pending") {
            $list_act = [
                "licensed" => "Đã đăng",
                "delete" => "Xoá tạm thời",
            ];
            $product_cats = ProductCat::withoutTrashed()->where("product_cat_status", "pending")->where("product_cat_title", "LIKE", "%{$key_word}%")->orderBy("level")->orderbyDesc("created_at")->Paginate(20);
        } else if ($status == "trashed") {
            $list_act = [
                "restore" => "Khôi phục",
                "delete_permanently" => "Xoá vĩnh viễn",
            ];
            $product_cats = ProductCat::onlyTrashed()->where("product_cat_title", "LIKE", "%{$key_word}%")->orderBy("level")->orderbyDesc("created_at")->Paginate(20);
        }

        $count_product_cat = $product_cats->total();
        $cnt_product_cat_active = ProductCat::withoutTrashed()->count();
        $cnt_product_cat_licensed = ProductCat::withoutTrashed()->where("product_cat_status", "licensed")->count();
        $cnt_product_cat_pending = ProductCat::withoutTrashed()->where("product_cat_status", "pending")->count();
        $cnt_product_cat_trashed = ProductCat::onlyTrashed()->count();
        $count_product_cat_status = [$cnt_product_cat_active, $cnt_product_cat_licensed, $cnt_product_cat_pending, $cnt_product_cat_trashed];

        // Truyền các role:
        return view("admin.product.cat.list", compact('product_cats', "count_product_cat", "count_product_cat_status", "list_act"));
    }

    public function add()
    {
        $list_product_cat = ProductCat::all();
        return view('admin.product.cat.add', compact("list_product_cat"));
    }

    public function store(Request $requests)
    {
        if ($requests->input('btn_add')) {
            $product_cat_title = $requests->input('product_cat_title');

            $requests->validate(
                [
                    'product_cat_title' => ['required', 'string', 'max:255'],
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
                    "product_cat_title" => "Tiêu đề danh mục",
                    "slug" => "Đường dẫn thân thiện",
                    "level" => "Cấp độ",
                    "cat_parent" => "Danh mục cha",
                ]
            );

            ProductCat::create([
                'product_cat_title' => $requests->input("product_cat_title"),
                'slug' => $requests->input("slug"),
                'level' => $requests->input("level"),
                'cat_parent_id' => $requests->input("cat_parent"),
            ]);

            return redirect("admin/product/cat/list")->with("status", "Đã thêm danh mục sản phẩm có tiêu đề {$product_cat_title} thành công");
        }
    }

    public function edit($id)
    {
        $product_cat = ProductCat::find($id);
        $list_product_cat = ProductCat::all();
        return view("admin.product.cat.edit", compact("product_cat", "list_product_cat"));
    }

    public function update(Request $requests, $id)
    {
        if ($requests->input('btn_update')) {
            $product_cat_title = $requests->input("product_cat_title");
            $requests->validate(
                [
                    'product_cat_title' => ['required', 'string', 'max:255'],
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
                    "product_cat_title" => "Tiêu đề danh mục",
                    "slug" => "Đường dẫn thân thiện",
                    "level" => "Cấp độ",
                    "cat_parent" => "Danh mục cha",
                ]
            );

            ProductCat::where('id', $id)->update([
                'product_cat_title' => $requests->input("product_cat_title"),
                'slug' => $requests->input("slug"),
                'level' => $requests->input("level"),
                'product_cat_status' => $requests->input("status"),
                'cat_parent_id' => $requests->input("cat_parent"),
            ]);

            return redirect("admin/product/cat/list")->with("status", "Đã cập nhật thông tin danh mục sản phẩm có tiêu đề {$product_cat_title} thành công");
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
                            ProductCat::where('id', $id)->update([
                                'product_cat_status' => "trashed",
                            ]);
                            ProductCat::where('cat_parent_id', $id)->update([
                                'cat_parent_id' => -1,
                            ]);
                        }
                        ProductCat::destroy($list_checked);
                        return redirect("admin/product/cat/list")->with("status", "Bạn đã xoá tạm thời {$cnt_member} danh mục sản phẩm thành công!");
                    } else if ($act == "licensed") {
                        foreach ($list_checked as $id) {
                            ProductCat::where('id', $id)->update([
                                'product_cat_status' => "licensed",
                            ]);
                        }
                        return redirect("admin/product/cat/list")->with("status", "Bạn đã cấp quyền {$cnt_member} danh mục sản phẩm thành công");
                    } else if ($act == "pending") {
                        foreach ($list_checked as $id) {
                            ProductCat::where('id', $id)->update([
                                'product_cat_status' => "pending",
                            ]);
                        }
                        return redirect("admin/product/cat/list")->with("status", "Bạn đã xét trạng thái chờ {$cnt_member} danh mục sản phẩm thành công");
                    } else if ($act == "delete_permanently") {
                        foreach ($list_checked as $id) {
                            ProductCat::where('cat_parent_id', $id)->update([
                                'cat_parent_id' => -1,
                            ]);
                        }
                        ProductCat::onlyTrashed()
                            ->whereIn("id", $list_checked)
                            ->forceDelete();
                        return redirect("admin/product/cat/list")->with("status", "Bạn đã xoá vĩnh viễn {$cnt_member} danh mục sản phẩm thành công");
                    } else if ($act == "restore") {
                        ProductCat::onlyTrashed()
                            ->whereIn("id", $list_checked)
                            ->restore();
                        foreach ($list_checked as $id) {
                            ProductCat::where('id', $id)->update([
                                'product_cat_status' => "pending",
                            ]);
                        }
                        return redirect("admin/product/cat/list")->with("status", "Bạn đã khôi phục {$cnt_member} danh mục sản phẩm thành công");
                    }
                } else {
                    return redirect()->back()->with("status", "Không tìm thấy danh mục sản phẩm nào!");
                }
            } else {
                return redirect()->back()->with("status", "Bạn chưa chọn danh mục sản phẩm nào để thực hiện hành động!");
            }
        } else {
            return redirect()->back()->with("status", "Bạn chưa chọn hành động nào để thực hiện!");
        }
    }

    public function delete($id)
    {
        ProductCat::where('cat_parent_id', $id)->update([
            'cat_parent_id' => -1,
        ]);
        $product_cat = ProductCat::withTrashed()->find($id);
        $product_cat_title = $product_cat->product_cat_title;

        if (empty($product_cat->deleted_at)) {
            ProductCat::where('id', $id)->update([
                'product_cat_status' => "trashed",
            ]);
            $product_cat->delete();
            return redirect()->back()->with("status", "Bạn đã xoá tạm thời danh mục sản phẩm có tiêu đề {$product_cat_title} thành công");
        } else {
            $product_cat->forceDelete();
            return redirect()->back()->with("status", "Bạn đã xoá vĩnh viễn danh mục sản phẩm có tiêu đề {$product_cat_title} thành công");
        }

    }

    public function restore($id)
    {
        $product_cat = ProductCat::withTrashed()->find($id);
        $product_cat_title = $product_cat->product_cat_title;
        $product_cat->restore();
        ProductCat::where('id', $id)->update([
            'product_cat_status' => "pending",
        ]);
        return redirect("admin/product/cat/list")->with("status", "Bạn đã khôi phục danh mục sản phẩm có tiêu đề {$product_cat_title} thành công");

    }
}

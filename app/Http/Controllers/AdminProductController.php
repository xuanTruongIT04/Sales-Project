<?php

namespace App\Http\Controllers;

use App\Image;
use App\Product;
use App\ProductCat;
use Illuminate\Http\Request;

class AdminProductController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => "product"]);
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
            $products = Product::withoutTrashed()->where("product_name", "LIKE", "%{$key_word}%")->orderbyDesc("created_at")->Paginate(20);
        } else if ($status == "licensed") {
            $list_act = [
                "pending" => "Chờ xét duyệt",
                "delete" => "Xoá tạm thời",
            ];
            $products = Product::withoutTrashed()->where("product_status", "licensed")->where("product_name", "LIKE", "%{$key_word}%")->orderbyDesc("created_at")->Paginate(20);
        } else if ($status == "pending") {
            $list_act = [
                "licensed" => "Đã đăng",
                "delete" => "Xoá tạm thời",
            ];
            $products = Product::withoutTrashed()->where("product_status", "pending")->where("product_name", "LIKE", "%{$key_word}%")->orderbyDesc("created_at")->Paginate(20);
        } else if ($status == "trashed") {
            $list_act = [
                "restore" => "Khôi phục",
                "delete_permanently" => "Xoá vĩnh viễn",
            ];
            $products = Product::onlyTrashed()->where("product_name", "LIKE", "%{$key_word}%")->orderbyDesc("created_at")->Paginate(20);
        }

        $count_product = $products->total();
        $cnt_product_active = Product::withoutTrashed()->count();
        $cnt_product_licensed = Product::withoutTrashed()->where("product_status", "licensed")->count();
        $cnt_product_pending = Product::withoutTrashed()->where("product_status", "pending")->count();
        $cnt_product_trashed = Product::onlyTrashed()->count();
        $count_product_status = [$cnt_product_active, $cnt_product_licensed, $cnt_product_pending, $cnt_product_trashed];

        // Truyền các role:
        return view("admin.product.list", compact('products', "count_product", "count_product_status", "list_act"));
    }

    public function add()
    {
        $list_product_cat = ProductCat::all();
        return view('admin.product.add', compact("list_product_cat"));
    }

    public function store(Request $requests)
    {
        if ($requests->input('btn_add')) {
            $product_name = $requests->input('product_name');

            $requests->validate(
                [
                    'product_name' => ['required', 'string', 'max:255'],
                    'slug' => ['required', 'string', 'max:300'],
                    'product_desc' => ['required'],
                    'product_detail' => ['required'],
                    "product_thumb" => ['required', 'file', "mimes:jpeg,png,jpg,gif", 'max:21000'],
                    'price_old' => ['required', 'numeric', 'min:0'],
                    'qty_remain' => ['required', 'numeric', 'min:0'],
                    'product_cat' => ['required', 'integer'],
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
                    "product_name" => "Tên sản phẩm",
                    "slug" => "Đường dẫn thân thiện",
                    "product_desc" => "Mô tả sản phẩm",
                    "product_detail" => "Chi tiết sản phẩm",
                    "product_thumb" => "Đường dẫn ảnh",
                    "price_old" => "Giá sản phẩm",
                    "qty_remain" => "Số lượng kho",
                    "product_cat" => "Danh mục sản phẩm",
                ]
            );

            $slug = $requests->input("slug");
            $product_code = code_product_format($slug);

            $product = Product::create([
                'product_code' => $product_code,
                'product_name' => $requests->input("product_name"),
                'product_desc' => $requests->input("product_desc"),
                'product_detail' => $requests->input("product_detail"),
                'slug' => $slug,
                'price_old' => $requests->input("price_old"),
                'price_new' => null,
                'qty_sold' => null,
                'qty_remain' => $requests->input("qty_remain"),
                'product_cat_id' => $requests->input("product_cat"),
            ]);

            if ($requests->hasFile("product_thumb")) {
                $file = $requests->product_thumb;
                $file_name = $file->getClientOriginalName();

                $file_ext = $file->getClientOriginalExtension();

                $file_size = $file->getSize();

                $path = $file->move("public/uploads", $file->getClientOriginalName());

                $thumbnail = "public/uploads/" . $file_name;
            }

            $product_id = $product->id;
            Image::create([
                'image_link' => $thumbnail,
                'rank' => "1",
                'product_id' => $product_id,
            ]);

            return redirect("admin/product/list")->with("status", "Đã thêm sản phẩm có tên {$product_name} thành công");
        }
    }

    public function edit($id)
    {
        $product = Product::find($id);
        $list_product_cat = ProductCat::all();
        return view('admin.product.edit', compact("list_product_cat", "product"));
    }

    public function update(Request $requests, $id)
    {
        if ($requests->input('btn_update')) {
            $product_name = $requests->input("product_name");

            $requests->validate(
                [
                    'product_name' => ['required', 'string', 'max:255'],
                    'slug' => ['required', 'string', 'max:300'],
                    'product_desc' => ['required'],
                    'product_detail' => ['required'],
                    "product_thumb" => ['required', 'file', "mimes:jpeg,png,jpg,gif", 'max:21000'],
                    'price_new' => ['required', 'numeric', 'min:0'],
                    'price_old' => ['required', 'numeric', 'min:0'],
                    'qty_remain' => ['required', 'numeric', 'min:0'],
                    'qty_sold' => ['required', 'numeric', 'min:0'],
                    'product_cat' => ['required', 'integer'],
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
                    "product_name" => "Tên sản phẩm",
                    "slug" => "Đường dẫn thân thiện",
                    "product_desc" => "Mô tả sản phẩm",
                    "product_detail" => "Chi tiết sản phẩm",
                    "product_thumb" => "Đường dẫn ảnh",
                    "price_new" => "Giá sản phẩm mới",
                    "price_old" => "Giá sản phẩm cũ",
                    "qty_remain" => "Số lượng kho",
                    "qty_remain" => "Số lượng đã bán",
                    "product_cat" => "Danh mục sản phẩm",
                ]
            );

            $product = Product::where('id', $id)->update([
                'product_name' => $requests->input("product_name"),
                'slug' => $requests->input("slug"),
                'product_desc' => $requests->input("product_desc"),
                'product_detail' => $requests->input("product_detail"),
                'price_old' => $requests->input("price_old"),
                'price_new' => $requests->input("price_new"),
                'qty_sold' => $requests->input("qty_sold"),
                'qty_remain' => $requests->input("qty_remain"),
                'product_cat_id' => $requests->input("product_cat"),
            ]);

            if ($requests->hasFile("product_thumb")) {
                $file = $requests->product_thumb;
                $file_name = $file->getClientOriginalName();

                $file_ext = $file->getClientOriginalExtension();

                $file_size = $file->getSize();

                $path = $file->move("public/uploads", $file->getClientOriginalName());

                $thumbnail = "public/uploads/" . $file_name;
            }

            Image::where("product_id", $id)->update([
                'rank' => "0",
            ]);

            Image::create([
                'image_link' => $thumbnail,
                'rank' => "1",
                'product_id' => $id,
            ]);

            return redirect("admin/product/list")->with("status", "Đã cập nhật thông tin sản phẩm có tên {$product_name} thành công");
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
                            Product::where('id', $id)->update([
                                'product_status' => "trashed",
                            ]);
                        }
                        Product::destroy($list_checked);
                        return redirect("admin/product/list")->with("status", "Bạn đã xoá tạm thời {$cnt_member} sản phẩm  thành công!");
                    } else if ($act == "licensed") {
                        foreach ($list_checked as $id) {
                            Product::where('id', $id)->update([
                                'product_status' => "licensed",
                            ]);
                        }
                        return redirect("admin/product/list")->with("status", "Bạn đã cấp quyền {$cnt_member} sản phẩm  thành công");
                    } else if ($act == "pending") {
                        foreach ($list_checked as $id) {
                            Product::where('id', $id)->update([
                                'product_status' => "pending",
                            ]);
                        }
                        return redirect("admin/product/list")->with("status", "Bạn đã xét trạng thái chờ {$cnt_member} sản phẩm  thành công");
                    } else if ($act == "delete_permanently") {
                        Product::onlyTrashed()
                            ->whereIn("id", $list_checked)
                            ->forceDelete();
                        return redirect("admin/product/list")->with("status", "Bạn đã xoá vĩnh viễn {$cnt_member} sản phẩm  thành công");
                    } else if ($act == "restore") {
                        Product::onlyTrashed()
                            ->whereIn("id", $list_checked)
                            ->restore();
                        foreach ($list_checked as $id) {
                            Product::where('id', $id)->update([
                                'product_status' => "pending",
                            ]);
                        }
                        return redirect("admin/product/list")->with("status", "Bạn đã khôi phục {$cnt_member} sản phẩm  thành công");
                    }
                } else {
                    return redirect()->back()->with("status", "Không tìm thấy sản phẩm nào!");
                }
            } else {
                return redirect()->back()->with("status", "Bạn chưa chọn sản phẩm nào để thực hiện hành động!");
            }
        } else {
            return redirect()->back()->with("status", "Bạn chưa chọn hành động nào để thực hiện!");
        }
    }

    public function delete($id)
    {
        $product = Product::withTrashed()->find($id);
        $product_name = $product->product_name;

        if (empty($product->deleted_at)) {
            Product::where('id', $id)->update([
                'product_status' => "trashed",
            ]);
            $product->delete();
            return redirect()->back()->with("status", "Bạn đã xoá tạm thời sản phẩm có tên {$product_name} thành công");
        } else {
            $product->forceDelete();
            return redirect()->back()->with("status", "Bạn đã xoá vĩnh viễn sản phẩm có tên {$product_name} thành công");
        }

    }

    public function restore($id)
    {
        $product = Product::withTrashed()->find($id);
        $product_name = $product->product_name;
        $product->restore();
        Product::where('id', $id)->update([
            'product_status' => "pending",
        ]);
        return redirect("admin/product/list")->with("status", "Bạn đã khôi phục sản phẩm có tên {$product_name} thành công");

    }
}

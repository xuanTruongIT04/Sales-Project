<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => "order"]);
            return $next($request);
        });
    }
    function list(Request $requests) {
        $status = !empty(request()->input('status')) ? $requests->input('status') : 'active';
        $list_act = [
            "delivery_successful" => "Giao hàng thành công",
            "shipping" => "Đang vận chuyển",
            "pending" => "Chờ xét duyệt",
            "delete" => "Xoá tạm thời",
        ];

        $key_word = "";

        if ($requests->input("key_word")) {
            $key_word = $requests->input("key_word");
        }

        if ($status == "active") {
            $orders = Order::withoutTrashed()->where("order_code", "LIKE", "%{$key_word}%")->orderbyDesc("created_at")->Paginate(20);
        } else if ($status == "delivery_successful") {
            $list_act = [
                "shipping" => "Đang vận chuyển",
                "pending" => "Chờ xét duyệt",
                "delete" => "Xoá tạm thời",
            ];
            $orders = Order::withoutTrashed()->where("order_status", "delivery_successful")->where("order_code", "LIKE", "%{$key_word}%")->orderbyDesc("created_at")->Paginate(20);
        } else if ($status == "shipping") {
            $list_act = [
                "delivery_successful" => "Giao hàng thành công",
                "pending" => "Chờ xét duyệt",
                "delete" => "Xoá tạm thời",
            ];
            $orders = Order::withoutTrashed()->where("order_status", "shipping")->where("order_code", "LIKE", "%{$key_word}%")->orderbyDesc("created_at")->Paginate(20);
        } else if ($status == "pending") {
            $list_act = [
                "delivery_successful" => "Giao hàng thành công",
                "shipping" => "Đang vận chuyển",
                "delete" => "Xoá tạm thời",
            ];
            $orders = Order::withoutTrashed()->where("order_status", "pending")->where("order_code", "LIKE", "%{$key_word}%")->orderbyDesc("created_at")->Paginate(20);
        } else if ($status == "trashed") {
            $list_act = [
                "restore" => "Khôi phục",
                "delete_permanently" => "Xoá vĩnh viễn",
            ];
            $orders = Order::onlyTrashed()->where("order_code", "LIKE", "%{$key_word}%")->orderbyDesc("created_at")->Paginate(20);
        }

        $count_order = $orders->total();
        $cnt_order_active = Order::withoutTrashed()->count();
        $cnt_order_delivery_successful = Order::withoutTrashed()->where("order_status", "delivery_successful")->count();
        $cnt_order_shipping = Order::withoutTrashed()->where("order_status", "shipping")->count();
        $cnt_order_pending = Order::withoutTrashed()->where("order_status", "pending")->count();
        $cnt_order_trashed = Order::onlyTrashed()->count();
        $count_order_status = [$cnt_order_active, $cnt_order_delivery_successful, $cnt_order_shipping, $cnt_order_pending, $cnt_order_trashed];

        // Truyền các role:
        return view("admin.order.list", compact('orders', "count_order", "count_order_status", "list_act"));
    }

    public function edit($id)
    {
        $order = Order::find($id);

        return view("admin.order.edit", compact("order"));
    }

    public function update(Request $requests, $id)
    {
        if ($requests->input('btn_update')) {
            $order_code = $requests->input("order_code");
            $requests->validate(
                [
                    'order_code' => ['required', 'string', 'max:255'],
                    'customer_name' => ['required', 'string', 'max:300'],
                    'number_phone' => "required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10",
                    'email' => ['required', 'string', 'email', 'max:255'],
                    "address_delivery" => ['required', 'string', 'max:300'],
                    "payment_method" => ['required'],
                    "order_status" => ['required'],
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
                    "order_code" => "Mã đơn hàng",
                    "customer_name" => "Tên khách hàng",
                    "number_phone" => "Số điện thoại",
                    "email" => "Địa chỉ email",
                    "address_delivery" => "Địa chỉ nhận hàng",
                    "payment_method" => "Hình thức thanh toán",
                    "order_status" => "Trạng thái đơn hàng",
                ]
            );

            $customer_id = Order::find($id)->customer->id;
            Customer::where('id', $customer_id)->update([
                'customer_name' => $requests->input("customer_name"),
                'number_phone' => $requests->input("number_phone"),
                'email' => $requests->input("email"),
            ]);

            Order::where('id', $id)->update([
                'order_code' => $requests->input("order_code"),
                'address_delivery' => $requests->input("address_delivery"),
                'payment_method' => $requests->input("payment_method"),
                'order_status' => $requests->input("order_status"),
            ]);

            return redirect("admin/order/list")->with("status", "Đã cập nhật thông tin đơn hàng có mã {$order_code} thành công");
        }
    }

    public function detail($id)
    {
        $order = Order::find($id);
        $list_product = get_order_product($id);

        return view("admin.order.detail", compact("order", "list_product"));
    }

    public function detailUpdate(Request $requests, $id)
    {
        if ($requests->input('btn_update')) {
            $requests->validate(
                [
                    "payment_method" => ['required'],
                    "order_status" => ['required'],
                ],
                [
                    'required' => ":attribute không được để trống",
                ],
                [
                    "payment_method" => "Hình thức thanh toán",
                    "order_status" => "Trạng thái đơn hàng",
                ]
            );

            $order_code = Order::find($id)->order_code;

            Order::where('id', $id)->update([
                'payment_method' => $requests->input("payment_method"),
                'order_status' => $requests->input("order_status"),
            ]);
            return redirect("admin/order/list")->with("status", "Đã cập nhật thông tin đơn hàng có mã {$order_code} thành công");
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
                            Order::where('id', $id)->update([
                                'order_status' => "trashed",
                            ]);
                        }
                        Order::destroy($list_checked);
                        return redirect("admin/order/list")->with("status", "Bạn đã xoá tạm thời {$cnt_member} đơn hàng thành công!");
                    } else if ($act == "delivery_successful") {
                        foreach ($list_checked as $id) {
                            Order::where('id', $id)->update([
                                'order_status' => "delivery_successful",
                            ]);
                        }
                        return redirect("admin/order/list")->with("status", "Bạn đã đặt trạng thái giao hàng thành công cho {$cnt_member} đơn hàng thành công");
                    } else if ($act == "shipping") {
                        foreach ($list_checked as $id) {
                            Order::where('id', $id)->update([
                                'order_status' => "shipping",
                            ]);
                        }
                        return redirect("admin/order/list")->with("status", "Bạn đã đặt trạng thái đang vận chuyển cho {$cnt_member} đơn hàng thành công");
                    } else if ($act == "pending") {
                        foreach ($list_checked as $id) {
                            Order::where('id', $id)->update([
                                'order_status' => "pending",
                            ]);
                        }
                        return redirect("admin/order/list")->with("status", "Bạn đã đặt trạng thái chờ {$cnt_member} đơn hàng thành công");
                    } else if ($act == "delete_permanently") {
                        Order::onlyTrashed()
                            ->whereIn("id", $list_checked)
                            ->forceDelete();
                        return redirect("admin/order/list")->with("status", "Bạn đã xoá vĩnh viễn {$cnt_member} đơn hàng thành công");
                    } else if ($act == "restore") {
                        Order::onlyTrashed()
                            ->whereIn("id", $list_checked)
                            ->restore();
                        foreach ($list_checked as $id) {
                            Order::where('id', $id)->update([
                                'order_status' => "pending",
                            ]);
                        }
                        return redirect("admin/order/list")->with("status", "Bạn đã khôi phục {$cnt_member} đơn hàng thành công");
                    }
                } else {
                    return redirect()->back()->with("status", "Không tìm thấy đơn hàng nào!");
                }
            } else {
                return redirect()->back()->with("status", "Bạn chưa chọn đơn hàng nào để thực hiện hành động!");
            }
        } else {
            return redirect()->back()->with("status", "Bạn chưa chọn hành động nào để thực hiện!");
        }
    }

    public function delete($id)
    {
        $order = Order::withTrashed()->find($id);
        $order_code = $order->order_code;

        if (empty($order->deleted_at)) {
            Order::where('id', $id)->update([
                'order_status' => "trashed",
            ]);
            $order->delete();
            return redirect()->back()->with("status", "Bạn đã xoá tạm thời đơn hàng có mã {$order_code} thành công");
        } else {
            $order->forceDelete();
            return redirect()->back()->with("status", "Bạn đã xoá vĩnh viễn đơn hàng có mã {$order_code} thành công");
        }

    }

    public function restore($id)
    {
        $order = Order::withTrashed()->find($id);
        $order_code = $order->order_code;
        $order->restore();
        Order::where('id', $id)->update([
            'order_status' => "pending",
        ]);
        return redirect("admin/order/list")->with("status", "Bạn đã khôi phục đơn hàng có mã {$order_code} thành công");

    }
}

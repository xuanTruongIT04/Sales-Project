<?php

namespace App\Http\Controllers;

use App\Customer;
use Illuminate\Http\Request;

class AdminCustomerController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => "customer"]);
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
            $customers = Customer::onlyTrashed()->where("customer_name", "LIKE", "%{$key_word}%")->orderbyDesc("created_at")->Paginate(20);
        } else {
            $customers = Customer::withoutTrashed()->where("customer_name", "LIKE", "%{$key_word}%")->orderbyDesc("created_at")->Paginate(20);
        }

        $count_customer = $customers->total();
        $cnt_customer_active = Customer::withoutTrashed()->count();
        $cnt_customer_trashed = Customer::onlyTrashed()->count();
        $count_customer_status = [$cnt_customer_active, $cnt_customer_trashed];
        return view("admin.customer.list", compact('customers', "count_customer", "count_customer_status", "list_act"));
    }

    public function edit($id)
    {
        $customer = Customer::find($id);

        return view("admin.customer.edit", compact("customer"));
    }

    public function update(Request $requests, $id)
    {
        if ($requests->input('btn_update')) {
            $requests->validate(
                [
                    'customer_name' => ['required', 'string', 'max:300'],
                    'number_phone' => "required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10",
                    'email' => ['required', 'string', 'email', 'max:255'],
                ],
                [
                    'required' => ":attribute không được để trống",
                    'max' => ":attribute có độ dài ít nhất :max ký tự",
                ],
                [
                    "customer_name" => "Tên khách hàng",
                    "number_phone" => "Số điện thoại",
                    "email" => "Địa chỉ email",
                ]
            );

            $customer_name = $requests->input("customer_name");

            Customer::where('id', $id)->update([
                'customer_name' => $requests->input("customer_name"),
                'number_phone' => $requests->input("number_phone"),
                'email' => $requests->input("email"),
            ]);

            return redirect("admin/customer/list")->with("status", "Đã cập nhật thông tin khách hàng tên {$customer_name} thành công");
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
                        Customer::destroy($list_checked);
                        return redirect("admin/customer/list")->with("status", "Bạn đã xoá tạm thời {$cnt_member} khách hàng thành công!");
                    } else if ($act == "delete_permanently") {
                        Customer::onlyTrashed()
                            ->whereIn("id", $list_checked)
                            ->forceDelete();
                        return redirect()->back()->with("status", "Bạn đã xoá vĩnh viễn {$cnt_member} khách hàng thành công");
                    } else if ($act == "restore") {
                        Customer::onlyTrashed()
                            ->whereIn("id", $list_checked)
                            ->restore();
                        return redirect("admin/customer/list")->with("status", "Bạn đã khôi phục {$cnt_member} khách hàng thành công");
                    }
                } else {
                    return redirect()->back()->with("status", "Không tìm thấy khách hàng nào!");
                }
            } else {
                return redirect()->back()->with("status", "Bạn chưa chọn khách hàng nào để thực hiện hành động!");
            }
        } else {
            return redirect()->back()->with("status", "Bạn chưa chọn hành động nào để thực hiện!");
        }
    }

    public function delete($id)
    {
        $customer = Customer::withTrashed()->find($id);
        $customer_name = $customer->customer_name;

        if (empty($customer->deleted_at)) {
            $customer->delete();
            return redirect()->back()->with("status", "Bạn đã xoá tạm thời khách hàng tên {$customer_name} thành công");
        } else {

            $customer->forceDelete();
            return redirect()->back()->with("status", "Bạn đã xoá vĩnh viễn khách hàng tên {$customer_name} thành công");
        }
    }

    public function restore($id)
    {
        $customer = Customer::withTrashed()->find($id);
        $customer_name = $customer->customer_name;
        $customer->restore();
        return redirect("admin/customer/list")->with("status", "Bạn đã khôi phục khách hàng tên {$customer_name} thành công");
    }

}

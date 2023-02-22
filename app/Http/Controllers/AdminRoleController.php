<?php

namespace App\Http\Controllers;

use App\Role;
use Illuminate\Http\Request;

class AdminRoleController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => "role"]);
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
            $roles = Role::onlyTrashed()->where("name_role", "LIKE", "%{$key_word}%")->orderbyDesc("created_at")->Paginate(20);
        } else {
            $roles = Role::withoutTrashed()->where("name_role", "LIKE", "%{$key_word}%")->orderbyDesc("created_at")->Paginate(20);
        }

        $count_role = $roles->total();
        $cnt_role_active = Role::withoutTrashed()->count();
        $cnt_role_trashed = Role::onlyTrashed()->count();
        $count_role_status = [$cnt_role_active, $cnt_role_trashed];
        return view("admin.role.list", compact('roles', "count_role", "count_role_status", "list_act"));
    }

    public function add()
    {
        $roles = Role::all();
        return view('admin.role.add', compact("roles"));
    }

    public function store(Request $requests)
    {
        if ($requests->input('btn_add')) {
            $requests->validate(
                [
                    'name_role' => ['required', 'string', 'max:300'],
                ],
                [
                    'required' => ":attribute không được để trống",
                    'max' => ":attribute có độ dài ít nhất :max ký tự",
                ],
                [
                    "name_role" => "Tên quyền",
                ]
            );

            $name_role = $requests->input("name_role");

            Role::create([
                'name_role' => $name_role,
            ]);

            return redirect("admin/role/list")->with("status", "Đã thêm quyền tên {$name_role} thành công");
        }
    }

    public function edit($id)
    {
        $roles = Role::all();
        $role = Role::find($id);

        return view("admin.role.edit", compact("role", "roles"));
    }

    public function update(Request $requests, $id)
    {
        if ($requests->input('btn_update')) {
            $requests->validate(
                [
                    'name_role' => ['required', 'string', 'max:300'],
                ],
                [
                    'required' => ":attribute không được để trống",
                    'max' => ":attribute có độ dài ít nhất :max ký tự",
                ],
                [
                    "name_role" => "Tên quyền",
                ]
            );

            $name_role = $requests->input("name_role");

            Role::where('id', $id)->update([
                'name_role' => $name_role,
            ]);

            return redirect("admin/role/list")->with("status", "Đã cập nhật thông tin quyền tên {$name_role} thành công");
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
                        Role::destroy($list_checked);
                        return redirect("admin/role/list")->with("status", "Bạn đã xoá tạm thời {$cnt_member} quyền thành công!");
                    } else if ($act == "delete_permanently") {
                        Role::onlyTrashed()
                            ->whereIn("id", $list_checked)
                            ->forceDelete();
                        return redirect()->back()->with("status", "Bạn đã xoá vĩnh viễn {$cnt_member} quyền thành công");
                    } else if ($act == "restore") {
                        Role::onlyTrashed()
                            ->whereIn("id", $list_checked)
                            ->restore();
                        return redirect("admin/role/list")->with("status", "Bạn đã khôi phục {$cnt_member} quyền thành công");
                    }
                } else {
                    return redirect()->back()->with("status", "Không tìm thấy quyền nào!");
                }
            } else {
                return redirect()->back()->with("status", "Bạn chưa chọn quyền nào để thực hiện hành động!");
            }
        } else {
            return redirect()->back()->with("status", "Bạn chưa chọn hành động nào để thực hiện!");
        }
    }

    public function delete($id)
    {
        $role = Role::withTrashed()->find($id);
        $name_role = $role->name_role;

        if (empty($role->deleted_at)) {
            $role->delete();
            return redirect()->back()->with("status", "Bạn đã xoá tạm thời quyền tên {$name_role} thành công");
        } else {

            $role->forceDelete();
            return redirect()->back()->with("status", "Bạn đã xoá vĩnh viễn quyền tên {$name_role} thành công");
        }
    }

    public function restore($id)
    {
        $role = Role::withTrashed()->find($id);
        $name_role = $role->name_role;
        $role->restore();
        return redirect("admin/role/list")->with("status", "Bạn đã khôi phục quyền tên {$name_role} thành công");
    }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Role;
use App\Image;
use Illuminate\Support\Facades\Auth;

class AdminUserController extends Controller
{
    //
    function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => "user"]);
            return $next($request);
        });
    }

    function list(Request $requests)
    {
        $status = !empty(request()->input('status')) ? $requests->input('status') : 'active';
        $list_act = [
            "licensed" => "Đã cấp quyền",
            "pending" => "Chờ xét duyệt",
            "delete" => "Xoá tạm thời"
        ];

        $key_word = "";

        if ($requests->input("key_word")) {
            $key_word = $requests->input("key_word");
        }


        if ($status == "active") {
            $users = User::withoutTrashed()->where("fullname", "LIKE", "%{$key_word}%")->orderbyDesc("created_at")->Paginate(20);
        } else if ($status == "licensed") {
            $list_act = [
                "pending" => "Chờ xét duyệt",
                "delete" => "Xoá tạm thời"
            ];
            $users = User::withoutTrashed()->where("user_status", "licensed")->where("fullname", "LIKE", "%{$key_word}%")->orderbyDesc("created_at")->Paginate(20);
        } else if ($status == "pending") {
            $list_act = [
                "licensed" => "Đã cấp quyền",
                "delete" => "Xoá tạm thời"
            ];
            $users = User::withoutTrashed()->where("user_status", "pending")->where("fullname", "LIKE", "%{$key_word}%")->orderbyDesc("created_at")->Paginate(20);
        } else if ($status == "trashed") {
            $list_act = [
                "restore" => "Khôi phục",
                "delete_permanently" => "Xoá vĩnh viễn"
            ];
            $users = User::onlyTrashed()->where("fullname", "LIKE", "%{$key_word}%")->orderbyDesc("created_at")->Paginate(20);
        }

        $count_user = $users->total();
        $cnt_user_active = User::withoutTrashed()->count();
        $cnt_user_licensed = User::withoutTrashed()->where("user_status", "licensed")->count();
        $cnt_user_pending = User::withoutTrashed()->where("user_status", "pending")->count();
        $cnt_user_trashed = User::onlyTrashed()->count();
        $count_user_status = [$cnt_user_active, $cnt_user_licensed, $cnt_user_pending, $cnt_user_trashed];

        // Truyền các role:
        return view("admin.user.list", compact('users', "count_user", "count_user_status", "list_act"));
    }

    function add()
    {
        $roles = Role::all();
        return view('admin.user.add', compact("roles"));
    }

    function store(Request $requests)
    {
        if ($requests->input('btn_add')) {
            $fullname = $requests->input('fullname');

            $requests->validate(
                [
                    'fullname' => ['required', 'string', 'max:255'],
                    'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                    'password' => ['required', 'string', 'min:8', 'confirmed'],
                    'password_confirmation' => ['required', 'string', 'min:8'],
                    'user_thumb' => ['required', 'file', "mimes:jpeg,png,jpg,gif", 'max:21000'],
                    "role" => ['required'],
                ],
                [
                    "max" => [
                        "numeric" => ":attribute không được lớn hơn :max.",
                        "file" => ":attribute không được nhiều hơn :max KB.",
                        "string" => ":attribute không được nhiều hơn :max kí tự.",
                        "array" => ":attribute không được nhiều hơn :max mục."
                    ],
                    "min" => [
                        "numeric" => ":attribute không được bé hơn :min.",
                        "file" => ":attribute không được ít hơn :min KB.",
                        "string" => ":attribute không được ít hơn :min kí tự.",
                        "array" => ":attribute phải có ít nhất :min mục."
                    ],
                ],
                [
                    "fullname" => "Tên người dùng",
                    "email" => "Email",
                    "password" => "Mật khẩu",
                    "password_confirmation" => "Xác nhận mật khẩu",
                    "role" => "Quyền",
                    "user_thumb" => "Đường dẫn ảnh"
                ]
            );

            if ($requests->hasFile("user_thumb")) {
                $file = $requests->user_thumb;
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
            
            User::create([
                'fullname' => $requests->input("fullname"),
                'email' => $requests->input("email"),
                'password' => Hash::make($requests->input("password")),
                'role_id' => $requests->input("role"),
                'image_id' => $image_id
            ]);

            return redirect("admin/user/list")->with("status", "Đã thêm thành viên tên {$fullname} thành công");
        }
    }

    function edit($id)
    {
        $roles = Role::all();
        $user = User::find($id);

        return view("admin.user.edit", compact("user", "roles"));
    }

    function update(Request $requests, $id)
    {
        if ($requests->input('btn_update')) {
            $fullname = $requests->input("fullname");

            $requests->validate(
                [
                    'fullname' => ['required', 'string', 'max:255'],
                    'password' => ['required', 'string', 'min:8', 'confirmed'],
                    'password_confirmation' => ['required', 'string', 'min:8'],
                    'user_thumb' => ['required', 'file', 'max:21000'],
                    "role" => ['required'],
                ],
                [
                    'required' => ":attribute không được để trống",
                    'unique' => ":attribute đã được đăng ký, xin vui lòng nhập :attribute khác",
                    "max" => [
                        "numeric" => ":attribute không được lớn hơn :max.",
                        "file" => ":attribute không được nhiều hơn :max KB.",
                        "string" => ":attribute không được nhiều hơn :max kí tự.",
                        "array" => ":attribute không được nhiều hơn :max mục."
                    ],
                    "min" => [
                        "numeric" => ":attribute không được bé hơn :min.",
                        "file" => ":attribute không được ít hơn :min KB.",
                        "string" => ":attribute không được ít hơn :min kí tự.",
                        "array" => ":attribute phải có ít nhất :min mục."
                    ],
                    "confirmed" => ":attribute không giống nhau",
                ],
                [
                    "fullname" => "Tên người dùng",
                    "password" => "Mật khẩu",
                    "password_confirmation" => "Xác nhận mật khẩu",
                    "role" => "Quyền",
                    "user_thumb" => "Đường dẫn ảnh"
                ]
            );

            if ($requests->hasFile("user_thumb")) {
                $file = $requests->user_thumb;
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
            
            User::where('id', $id)->update([
                'fullname' => $requests->input("fullname"),
                'password' => Hash::make($requests->input("password")),
                'role_id' => $requests->input("role"),
                'image_id' => $image_id,
                'user_status' => $requests->input("status")
            ]);
            
            return redirect("admin/user/list")->with("status", "Đã cập nhật thông tin thành viên tên {$fullname} thành công");
        }
    }

    function action(Request $requests)
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
                        foreach ($list_checked as $id) {
                            User::where('id', $id)->update([
                                'user_status' => "trashed"
                            ]);
                        }
                        User::destroy($list_checked);
                        return redirect("admin/user/list")->with("status", "Bạn đã xoá tạm thời {$cnt_member} thành viên thành công!");
                    } else if ($act == "licensed") {
                        foreach ($list_checked as $id) {
                            User::where('id', $id)->update([
                                'user_status' => "licensed"
                            ]);
                        }
                        return redirect("admin/user/list")->with("status", "Bạn đã cấp quyền {$cnt_member} thành viên thành công");
                    } else if ($act == "pending") {
                        foreach ($list_checked as $id) {
                            User::where('id', $id)->update([
                                'user_status' => "pending"
                            ]);
                        }
                        return redirect("admin/user/list")->with("status", "Bạn đã xét trạng thái chờ {$cnt_member} thành viên thành công");
                    } else if ($act == "delete_permanently") {
                        User::onlyTrashed()
                            ->whereIn("id", $list_checked)
                            ->forceDelete();
                        return redirect("admin/user/list")->with("status", "Bạn đã xoá vĩnh viễn {$cnt_member} thành viên thành công");
                    } else if ($act == "restore") {
                        User::onlyTrashed()
                            ->whereIn("id", $list_checked)
                            ->restore();
                        foreach ($list_checked as $id) {
                            User::where('id', $id)->update([
                                'user_status' => "pending"
                            ]);
                        }
                        return redirect("admin/user/list")->with("status", "Bạn đã khôi phục {$cnt_member} thành viên thành công");
                    }
                } else {
                    return redirect("admin/user/list")->with("status", "Bạn không thể tự thao tác chính mình");
                }
            } else {
                return redirect("admin/user/list")->with("status", "Bạn chưa chọn thành viên nào để thực hiện hành động!");
            }
        } else {
            return redirect("admin/user/list")->with("status", "Bạn chưa chọn hành động nào để thực hiện!");
        }
    }

    function delete($id)
    {
        if (Auth::id() != $id) {
            $user = User::withTrashed()->find($id);
            $fullname = $user->fullname;

            if (empty($user->deleted_at)) {
                User::where('id', $id)->update([
                    'user_status' => "trashed"
                ]);
                $user->delete();
                return redirect()->back()->with("status", "Bạn đã xoá tạm thời thành viên tên {$fullname} thành công");
            } else {
                $user->forceDelete();
                return redirect()->back()->with("status", "Bạn đã xoá vĩnh viễn thành viên tên {$fullname} thành công");
            }
        } else
            return redirect()->back()->with("status", "Bạn không thể tự xoá chính mình ra khỏi hệ thống");
    }

    function restore($id)
    {
        if (Auth::id() != $id) {
            $user = User::withTrashed()->find($id);
            $fullname = $user->fullname;
            $user->restore();
            User::where('id', $id)->update([
                'user_status' => "pending"
            ]);
            return redirect("admin/user/list")->with("status", "Bạn đã khôi phục thành viên tên {$fullname} thành công");
        } else
            return redirect("admin/user/list")->with("status", "Bạn không thể tự khôi phục chính mình ra khỏi hệ thống");
    }
}

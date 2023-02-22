@extends('layouts.admin')


@section('content')
<div id="content" class="container-fluid">
    <div class="card">
        <div class="card-header font-weight-bold">
            Thêm quản trị viên
        </div>
        <div class="card-body">
            <form action="{{ url('admin/user/store') }}" method='POST' enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="name" class="fw-550">Họ và tên</label>
                    <input class="form-control" type="text" name="fullname" id="name" value="{{ Old('fullname') }}">
                    @error('fullname')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input class="form-control" type="text" name="email" id="email" value="{{ Old('email') }}">
                    @error('email')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="password" class="fw-550">Mật khẩu</label>
                    <input class="form-control" type="password" name="password" id="password">
                    @error('password')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="password-confirm" class="fw-550">Xác nhận mật khẩu</label>
                    <input class="form-control" type="password" name="password_confirmation" id="password-confirm">
                    @error('password_confirmation')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for='user_thumb' class="fw-550">Ảnh đại diện</label> <BR>
                    <div id="uploadFile">
                        <input type="file" name="user_thumb" class="form-control-file upload_file" id="user_thumb" onchange="upload_image(this)">
                        <img src={{ url("public/uploads/img-admin-1.jpg") }} id="image_upload_file">
                    </div>

                    @error('user_thumb')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="role" class="fw-550">Nhóm quyền</label>
                    <select class="form-control" id="role" name="role">
                        <option value="">Chọn quyền</option>
                        @foreach ($roles as $k => $role)
                        <option value="{{ $role->id }}" @if (Old('role')==$role->id) selected="selected" @endif> {{
                            $role->name_role }}
                        </option>
                        @endforeach
                    </select>
                    @error('role')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <input type="submit" name="btn_add" class="btn btn-primary" value="Thêm mới">
            </form>
        </div>
    </div>
</div>
@endsection

@extends('layouts.admin')

@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                Chỉnh sửa thông tin quản trị viên
            </div>
            <div class="card-body">
                <form id="form-upload" action="{{ url("admin/user/update/{$user->id}") }}" method='POST'
                    enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="name" class="fw-550">Họ và tên</label>
                        <input class="form-control" type="text" name="fullname" id="name"
                            value="{{ $user->fullname }}">
                        @error('fullname')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="email" class="fw-550">Email</label>
                        <input class="form-control" type="text" name="email" id="email" value="{{ $user->email }}"
                            disabled>
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
                            <img src={{ url($user-> image -> image_link) }} id="image_upload_file">
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
                                <option value="{{ $role->id }}"
                                    @if ($user->role->id == $role->id) selected="selected" @endif>{{ $role->name_role }}
                                </option>
                            @endforeach
                        </select>
                        @error('role')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>



                    <div class="form-group">
                        <label for="status" class="fw-550">Trạng thái</label>

                        @if (!empty($user->user_status))
                            {!! template_update_status_user($user->user_status) !!}
                        @endif

                        @error('status')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror

                    </div>

                    <input type="submit" name="btn_update" class="btn btn-primary" value="Cập nhật">
                </form>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.admin')

@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                Chỉnh sửa thông tin quyền
            </div>
            <div class="card-body">
                <form action="{{ url("admin/role/update/{$role->id}") }}" method='POST'>
                    @csrf
                    <div class="form-group">
                        <label for="name" class="fw-550">Tên quyền</label>
                        <input class="form-control" type="text" name="name_role" id="name" value="{{ $role->name_role }}">
                        @error('name_role')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <input type="submit" name="btn_update" class="btn btn-primary" value="Thêm mới">
                </form>
            </div>
        </div>
    </div>
@endsection

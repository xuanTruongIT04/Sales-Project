@extends('layouts.admin')

@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                Thêm quyền
            </div>
            <div class="card-body">
                <form action="{{ url("admin/role/store") }}" method='POST'>
                    @csrf
                    <div class="form-group">
                        <label for="name" class="fw-550">Tên quyền</label>
                        <input class="form-control" type="text" name="name_role" id="name">
                        @error('name_role')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <input type="submit" name="btn_add" class="btn btn-primary" value="Thêm mới">
                </form> 
            </div>
        </div>
    </div>
@endsection

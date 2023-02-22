@extends('layouts.admin')

@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                Chỉnh sửa thông tin khách hàng
            </div>
            <div class="card-body">
                <form action="{{ url("admin/customer/update/{$customer->id}") }}" method='POST'>
                    @csrf
                    <div class="form-group">
                        <label for="name" class="fw-550">Tên khách hàng</label>
                        <input class="form-control" type="text" name="customer_name" id="name" value="{{ $customer->customer_name }}">
                        @error('customer_name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="number-phone" class="fw-550">Số điện thoại</label>
                        <input class="form-control" type="text" name="number_phone" id="number-phone" value="{{ $customer->number_phone }}">
                        @error('number_phone')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email" class="fw-550">Email</label>
                        <input class="form-control" type="text" name="email" id="email" value="{{ $customer->email }}">
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <input type="submit" name="btn_update" class="btn btn-primary" value="Cập nhật">
                </form>
            </div>
        </div>
    </div>
@endsection

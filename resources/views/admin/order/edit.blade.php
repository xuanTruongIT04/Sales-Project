@extends('layouts.admin')

@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                Chỉnh sửa thông tin đơn hàng
            </div>
            <div class="card-body">
                <form action="{{ url("admin/order/update/{$order->id}") }}" method='POST'>
                    @csrf
                    <div class="form-group">
                        <label for="order-code">Mã đơn hàng</label>
                        <input class="form-control no-edit" type="text" name="order_code" id="order-code"
                            readonly="readonly" value="{{ $order->order_code }}">
                        @error('order_code')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="name">Tên khách hàng</label>
                        <input class="form-control" type="text" name="customer_name" id="name"
                            value="{{ $order->customer->customer_name }}">
                        @error('customer_name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="number-phone">Số điện thoại</label>
                        <input class="form-control" type="text" name="number_phone" id="number-phone"
                            value="{{ $order->customer->number_phone }}">
                        @error('number_phone')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    {{-- protected $fillable = ['order_code', 'address_delivery', 'payment_method', 'notes', 'order_status', "customer_id" ]; --}}

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input class="form-control" type="email" name="email" id="email"
                            value="{{ $order->customer->email }}">
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="address-delivery">Địa chỉ nhận hàng</label>
                        <input class="form-control" type="text" name="address_delivery" id="address-delivery"
                            value="{{ $order->address_delivery }}">
                        @error('address_delivery')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="notes">Ghi chú từ khách hàng</label>
                        <textarea class="form-control no-edit" name="notes" readonly="readonly" id="notes" cols="30" rows="10">{{ $order->notes }}</textarea>
                        @error('notes')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="payment-method">Hình thức thanh toán</label> <BR>
                        {!! show_payment_method($order->payment_method) !!} <BR>
                        @error('order_status')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="status">Trạng thái đơn hàng</label><BR>
                        {!! show_order_status($order->order_status) !!}<BR>
                        @error('order_status')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <input type="submit" name="btn_update" class="btn btn-primary" value="Cập nhật">
                </form>
            </div>
        </div>
    </div>
@endsection

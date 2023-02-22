@extends('layouts.admin')

@section('content')
    <div id="content" class="container-fluid">

        <div class="card">
            <div class="card-header font-weight-bold">
                Sản phẩm đơn hàng
            </div>
            <div class="card-body" id="information-order">
                <table class="table table-striped table-checkall">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Ảnh sản phẩm</th>
                            <th scope="col">Tên sản phẩm</th>
                            <th scope="col">Đơn giá</th>
                            <th scope="col">Số lượng</th>
                            <th scope="col">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!empty($list_product))
                            @php
                                $sum_price = 0;
                                $sum_qty = 0;
                                $cnt = 0;
                            @endphp
                            @foreach ($list_product as $product)
                                @php
                                    $order_total = DB::table('order_product')
                                        ->where('order_product.order_id', $order->id)
                                        ->where('order_product.product_id', $product->id)
                                        ->first()->number_order;
                                    $cnt++;
                                @endphp
                                <tr class="row-in-list">
                                    <th scope=" row">{{ $cnt }}</th>
                                    <td>
                                        <a href="{{ route('admin.product.edit', $product->id) }}" class="thumbnail">
                                            <img src="@if (!empty(get_main_image($product->id))) {{ url(get_main_image($product->id)) }}@else{{ url('public/uploads/img-product2.png') }} @endif"
                                                alt="Ảnh của sản phẩm {{ $product->product_name }}"
                                                title="Ảnh của sản phẩm {{ $product->product_name }}" id="thumbnail_img">
                                        </a>
                                    </td>
                                    <td>{{ $product->product_name }}</td>
                                    @php
                                        $product_price = !empty($product->price_new) ? $product->price_new : $product->price_old;
                                    @endphp
                                    <td>{!! currency_format($product_price) !!}</td>
                                    <td>{{ $order_total }}</td>
                                    @php
                                        $price_total = $order_total * $product_price;
                                    @endphp
                                    <td><b>{!! currency_format($price_total) !!}</b></td>
                                    @php
                                        $sum_price += $price_total;
                                        $sum_qty += $order_total;
                                    @endphp
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="7" class="bg-white">Không tìm thấy trang nào!</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <div class="infor-order-detail">
            <div class="card total-price-order">
                <div class="card-header font-weight-bold">
                    Giá trị đơn hàng
                </div>
                <div class="card-body" id="information-order">
                    <div class="card text-white bg-info mb-3 mr-5 d-inline-block" style="max-width: 18rem;">
                        <div class="card-header">Tổng số lượng</div>
                        <div class="card-body">
                            <h5 class="card-title text-center">{{ $sum_qty }}</h5>
                            <p class="card-text text-center text-uppercase">sản phẩm</p>
                        </div>
                    </div>

                    <div class="card text-white bg-success mb-3 d-inline-block" style="max-width: 18rem;">
                        <div class="card-header">Tổng tiền đơn hàng</div>
                        <div class="card-body">
                            <h5 class="card-title text-center">{!! number_format($sum_price) !!}</h5>
                            <p class="card-text text-center text-uppercase">vnđ</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card detail-infor-order">
                <div class="card-header font-weight-bold">
                    Thông tin đơn hàng
                </div>
                <div class="card-body" id="information-order">
                    <form action="{{ url("admin/order/detail/update/{$order->id}") }}" method='POST'>
                        @csrf
                        <div class="form-group">
                            <h6 class="order-code">
                                <i class="fas fa-barcode"></i>Mã đơn hàng
                            </h6>
                            <span class="detail">{{ $order->order_code }}</span>
                        </div>

                        <div class="form-group">
                            <h6 class="address-delivery">
                                <i class="fas fa-map-marker-alt"></i>Địa chỉ nhận hàng / Số điện thoại
                            </h6>
                            <span class="detail">{{ $order->address_delivery }} / </span><span
                                class="detail">{{ $order->customer->number_phone }}</span>
                        </div>

                        <div class="form-group">
                            <h6 class="payment-method">
                                <i class="fab fa-opencart"></i>
                                Thông tin vận chuyển
                            </h6>
                            {!! show_payment_method($order->payment_method) !!}
                        </div>

                        <div class="form-group">
                            <h6 class="order-status">
                                <label for="status" class="fw-550">
                                    <i class="fas fa-chart-area"></i>Trạng thái đơn hàng
                                </label>
                            </h6>
                            {!! show_order_status($order->order_status) !!}
                            @error('order_status')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <input type="submit" name="btn_update" class="btn btn-primary btn-add-detail-order"
                            value="Cập nhật">
                    </form>
                </div>
            </div>

        </div>




    @endsection

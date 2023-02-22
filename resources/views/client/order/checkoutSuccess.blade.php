@extends('layouts.client')

@section('content')
    <div id="main-content-wp" class="checkout-page">
        <div class="section" id="breadcrumb-wp">
            <div class="wp-inner">
                <div class="section-detail">
                    <ul class="list-item clearfix">
                        <li>
                            <a href="trang-chu.html" title="Trang chủ">Trang chủ</a>
                        </li>
                        <li>
                            <a class="children-bread-crumb">Đặt hàng thành công</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div id="wrapper" class="wp-inner clearfix">
            <div class="section" id="order-success">
                <div class="section-head">
                    <p class="mess-order">
                        <img class="img-alert"
                            src="https://upload.wikimedia.org/wikipedia/commons/thumb/7/73/Flat_tick_icon.svg/1024px-Flat_tick_icon.svg.png"
                            alt="">
                        <span>Đặt hàng thành công!</span>
                    </p>
                    <p> Cảm ơn quý khách đã đặt hàng tại hệ thống Ismart!</p>
                    <p> Nhân viên chăm sóc Ismart sẽ liên hệ tới bạn sớm nhất có thể.</p>
                </div>
                @if (!empty($detail_order_success) && is_array($detail_order_success))
                    @php
                        $order_code = $detail_order_success['order_code'];
                        $customer_id = $detail_order_success['customer_id'];
                        $address_delivery = $detail_order_success['address_delivery'];
                        $notes = $detail_order_success['notes'];
                        
                        $customer = get_info_customer($customer_id);
                        if (!empty($customer)) {
                            $customer_name = $customer['customer_name'];
                            $email = $customer['email'];
                            $number_phone = $customer['number_phone'];
                        }
                        
                    @endphp
                    <div class="section-detail mt-5">
                        <h2 class="code_order">Mã đơn hàng: {{ $order_code }}</h2>
                        <table class="table table-border mt-3">
                            <h3 class="info-customer">Thông tin khách hàng</h3>
                            <thead class="thead" style="background-color: #008000a8; color: #FFF;">
                                <tr>
                                    <td>Họ và tên</td>
                                    <td>Số điện thoại</td>
                                    <td>Email</td>
                                    <td>Địa chỉ</td>
                                    <td>Ghi chú</td>
                                </tr>
                            </thead>
                            <tbody class="bg-white">
                                <tr>
                                    <td>{{ $customer_name }}</td>
                                    <td>{{ $number_phone }}</td>
                                    <td>{{ $email }}</td>
                                    <td>{{ $address_delivery }}</td>
                                    <td>{{ $notes }}</td>
                            </tbody>
                        </table>
                    </div>
                    <div class="section-detail mt-5">
                        <table class="table table-border product-bought">
                            <h3 class="info-customer">Sản phẩm đã mua</h3>
                            <thead class="thead" style="background-color: #008000a8; color: #FFF;">
                                <tr>
                                    <td>STT</td>
                                    <td>Ảnh sản phẩm</td>
                                    <td>Tên sản phẩm</td>
                                    <td>Giá sản phẩm</td>
                                    <td>Số lượng</td>
                                    <td>Thành tiền</td>
                                </tr>
                            </thead>
                            <tbody class="bg-white">
                                @php
                                    $stt = 0;
                                    $total_price = 0;
                                    $sub_total = 0;
                                    $qty_order = 0;
                                @endphp
                                @foreach ($list_product as $item)
                                    @php
                                        $qty_order = get_qty_order($item['id']);
                                        $sub_total = $item['price_new'] * $qty_order;
                                        $total_price += $sub_total;
                                        $product_thumb = get_product_main_thumb($item['id']);
                                        $stt++;
                                    @endphp
                                    <tr>
                                        <td style="vertical-align: middle;">{{ $stt }}</td>
                                        <td class="image-product-order"><img src="{!! url($product_thumb) !!}" alt="">
                                        </td>
                                        <td style="vertical-align: middle;">{{ $item['product_name'] }}</td>
                                        <td style="vertical-align: middle;">{!! currency_format($item['price_new']) !!}</td>
                                        <td style="vertical-align: middle;">{{ $qty_order }}</td>
                                        <td style="vertical-align: middle;">{!! currency_format($sub_total) !!}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="tfoot">
                                <tr>
                                    <td class="total-price" colspan="5">Tổng tiền:</td>
                                    <td>{!! currency_format($total_price) !!}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @endif

                <div id="order-end" class="end-order mt-5">
                    <p>Trước khi giao nhân viên sẽ gọi quý khách để xác nhận.</p>
                    <p>Khi cần trợ giúp vui lòng gọi cho chúng tôi vào hotline: <a href="">0374.993.702</a></p>
                    <a href="trang-chu.html" class="home">về trang chủ</a>
                    <a href="https://mail.google.com/" class="btn-check-mail">Kiểm tra email</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.client')

@section('content')
    <div id="main-content-wp" class="checkout-page">
        <div class="section" id="breadcrumb-wp">
            <div class="wp-inner">

                <div class="section-detail">
                    <ul class="list-item clearfix">
                        <li>
                            <a href="trang-chu" title="Trang chủ">Trang chủ</a>
                        </li>
                        <li>
                            <a class="children-bread-crumb">Thanh toán</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div id="wrapper" class="wp-inner clearfix">
        @if (!empty($list_product_order) && count($list_product_order) > 0)
            <div class="section" id="order-review-wp">
                <div class="section-head">
                    <h1 class="section-title">Thông tin đơn hàng</h1>
                </div>
                <div class="section-detail">
                    <form method="POST" action="{{ url('/kiem-tra-thanh-toan') }}" name="form-checkout">
                        {{ csrf_field() }}
                        <table class="shop-table">
                            <thead>
                                <tr>
                                    <td>Sản phẩm</td>
                                    <td>Tổng</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($list_product_order as $product_order)
                                <tr class="cart-item">
                                    <td class="product-name" name="product_name">{{ $product_order->options->product_name }} <strong
                                            class="product-quantity" name="number_order">x {{ $product_order->qty }} </strong></td>
                                    <td class="product-total">{{ currency_format($product_order->total) }} </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="order-total">
                                    <td>Tổng đơn hàng:</td>
                                    <td><strong class="total-price">{{ $total_price . " VNĐ" }} </strong></td>
                                </tr>
                            </tfoot>
                        </table>
                        <div id="payment-checkout-wp">
                            <ul id="payment_methods">
                                <li>
                                    <input type="radio" id="payment-home" name="payment_method" value="payment-home"
                                        checked>
                                    <label for="payment-home">Thanh toán tại nhà</label>
                                </li>
                                <li>
                                    <input type="radio" id="direct-payment" name="payment_method" value="direct-payment">
                                    <label for="direct-payment">Thanh toán tại cửa hàng</label>
                                </li>
                            </ul>
                        </div>

                </div>
            </div>
            <div class="section" id="customer-info-wp">
                <div class="section-head">
                    <h1 class="section-title">Thông tin khách hàng</h1>
                </div>
                <div class="section-detail">
                    <div class="form-row clearfix">
                        <div class="form-col fl-left">
                            <label for="fullname">Họ tên</label>
                            <input type="text" name="fullname" id="fullname" value="{{ Old('fullname') }}">
                            @error('fullname')
                                <span class="error-text">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-col fl-right">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" required="required"
                                value="{{ Old('email') }}">
                            @error('email')
                                <span class="error-text">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row clearfix">
                        <div class="form-col fl-left">
                            <label for="address">Địa chỉ</label>
                            <input type="text" name="address" id="address" value="{{ Old('address') }}">
                            @error('address')
                                <span class="error-text">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-col fl-right">
                            <label for="phone">Số điện thoại</label>
                            <input type="tel" name="phone" id="phone" value="{{ Old('phone') }}">
                            @error('phone')
                                <span class="error-text">{{ $message }}</span>
                            @enderror

                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-col">
                            <label for="notes">Ghi chú</label>
                            <textarea id="notes" name="notes"
                                placeholder="Ghi thêm lưu ý cho người bán hàng (VD: Địa chỉ, số lượng, màu sắc, ...)">{{ Old('notes') }}</textarea>
                        </div>
                    </div>
                 
                    <div class="place-order-wp clearfix">
                        <input type="submit" id="order-now" name="order_now" value="Đặt hàng">
                    </div>
                    </form>
                </div>
            </div>
        @endif

        </div>
    </div>
@endsection

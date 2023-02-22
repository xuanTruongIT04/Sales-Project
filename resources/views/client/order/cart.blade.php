@extends('layouts.client')

@section('content')
    <div id="main-content-wp" class="cart-page">
        <div class="section" id="breadcrumb-wp">
            <div class="wp-inner">
                @if (session('status'))
                <div id="alert-order">
                    {{ session('status') }}
                </div>
            @endif
                <div class="section-detail">
                    <ul class="list-item clearfix">
                        <li>
                            <a href="trang-chu.html" title="Trang chủ">Trang chủ</a>
                        </li>
                        <li>
                            <a class="children-bread-crumb">Giỏ hàng</a>
                        </li>
                    </ul>
    
                </div>
            </div>
        </div>
        <div id="wrapper" class="wp-inner clearfix">
            <div class="section" id="info-cart-wp">
                <div class="section-detail table-responsive">
                @if (!empty($list_product_order) && count($list_product_order) > 0)
                    <table class="table">
                        <thead>
                            <tr>
                                <td>Mã sản phẩm</td>
                                <td>Ảnh sản phẩm</td>
                                <td>Tên sản phẩm</td>
                                <td>Giá sản phẩm</td>
                                <td>Số lượng</td>
                                <td>Thành tiền</td>
                                <td>Thao tác</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($list_product_order as $product_order)
                            <tr>
                                <td class="product_code">{{ $product_order->options->product_code }}</td>
                                <td>
                                    <a href="{!! get_slug_product($product_order->id) !!}" title="{{ $product_order->name }}" class="thumb">
                                        <img src="{{ $product_order->options->product_thumb }}" alt="{{ $product_order->name }}">
                                    </a>
                                </td>
                                <td>
                                    <a href="{!! get_slug_product($product_order->id) !!}" title="{{ $product_order->name }}"
                                        class="name-product">{{ $product_order->name }}</a>
                                </td>
                                <td class="price_new">{!! currency_format($product_order->price) !!}</td>
                                <td>
                                    <input type="number" min="1" max="{{ $product_order->options->qty_remain }}"
                                        data-id="{{ $product_order -> id }}" name="qty[{{ $product_order->id }}]"
                                        value="{{ $product_order->qty }}" class="num-order">
                                </td>
                                <td class="total_price" id="into-money-{{ $product_order->id }}">{!! currency_format($product_order->total) !!}</td>
                                <td>
                                    <a href="{!! url_delete_cart($product_order->rowId) !!}" title="Xoá sản phẩm" class="del-product"><i
                                            class="fa fa-trash-o"></i></a>
                                </td>
                            </tr>
                            @endforeach

                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="7">
                                    <div class="clearfix">
                                        <p id="total-price" class="fl-right">Tổng giá: <span>{{  Cart::Total() . " VNĐ"  }}</span></p>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="7">
                                    <div class="clearfix">
                                        <div class="fl-right">
                                            <a href="thanh-toan.html" title="" id="checkout-cart">Thanh toán</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                @else
                        {!! template_empty_cart() !!}
                @endif

                </div>
            </div>
            <div class="section" id="action-cart-wp">
                <div class="section-detail">
                    @if (!empty($list_product_order) && count($list_product_order) > 0)
                    <i>* Ghi chú:</i>
                    <p class="title">+ Click vào <span>"hình mũi tên”</span> lên, xuống ở ô SỐ LƯỢNG để cập nhật số lượng.<BR>
                        + Click vào <span>hình sọt rác</span> ở cuối dòng để xóa sản phẩm khỏi giỏ hàng.<BR>
                        + Nhấn vào thanh toán để hoàn tất mua hàng.</p>
                        <a href="san-pham.html" title="Quay trở về trang sản phẩm" id="buy-more">Mua tiếp</a><br />
                        <a href="{{ url_delete_all_cart() }}" title="Xoá giỏ hàng" id="delete-cart">Xóa giỏ hàng</a>
                        @else
                        <a href="trang-chu.html" title="Quay trở về trang chủ" id="return-home">Quay trở lại trang chủ <i class="fas fa-undo"></i></a><br />
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

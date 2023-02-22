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
                        <a class="children-bread-crumb">Đặt hàng thất bại</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div id="wrapper" class="wp-inner clearfix">
        <div class="section" id="order-success">
            <div class="section-head">
                <p class="mess-order">
                    <img style="opacity: 0.7;" class="img-alert" src="https://upload.wikimedia.org/wikipedia/commons/thumb/3/33/OOjs_UI_icon_clear-destructive.svg/2048px-OOjs_UI_icon_clear-destructive.svg.png" alt="">
                    <span>Đặt hàng thất bại!</span>
                </p>
                <p>Ismart rất hân hạnh khi được phục vụ quý khách</p>

            </div>
            <div id="order-end" class="end-order mt-5">
                <img src="https://diyvietnam.vn/tp/T0051/images/empty-cart.png" style="margin: 30px auto;" alt="">
                <div class="mess-order-2">
                    <p> Vui lòng kiểm tra lại giỏ hàng của quý khách</p>
                    <p> Nếu nó trống, hãy quay lại trang chủ và thêm vào giỏ hàng của mình những sản phẩm thật đẹp nhé!</p>
                </div>
                <a href="trang-chu.html" class="home">về trang chủ</a>
            </div>
        </div>
    </div>
</div>
@endsection
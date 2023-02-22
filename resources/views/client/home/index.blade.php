@extends('layouts.client')

@section('content')
    <div id="main-content-wp" class="home-page clearfix">
        <div class="wp-inner">
            <div class="main-content fl-right">
                @if (!empty($list_slider))
                    <div class="section" id="slider-wp">
                        <div class="section-detail">
                            @foreach ($list_slider as $slider)
                                <div class="item">
                                    <img src="{!! get_image_fk($slider->image_id) !!}" alt="{{ $slider->slider_name }}"
                                        title="{{ $slider->slider_name }}">
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif


                <div class="section" id="support-wp">
                    <div class="section-detail">
                        <ul class="list-item clearfix">
                            <li>
                                <div class="thumb">
                                    <img src="public/images/icon-1.png">
                                </div>
                                <h3 class="title">Miễn phí vận chuyển</h3>
                                <p class="desc">Tới tận tay khách hàng</p>
                            </li>
                            <li>
                                <div class="thumb">
                                    <img src="public/images/icon-2.png">
                                </div>
                                <h3 class="title">Tư vấn 24/7</h3>
                                <p class="desc">0374.993.702</p>
                            </li>
                            <li>
                                <div class="thumb">
                                    <img src="public/images/icon-3.png">
                                </div>
                                <h3 class="title">Tiết kiệm hơn</h3>
                                <p class="desc">Với nhiều ưu đãi cực lớn</p>
                            </li>
                            <li>
                                <div class="thumb">
                                    <img src="public/images/icon-4.png">
                                </div>
                                <h3 class="title">Thanh toán nhanh</h3>
                                <p class="desc">Hỗ trợ nhiều hình thức</p>
                            </li>
                            <li>
                                <div class="thumb">
                                    <img src="public/images/icon-5.png">
                                </div>
                                <h3 class="title">Đặt hàng online</h3>
                                <p class="desc">Thao tác đơn giản</p>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="section" id="feature-product-wp">
                    <div class="section-head">
                        <h3 class="section-title">Sản phẩm nổi bật</h3>
                    </div>
                    @if (!empty($list_product_sold_most))
                        <div class="section-detail">
                            <ul class="list-item list-item-zoom-in">
                                @foreach ($list_product_sold_most as $product_sold_most)
                                    <li>
                                        <a href="{!! get_slug_product($product_sold_most->id) !!}" title="{{ $product_sold_most->product_name }}"
                                            class="thumb">
                                            <img src="{!! get_product_main_thumb($product_sold_most->id) !!}">
                                        </a>
                                        <a href="{!! get_slug_product($product_sold_most->id) !!}" title="{{ $product_sold_most->product_name }}"
                                            class="product-name">{{ $product_sold_most->product_name }}</a>
                                        <div class="price">
                                            <span class="old">{!! currency_format($product_sold_most->price_old) !!}</span>
                                            <span class="new">{!! currency_format($product_sold_most->price_new) !!}</span>
                                        </div>
                                        <div class="action clearfix">
                                            <a href="{{ $product_sold_most->url_add_cart }}" title="Thêm giỏ hàng"
                                                class="add-cart fl-left">Thêm giỏ
                                                hàng</a>
                                            <a href="{{ $product_sold_most->url_buy_now }}" title="Mua ngay"
                                                class="buy-now fl-right">Mua ngay</a>
                                        </div>
                                    </li>
                                @endforeach

                            </ul>
                        </div>
                    @endif
                </div>
                @if (session('add_product_success'))
                    @include('layouts.model-success-order')
                @endif
                <div class="section" id="list-product-wp">
                    <div class="section-head">
                        <h3 class="section-title">Điện thoại</h3>
                    </div>
                    @if (!empty($list_phone_demo))
                        <div class="section-detail">
                            <ul class="list-item clearfix">
                                @foreach ($list_phone_demo as $phone_demo)
                                    <li>
                                        <a href="{!! get_slug_product($phone_demo->id) !!}" title="{{ $phone_demo->product_name }}"
                                            class="thumb">
                                            <img src="{!! get_product_main_thumb($phone_demo->id) !!}">
                                        </a>
                                        <a href="{!! get_slug_product($phone_demo->id) !!}" title="{{ $phone_demo->product_name }}"
                                            class="product-name">{{ $phone_demo->product_name }}</a>
                                        <div class="price">
                                            <span class="old">{!! currency_format($phone_demo->price_old) !!}</span>
                                            <span class="new">{!! currency_format($phone_demo->price_new) !!}</span>
                                        </div>
                                        <div class="action clearfix">
                                            <a href="{{ $phone_demo->url_add_cart }}" title="Thêm giỏ hàng"
                                                class="add-cart fl-left">Thêm giỏ
                                                hàng</a>
                                            <a href="{{ $phone_demo->url_buy_now }}" title="Mua ngay"
                                                class="buy-now fl-right">Mua ngay</a>
                                        </div>
                                    </li>
                                @endforeach

                            </ul>
                        </div>
                    @endif

                </div>
                <div class="section" id="list-product-wp">
                    <div class="section-head">
                        <h3 class="section-title">Laptop</h3>
                    </div>
                    @if (!empty($list_laptop_demo))
                        <div class="section-detail">
                            <ul class="list-item clearfix">
                                @foreach ($list_laptop_demo as $laptop_demo)
                                    <li>
                                        <a href="{!! get_slug_product($laptop_demo->id) !!}" title="{{ $laptop_demo->product_name }}"
                                            class="thumb">
                                            <img src="{!! get_product_main_thumb($laptop_demo->id) !!}">
                                        </a>
                                        <a href="{!! get_slug_product($laptop_demo->id) !!}" title="{{ $laptop_demo->product_name }}"
                                            class="product-name">{{ $laptop_demo->product_name }}</a>
                                        <div class="price">
                                            <span class="old">{!! currency_format($laptop_demo->price_old) !!}</span>
                                            <span class="new">{!! currency_format($laptop_demo->price_new) !!}</span>
                                        </div>
                                        <div class="action clearfix">
                                            <a href="{{ $laptop_demo->url_add_cart }}" title="Thêm giỏ hàng"
                                                class="add-cart fl-left">Thêm giỏ
                                                hàng</a>
                                            <a href="{{ $laptop_demo->url_buy_now }}" title="Mua ngay"
                                                class="buy-now fl-right">Mua ngay</a>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
            @include('layouts.sidebar')
        </div>
    </div>
@endsection

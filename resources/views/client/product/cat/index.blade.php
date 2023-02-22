@extends('layouts.client')

@section('content')

    <div id="main-content-wp" class="clearfix category-product-page">
        <div class="wp-inner">
            <div class="secion" id="breadcrumb-wp">
                <div class="secion-detail">
                    <ul class="list-item clearfix">
                        <li>
                            <a href="trang-chu" title="Trang chủ">Trang chủ</a>
                        </li>
                        <li>
                            <a class="children-bread-crumb">Sản phẩm</a>
                        </li>
                        <li>
                        </li>
                    </ul>
                </div>
            </div>
            @php
                $slug = Request()->slug;
            @endphp
            <div class="main-content fl-right">
                <div class="section" id="list-product-wp">
                    @if (!empty($slug) && isset($slug))
                        <div class="filter-wp fl-right">
                            <p class="desc">Hiển thị <b>@php isset($cnt_product) ? (int) $cnt_product : '0';@endphp </b> trên <b>@php isset($num_total) ? (int) $num_total : '0';@endphp
                                </b>sản phẩm</p>
                            <div class="form-filter">
                                <form method="GET" action="" class="form-filter-product">
                                    <select id="select-filter-product" name="filter_product">
                                        <option value="">Sắp xếp</option>
                                        <option value="product_name_asc">
                                            Tên từ A-Z</option>
                                        <option value="product_name_desc">
                                            Tên từ Z-A</option>
                                        <option value="product_price_asc">
                                            Giá thấp lên cao</option>
                                        <option value="product_price_desc">
                                            Giá cao xuống thấp</option>
                                    </select>
                                </form>
                            </div>
                        </div>
                        <span id="method-filter-nav-pdc" class="method-filter-nav-pdc fl-left" style="">
                         
                        </span>
                    @endif
                    @if (session('add_product_success'))
                        @include('layouts.model-success-order')
                    @endif
                    @if (!empty($total_list_product_cat) && count($total_list_product_cat) > 0)
                        @foreach ($total_list_product_cat as $product_cat)
                            @if (!empty($slug))
                                @php $product_cat_id = get_product_cat_id_by_slug($slug) @endphp
                            @else
                                @php $product_cat_id = !empty($product_cat->id) ? $product_cat->id : '' @endphp
                            @endif
                            <div class="section-head clearfix ">
                                <h3 class="section-title fl-left">{!! !empty($product_cat->product_cat_title) ? $product_cat->product_cat_title : '' !!} </h3>
                            </div>
                            @php
                                $list_product = get_list_product($product_cat_id);
                                // PHÂN TRANG
                                global $num_page, $start;
                                
                                // Trang hiện tại
                                $page = isset(Request()->page) ? (int) Request()->page : 1;
                                
                                // Số bản ghi trên mỗi trang
                                $num_per_page = 36;
                                
                                $start = ($page - 1) * $num_per_page;
                                
                                // Tổng bản ghi
                                $total_row = $cnt_product;
                                
                                // Tổng số trang cần tìm
                                $num_page = ceil($total_row / $num_per_page);
                            @endphp
                            @if (!empty($slug))
                                @php $list_product = paging_list_product($list_product, $start, $num_per_page) @endphp
                            @else
                                @php $list_product = get_list_product_highlight($product_cat_id) @endphp
                            @endif

                            @if (!empty($list_product) && count($list_product) > 0)
                                <div class="section-detail" id="list-product-main">
                                    <ul class="list-item clearfix">
                                        @foreach ($list_product as $product)
                                            <li>
                                                <a href="{!! get_slug_product($product->id) !!}" title="" class="thumb">
                                                    <img src="{!! get_product_main_thumb($product->id) !!}">
                                                </a>
                                                <a href="{!! get_slug_product($product->id) !!}" title="" class="product-name">
                                                    @if (strlen($product->product_name) > 45)
                                                        {!! brief_name($product->product_name, 10) !!}
                                                    @else
                                                        {!! $product->product_name !!}
                                                    @endif
                                                </a>
                                                <div class="price">
                                                    <span class="old">{!! currency_format($product->price_old) !!}</span>
                                                    @if (!empty($product->price_new && is_numeric($product->price_new)))
                                                        <span class="new"> {!! currency_format($product->price_new) !!}</span>
                                                    @endif
                                                </div>
                                                <div class="action clearfix">
                                                    <a href="{!! url_add_cart($product->slug) !!}" title="Thêm giỏ hàng"
                                                        class="add-cart fl-left">Thêm giỏ
                                                        hàng</a>
                                                    <a href="{!! $product->url_buy_now !!}" title="Mua ngay"
                                                        class="buy-now fl-right">Mua ngay</a>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        @endforeach
                </div>
                @if (!empty($slug) && isset($slug))
                    <div class="section" id="paging-wp">
                        <div class="section-detail">
                            @php
                                global $num_page;
                                echo get_paging($page, $num_page, "san-pham/{$slug}");
                            @endphp

                        </div>
                    </div>
            </div>
            @include('layouts.sidebar-list-product-cat')
        </div>
    @else
    </div>
    @include('layouts.sidebar-product')
    </div>
    </div>
    </div>
    @endif
@else
    {!! template_empty_product() !!}
    @endif

@endsection

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
                            <a class="children-bread-crumb">Tìm kiếm sản phẩm</a>
                        </li>
                        <li>

                        </li>
                    </ul>
                </div>
            </div>
            <div class="main-content fl-right">
                @if (!empty($list_product_search) && count($list_product_search) > 0)
                    @php
                        global $page, $num_page, $start;
                        // Trang hiện tại
                        $page = !empty(Request()->input('page')) ? (int) Request()->input('page') : 1;
                        
                        // Số bản ghi trên mỗi trang
                        $num_per_page = 20;
                        
                        $start = ($page - 1) * $num_per_page;
                        
                        // Tổng bản ghi
                        $total_row = count($list_product_search);
                        
                        // Tổng số trang cần tìm
                        $num_page = ceil($total_row / $num_per_page);
                        
                        $list_product_search = paging_list_product($list_product_search, $start, $num_per_page);
                        @endphp
                      
                    <div class="section clearfix" id="list-product-wp">
                        <div class="filter-wp fl-right">
                            <div class="form-filter">
                                {{-- <form method="POST" action="">
                                    <select name="filter_product">
                                        <option value="">Sắp xếp</option>
                                        <option value="product_name_asc" @php if (isset($_POST['btn_filter_product']) && $_POST['filter_product'] == 'product_name_asc') {
                                            echo 'selected';
                                        } @endphp>Tên từ A-Z</option>
                                        <option value="product_name_desc" @php if (isset($_POST['btn_filter_product']) && $_POST['filter_product'] == 'product_name_desc') {
                                            echo 'selected';
                                        } @endphp>Tên từ Z-A</option>
                                        <option value="product_price_asc" @php if (isset($_POST['btn_filter_product']) && $_POST['filter_product'] == 'product_price_asc') {
                                            echo 'selected';
                                        } @endphp>Giá thấp lên cao</option>
                                        <option value="product_price_desc" @php if (isset($_POST['btn_filter_product']) && $_POST['filter_product'] == 'product_price_desc') {
                                            echo 'selected';
                                        } @endphp>Giá cao xuống thấp</option>
                                    </select>
                                    <button type="submit" name="btn_filter_product">Lọc</button>
                                </form> --}}
                            </div>

                        </div>
                        @php
                        $key_word = !empty(Request() -> input('key_word')) ? Request()->input('key_word') : '';
                        @endphp
                        <div class="section-head fl-left">
                            <h3 class="section-title fl-left">Kết quả tìm kiếm cho từ khoá: <b
                                    class='key-word-content'>"{!! $key_word !!}"</b></h3>
                        </div>
                        <div class="section-detail">
                            <ul class="list-item clearfix">
                            @foreach ($list_product_search as $product)
                                <li>
                                    <a href="{!! get_slug_product($product->id) !!}" title="" class="thumb">
                                        <img src="{!! get_product_main_thumb($product->id) !!}">
                                    </a>
                                    <a href="{!! get_slug_product($product->id) !!}" title=""
                                        class="product-name">{!!  brief_name($product->product_name, 10) !!}</a>
                                    <div class="price">
                                        <span class="old">{!! currency_format($product->price_old) !!}</span>
                                        <span class="new">{!! currency_format($product->price_new) !!}</span>
                                    </div>
                                    <div class="action clearfix">
                                        <a href="{!! $product->url_add_cart !!}" title="Thêm giỏ hàng" class="add-cart fl-left">Thêm
                                            giỏ hàng</a>
                                        <a href="{!! $product->url_buy_now !!}" title="Mua ngay" class="buy-now fl-right">Mua
                                            ngay</a>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>

                    </div>
                    <div class="section" id="paging-wp">
                        <div class="section-detail">
                            @php
                            global $page, $num_page;
                            $key_word = Request() -> input('key_word');
                            @endphp
                           {!! get_paging_list_product($page, $num_page, 'tim-kiem-san-pham?key_word=' . $key_word) !!} 
                        </div>
                    </div>
                    @else
                    {!! template_no_product_found() !!}
                    @endif
                </div>
                {{-- {{ dd("hehe1") }} --}}
            @include('layouts.sidebar-list-product-cat')
        </div>
    </div>
@endsection

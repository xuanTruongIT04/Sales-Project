@extends('layouts.client')
@section('content')
    <div id="main-content-wp" class="clearfix detail-product-page">
        <div class="wp-inner">
            <div class="secion" id="breadcrumb-wp">
                <div class="secion-detail">
                    <ul class="list-item clearfix">
                        <li>
                            <a href="trang-chu" title="Trang chủ">Trang chủ</a>
                        </li>
                        <li>
                            <a href="san-pham" title="Sản phẩm">Sản phẩm</a>
                        </li>
                        <li>
                            <a class="children-bread-crumb">Chi tiết sản phẩm</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="main-content fl-right">
                <div class="section" id="detail-product-wp">
                    <div class="section-detail clearfix">
                        <div class="thumb-wp fl-left">
                            <a href="#" title="" id="main-thumb">
                                <img id="zoom" src="{!! get_product_main_thumb($info_product->id) !!}"
                                    data-zoom-image="{!! get_product_main_thumb($info_product->id) !!}" />
                            </a>
                            @php
                                $list_product_thumb = get_list_product_thumb($info_product->id);
                            @endphp
                            @if (!empty($list_product_thumb) && count($list_product_thumb) > 0)
                                <div id="list-thumb">
                                    @foreach ($list_product_thumb as $product_thumb)
                                        <a href="" data-image="{!! $product_thumb['image_link'] !!}"
                                            data-zoom-image="{!! $product_thumb['image_link'] !!}">
                                            <img id="zoom" src="{!! $product_thumb['image_link'] !!}" />
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        <div class="thumb-respon-wp fl-left">
                            <img src="public/images/img-pro-01.png" alt="">
                        </div>
                        <div class="info fl-right">
                            <h3 class="product-name">{!! $info_product->product_name !!}</h3>
                            <div class="desc desc-product">
                                @if (strlen($info_product->product_desc) > 150)
                                    {!! $info_product->product_desc !!}
                                    <div class="section-load-more-dp">
                                        <button class="btn-load-more-dp">Xem thêm mô tả ngắn</button>
                                        <div class="modal-load-more-dp">
                                            <div class="modal-box-load-more-dp">
                                                <div class="modal-header-load-more-dp">
                                                    <span>Mô tả ngắn về sản phẩm</span>
                                                </div>
                                                <div class="modal-content-load-more-dp">
                                                    <span class="close-load-more-dp">&times;</span>
                                                    {!! $info_product->product_desc !!}
                                                </div>
                                                <div class="modal-footer-load-more-dp">
                                                    <span>THẾ GIỚI CÔNG NGHỆ ISMART</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    {!! $info_product->product_desc !!}
                                @endif
                                @endphp

                            </div>
                            <div class="num-product">
                                <span class="title">Sản phẩm: </span>
                                <span class="status">{!! template_status_product($info_product->qty_remain) !!}</span>
                            </div>
                            <div class="price">
                                <p>Giá cũ: <span class="old"> {!! currency_format($info_product->price_old) !!}</span></p>
                                <p>Giá mới: <span class="new"> {!! currency_format($info_product->price_new) !!}</span></p>
                            </div>
                            <form action="{{ url("/them-san-pham-sl/{$info_product->slug}") }}" method="GET">
                                <div id="num-order-wp">
                                    <a title="" id="minus"><i class="fa fa-minus"></i></a>
                                    <input type="text" name="num_order_bonus" value="1" id="num-order">
                                    <a title="" id="plus"><i class="fa fa-plus"></i></a>
                                </div>
                                <input type="submit" title="Thêm giỏ hàng" name="btn_add_order" class="add-cart"
                                    value="Thêm giỏ hàng" style="border: none;">
                            </form>

                        </div>
                    </div>
                </div>
                <div class="section detail-product-load-more" id="post-product-wp">
                    <div class="section-head">
                        <h3 class="section-title">Mô tả sản phẩm</h3>

                    </div>
                    <div class="section-detail">
                        {!! $info_product->product_detail !!}
                    </div>
                    <div id="section-load-more">
                        <button id="btn-load-more">Xem thêm</button>
                    </div>
                </div>
                <div class="section" id="same-category-wp">
                    <div class="section-head">
                        <h3 class="section-title">Cùng chuyên mục</h3>
                    </div>
                    @if (!empty($list_product_same_cat) && count($list_product_thumb) > 0)
                        <div class="section-detail">
                            <ul class="list-item">
                                @foreach ($list_product_same_cat as $product_same_cat)
                                    <li>
                                        <a href="{!! set_slug_product(get_slug_product_same_cat($product_same_cat->id)) !!}" title="" class="thumb">
                                            <img src="{!! get_product_main_thumb($product_same_cat->id) !!}">
                                        </a>
                                        <a href="{!! set_slug_product(get_slug_product_same_cat($product_same_cat->id)) !!}" title=""
                                            class="product-name">{!! brief_name($product_same_cat->product_name, 10) !!}</a>
                                        <div class="price">
                                            <span class="old">{!! currency_format($product_same_cat->price_old, ' VNĐ') !!}</span>
                                            <span class="new">{!! currency_format($product_same_cat->price_new, ' VNĐ') !!}</span>
                                        </div>
                                        <div class="action clearfix">
                                            <a href="{!! $product_same_cat->url_add_cart !!}" title="" class="add-cart fl-left">Thêm
                                                giỏ hàng</a>
                                            <a href="{!! $product_same_cat->url_buy_now !!}" title="" class="buy-now fl-right">Mua
                                                ngay</a>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @else
                        @php echo "<p>Không có sản phẩm nào cùng chuyên mục</p>"; @endphp
                    @endif

                </div>
            </div>
            @include('layouts.sidebar-product')
        </div>
    </div>
@endsection

@php
    use App\Image;
    
    function get_list_product_thumb($product_id)
    {
        $sql = Image::where('product_id', $product_id)
            ->where('rank', '0')
            ->select('image_link')
            ->get();
        $sql = json_decode(json_encode($sql), true);
    
        return $sql;
    }
@endphp

@php
    $list_product_sold_most = get_list_product_sold_most();
@endphp

<div class="section" id="selling-wp">
    <div class="section-head">
        <h3 class="section-title">Sản phẩm bán chạy</h3>
    </div>
    @if (!empty($list_product_sold_most))
        <div class="section-detail">
            <ul class="list-item list-item-zoom-in-sidebar">
                @foreach ($list_product_sold_most as $product_sold_most)
                    <li class="clearfix sold-most-item">
                        <a href="{!! get_slug_product($product_sold_most->id) !!}"
                            title="{!! $product_sold_most->product_name !!}" class="thumb fl-left">
                            <img src="{!! get_product_main_thumb($product_sold_most->id) !!}"
                                alt="Đây là {!! $product_sold_most->product_name !!}">
                        </a>
                        <div class="info fl-right">
                            <a href="{!! get_slug_product($product_sold_most->id) !!}"
                                title="{!! $product_sold_most->product_name !!}"
                                class="product-name">{!! $product_sold_most->product_name !!}</a>
                            <div class="price">
                                <span class="old">{!! currency_format($product_sold_most->price_old) !!}</span>
                                <span class="new">{!! currency_format($product_sold_most->price_new) !!}</span>
                            </div>
                            <a href="{!! $product_sold_most->url_buy_now !!}" title="Mua ngay"
                                class="buy-now">Mua ngay</a>
                        </div>
                    </li>
                @endforeach

            </ul>
        </div>
    @endif

</div>

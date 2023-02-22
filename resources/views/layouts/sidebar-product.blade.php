<div class="sidebar fl-left">
    @php
        $list_product_cat_parent = get_list_product_cat_parent();
    @endphp
    <div class="section" id="category-product-wp">
        <div class="section-head">
            <h3 class="section-title">Danh mục sản phẩm</h3>
        </div>
        <div class="secion-detail">
            {!! template_get_sidebar($list_product_cat_parent) !!}
        </div>
        @include('layouts.banner')
    </div>
</div>

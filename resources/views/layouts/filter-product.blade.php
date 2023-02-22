<div class="section-detail" id="filter_product_by_price">
    <h3 class="fpbp_name">Giá</h3>
    <form action="" method="GET">
        @csrf
        <div class="fpbp_content">
            <div class="fpbp_content_item">
                <input type="radio" name="ajax_filter_price_sidebar" id="price_1" value="500000">
                <label for="price_1">Dưới 500.000 VNĐ</label>
            </div>
            <div class="fpbp_content_item">
                <input type="radio" name="ajax_filter_price_sidebar" id="price_2" value="1000000">
                <label for="price_2">500.000 VNĐ - 1.000.000 VNĐ</label>
            </div>
            <div class="fpbp_content_item">
                <input type="radio" name="ajax_filter_price_sidebar" id="price_3" value="5000000">
                <label for="price_3">1.000.000 VNĐ - 5.000.000 VNĐ</label>
            </div>
            <div class="fpbp_content_item">
                <input type="radio" name="ajax_filter_price_sidebar" id="price_4" value="10000000">
                <label for="price_4">5.000.000 VNĐ - 10.000.000 VNĐ</label>
            </div>
            <div class="fpbp_content_item">
                <input type="radio" name="ajax_filter_price_sidebar" id="price_5" value="10000001">
                <label for="price_5">Trên 10.000.000 VNĐ</label>
            </div>
            <div class="fpbp_content_item">
                <input type="radio" name="ajax_filter_price_sidebar" id="price_6" value="999999999">
                <label for="price_6">Tất cả các sản phẩm</label>
            </div>
        </div>

    </form>

</div>

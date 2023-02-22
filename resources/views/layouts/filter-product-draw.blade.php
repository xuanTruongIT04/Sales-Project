<div class="section-detail">
    <form method="POST" action="">
        <div class="filter-price">
            <p class="filter-price-title">Giá (0 đồng - 200 triệu)</p>
            <input type="hidden" id="hidden_minimum_price" value="0">
            <input type="hidden" id="hidden_maximum_price" value="200000000">
            <input type="text" id="amount" readonly style="border:0; width: 100%; font-size: 15px; color: green; margin-bottom: 10px;  background-color: #cccccc0d;">
            <div id="slider-range"></div>
        </div>
        <?php
        // show_array(get_branch_product_cat());
        // $list_branch_product_cat = get_branch_product_cat();
        // $list_brand_product_cat = get_brand_product_cat();
        // if (!empty($list_branch_product_cat) && is_array($list_branch_product_cat)) {
        ?>
            <!-- <table>
                <thead>
                    <tr>
                        <td colspan="2">Loại</td>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // $cnt_index_branch = 0;
                    // foreach ($list_branch_product_cat as $branch_product_cat) {
                    //     $cnt_index_branch++;
                    ?>
                        <tr>
                            <td><input type="checkbox" name="common_selector r-branch" id="filter-branch<?php //echo "-" . $cnt_index_branch; ?>"></td>
                            <td><label for="filter-branch<?php //echo "-" . $cnt_index_branch; ?>"><?php //echo $branch_product_cat['product_cat_title'] ?></label></td>
                        </tr>
                    <?php
                    // }
                    ?>


                </tbody>
            </table> -->
        <?php
        // }
        ?>

        <?php
        // if (!empty($list_brand_product_cat) && is_array($list_brand_product_cat)) {

        ?>
            <!-- <table>
                <thead>
                    <tr>
                        <td colspan="2">Hãng</td>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // $cnt_index_brand = 0;
                    // foreach ($list_brand_product_cat as $brand_product_cat) {
                        // $cnt_index_brand++;
                    ?>
                        <tr>
                            <td><input type="checkbox" name="common_selector r-brand" id="filter-brand<?php //echo "-" . $cnt_index_brand; ?>"></td>
                            <td><label for="filter-brand<?php //echo "-" . $cnt_index_brand; ?>"><?php //echo $brand_product_cat['brand_name'] ?></label></td>
                        </tr>
                    <?php
                    // }
                    ?>
                </tbody>
            </table> -->
        <?php
        // }
        ?>
    </form>
</div>
<script>
    $(document).ready(function() {
        filter_data();

        function filter_data() {
            var action = 'get_data';
            var minimum_price = $("#hidden_minimum_price").val();
            var maximum_price = $("#hidden_maximum_price").val();

            $.ajax({
                url: "filter_data.php",
                method: "POST",
                data: {
                    action: action,
                    minimum_price: minimum_price,
                    maximum_price: maximum_price,
                    // branch: branch,
                    // brand: brand
                },
                success: function(data) {
                    $(".filter_data").html(data)
                }
            });
        }

        function get_filter(class_name) {
            var filter = [];
            $("." + class_name + ":checked").each(function() {
                filter.push($(this).val());
            });
        }

        $(".common_selector").click(function() {
            filter_data();
        })

        $("#slider-range").slider({
            range: true,
            min: 0,
            max: 200000000,
            values: [0, 200000000],
            step: 1000000,
            slide: function(event, ui) {
                $("#amount").val(ui.values[0] + " VNĐ" + " - " + ui.values[1] + " VNĐ");
                $("#hidden_minimum_price").val(ui.values[0]);
                $("#hidden_maximum_price").val(ui.values[1]);
            }
        });
        $("#amount").val($("#slider-range").slider("values", 0) + " VNĐ" + " - " + $("#slider-range").slider("values", 1) + " VNĐ");

    })
</script>
$(document).ready(function(){
    // UPDATE CART BY AJAX
    $("#info-cart-wp table tbody tr td .num-order").change(function() {
        var product_id = $(this).attr("data-id");
        var qty_order = $(this).val();
        var token = $("input[name='_token']").val();
        var data = {
            product_id : product_id,
            qty_order : qty_order,
            "_token":token,
        };

        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content")
            }
        });

        $.ajax({
            url: "./cap-nhat-gio-hang",
            method: "POST",
            data: data,
            dataType: "json",
            success: function(data) {
                $("#into-money-" + product_id).text(data.into_money);
                $("#total-price span").text(data.total_price);
            },  
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + " - " + ajaxOptions + " - " + thrownError);
            }
        });
    });


});

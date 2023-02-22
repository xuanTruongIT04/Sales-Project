<?php

$list_product_order = get_list_product_order();
$count_product_order = Cart::count();
$total_price = get_total_price();

?>
<!DOCTYPE html>
<html>

<head>
    <title>ISMART STORE</title>
    <base href="{!! url('/') !!}/" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('css/bootstrap/bootstrap-theme.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/bootstrap/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('reset.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/carousel/owl.carousel.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/carousel/owl.theme.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('style.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('responsive.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css"
        integrity="sha512-1sCRPdkRXhBV2PBLUdRb4tMg1w2YPf37qatUFeS7zlBy7jJI8Lf4VHwWfZZfpXtYSLy85pkm9GaYVYMfw5BC1A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/themes/base/jquery-ui.min.css"
        integrity="sha512-ELV+xyi8IhEApPS/pSj66+Jiw+sOT1Mqkzlh8ExXihe4zfqbWkxPRi8wptXIO9g73FSlhmquFlUOuMSoXz5IRw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&display=swap"
        rel="stylesheet">
</head>

<body>
    @csrf
    <div id="site">
        <div id="container">
            <div id="header-wp">
                <div id="head-top" class="clearfix">
                    <div class="wp-inner">
                        <a href="" title="" id="payment-link" class="fl-left">Hình thức thanh toán</a>
                        <div id="main-menu-wp" class="fl-right">
                            <ul id="main-menu" class="clearfix">
                                <li>
                                    <a href="trang-chu.html" title="">Trang chủ</a>
                                </li>
                                <li>
                                    <a href="san-pham.html" title="">Sản phẩm</a>
                                </li>
                                <li>
                                    <a href="bai-viet.html" title="">Blog</a>
                                </li>
                                <li>
                                    <a href="gioi-thieu.html" title="">Giới thiệu</a>
                                </li>
                                <li>
                                    <a href="lien-he.html" title="">Liên hệ</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div id="head-body" class="clearfix">
                    <div class="wp-inner">
                        <a href="trang-chu.html" title="" id="logo" class="fl-left"><img
                                src="{{ asset('images/logo.png') }}" /></a>
                        <div id="search-wp" class="fl-left">
                            <form method="GET" action="{{ url('tim-kiem-san-pham') }}" class="form-search">
                                <input type="text" name="key_word" id="s" class="input-search-ajax"
                                    placeholder="Nhập từ khóa tìm kiếm tại đây!" value="{{ Old('key_word') }}">
                                <button type="submit" id="sm-s">Tìm kiếm</button>

                                <div id="ajax-nav-search" class="ajax-search-result">
                                </div>

                            </form>
                        </div>

                        <div id="action-wp" class="fl-right">
                            <div id="advisory-wp" class="fl-left">
                                <span class="title">Tư vấn</span>
                                <span class="phone">0374.993.702</span>
                            </div>
                            <div id="btn-respon" class="fl-right"><i class="fa fa-bars" aria-hidden="true"></i>
                            </div>
                            <a href="gio-hang.html" title="giỏ hàng" id="cart-respon-wp" class="fl-right">
                                <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                                <span id="num"><?php echo $count_product_order; ?></span>
                            </a>
                            <a id="container-cart-wp" href="gio-hang.html">
                                <div id="cart-wp" class="fl-right">
                                    <div id="btn-cart">
                                        <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                                        <span id="num"><?php echo $count_product_order; ?></span>
                                    </div>
                                    <div id="dropdown">
                                        <p class="desc">Có <span><?php echo $count_product_order; ?> sản phẩm</span> trong giỏ hàng
                                        </p>
                                        <?php
                                        if (!empty($list_product_order && count($list_product_order))> 0) {
                                        ?>

                                        <ul class="list-cart">
                                            <?php
                                                foreach ($list_product_order as $item) {
                                                ?>
                                            <li class="clearfix">
                                                <a href="<?php echo get_slug_product($item->id); ?>" title="<?php echo $item->name; ?>"
                                                    class="thumb fl-left">
                                                    <img src="<?php echo get_product_main_thumb($item->id); ?>" alt="">
                                                </a>
                                                <div class="info fl-right">
                                                    <a href="<?php echo get_slug_product($item->id); ?>" title="<?php echo $item->name; ?>"
                                                        class="product-name"><?php echo $item->name; ?></a>
                                                    <p class="price"><?php echo currency_format($item->price); ?></p>
                                                    <p class="qty">Số lượng: <span><?php echo $item->qty; ?></span></p>
                                                </div>
                                            </li>
                                            <?php
                                                }
                                                ?>
                                        </ul>

                                        <div class="total-price clearfix">
                                            <p class="title fl-left">Tổng:</p>
                                            <p class="price fl-right"><?php echo Cart::total() . ' VNĐ'; ?></p>
                                        </div>
                                        <dic class="action-cart clearfix">
                                            <a href="gio-hang.html" title="Giỏ hàng" class="view-cart fl-left">Giỏ
                                                hàng</a>
                                            <a href="thanh-toan" title="Thanh toán" class="checkout fl-right">Thanh
                                                toán</a>
                                        </dic>
                                    </div>
                                    <?php
                                        } else {
                                ?>
                                    <img src="https://bizweb.dktcdn.net/100/440/012/themes/839260/assets/empty_cart.png?1653287637639"
                                        alt="Trống giỏ hàng">
                                    <span class="mess-order-header">Hãy thêm các sản phẩm vào giỏ hàng của
                                        bạn!!!</span>
                                    <?php
                                        }
                                ?>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @yield('content')
    </div>
    <div id="footer-wp">
        <div id="foot-body">
            <div class="wp-inner clearfix">
                <div class="block" id="info-company">
                    <h3 class="title">ISMART</h3>
                    <p class="desc">ISMART luôn cung cấp luôn là sản phẩm chính hãng có thông tin rõ ràng, chính sách
                        ưu đãi cực lớn cho khách hàng có thẻ thành viên.</p>
                    <div id="payment">
                        <div class="thumb">
                            <img src="{{ asset('images/img-foot.png') }}" alt="">
                        </div>
                    </div>
                </div>
                <div class="block menu-ft" id="info-shop">
                    <h3 class="title">Thông tin cửa hàng</h3>
                    <ul class="list-item">
                        <li>
                            <p>39/195 - Đường Cầu Diễn - Phường Cầu Diễn</p>
                        </li>
                        <li>
                            <p>0374.993.702 - 0348.080.722</p>
                        </li>
                        <li>
                            <p>ismartshop@gmail.com</p>
                        </li>
                    </ul>
                </div>
                <div class="block menu-ft policy" id="info-shop">
                    <h3 class="title">Chính sách mua hàng</h3>
                    <ul class="list-item">
                        <li>
                            <a href="" title="">Quy định - chính sách</a>
                        </li>
                        <li>
                            <a href="" title="">Chính sách bảo hành - đổi trả</a>
                        </li>
                        <li>
                            <a href="" title="">Chính sách hội viện</a>
                        </li>
                        <li>
                            <a href="" title="">Giao hàng - lắp đặt</a>
                        </li>
                    </ul>
                </div>
                <div class="block" id="newfeed">
                    <h3 class="title">Bảng tin</h3>
                    <p class="desc">Đăng ký với chung tôi để nhận được thông tin ưu đãi sớm nhất</p>
                    <div id="form-reg">
                        <form method="POST" action="">
                            <input type="email" name="email" id="email" placeholder="Nhập email tại đây">
                            <button type="submit" id="sm-reg">Đăng ký</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div id="foot-bot">
            <div class="wp-inner">
                <p id="copyright">© Bản quyền thuộc về unitop.vn | Php Master</p>
            </div>
        </div>
    </div>
    </div>
    <div id="menu-respon">
        <a href="?page=home" title="" class="logo">VSHOP</a>
        <div id="menu-respon-wp">
            <ul class="" id="main-menu-respon">
                <li>
                    <a href="?page=home" title>Trang chủ</a>
                </li>
                <li>
                    <a href="?page=category_product" title>Điện thoại</a>
                    <ul class="sub-menu">
                        <li>
                            <a href="?page=category_product" title="">Iphone</a>
                        </li>
                        <li>
                            <a href="?page=category_product" title="">Samsung</a>
                            <ul class="sub-menu">
                                <li>
                                    <a href="?page=category_product" title="">Iphone X</a>
                                </li>
                                <li>
                                    <a href="?page=category_product" title="">Iphone 8</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="?page=category_product" title="">Nokia</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="?page=category_product" title>Máy tính bảng</a>
                </li>
                <li>
                    <a href="?page=category_product" title>Laptop</a>
                </li>
                <li>
                    <a href="?page=category_product" title>Đồ dùng sinh hoạt</a>
                </li>
                <li>
                    <a href="?page=blog" title>Blog</a>
                </li>
                <li>
                    <a href="#" title>Liên hệ</a>
                </li>
            </ul>
        </div>
    </div>
    <div id="btn-top"><img src="{{ asset('images/icon-to-top.png') }}" alt="" /></div>
    <div id="fb-root"></div>
    <script src="{{ asset('js/jquery-2.2.4.min.js') }}" type="text/javascript"></script>
    <script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI="
        crossorigin="anonymous"></script>
    <script src="{{ asset('js/elevatezoom-master/jquery.elevatezoom.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/bootstrap/bootstrap.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/carousel/owl.carousel.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/main.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/ajax.js') }}" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"
        integrity="sha512-57oZ/vW8ANMjR/KQ6Be9v/+/h6bq9/l3f0Oc7vn6qMqyhvPd1cvKBRWWpzu0QoneImqr2SkmO4MSqU+RpHom3Q=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        (function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id))
                return;
            js = d.createElement(s);
            js.id = id;
            js.src = "//connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v2.8&appId=849340975164592";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));



        // SEARCH PRODUCT NAVIGATE
        $("#ajax-nav-search").hide();
        $(".input-search-ajax").keyup(function() {
            var $key_word = $(this).val();
            var $link_bonus = "{{ url('tim-kiem-san-pham?key_word=') }}" + $key_word;
            if ($key_word != "") {
                $.ajax({
                    url: "{{ route('ajax-search-product') }}?key=" + $key_word,
                    type: "GET",
                    success: function(res) {
                        var $list_product = "";
                        for (var pro of res) {
                            $list_product += '<a href="' + pro.link_detail_product +'" class="ajax-item-result">';
                            $list_product += '<span class="ajax-img-contain">';
                            $list_product += '<img src=' + pro.product_thumb +
                                ' class="ajax-img-thumb" alt="' + pro.product_name + '"></span>';
                            $list_product += '<div class="ajax-item-content">';
                            $list_product += '<h4 class="ajax-item-name"><span>' + pro.product_name + '</span></h4>';
                            $list_product += ' <p><span class="ajax-item-price-new">' + pro.price_new +
                                '</span><span class="ajax-item-price-old">Giá cũ: ' + pro.price_old +
                                '</span></p>';
                            $list_product += '</div>';
                            $list_product += '</a>';
                        }
                        if (res != "")
                            $list_product += '<span class="ajax-nav-search-more"><a href="' +
                            $link_bonus +
                            '" title="Click để xem thêm các sản phẩm trong danh sách">Xem thêm</a></span>';
                        $("#ajax-nav-search").show(300);
                        $("#ajax-nav-search").html($list_product);
                    }
                });
            } else {
                $("#ajax-nav-search").html("");
                var $key_word = $(this).val();
            }
        });
        // FILTER PRODUCT SIDEBAR
        $("#list-product-wp #method-filter-nav-pdc").css("background-color", "#FFF");
        $("#filter_product_by_price .fpbp_content .fpbp_content_item input ").on("click ", function() {
            var $price = $(this).val();
            var $data = {
                ajax_filter_price_sidebar: $price,
            };
            if ($price != "") {
                $("#list-product-wp #method-filter-nav-pdc").css("background-color", "#efff18cf").css("font-size", "20").css("font-weight", "600");
                if ($price == 500000)
                    $("#method-filter-nav-pdc").html("----  Danh sách sản phẩm có giá dưới 500.000 VNĐ  ----");
                else if ($price == 1000000)
                    $("#method-filter-nav-pdc").html("----  Danh sách sản phẩm có giá 500.000 VNĐ - 1.000.000 VNĐ  ----");
                else if ($price == 5000000)
                    $("#method-filter-nav-pdc").html("----  Danh sách sản phẩm có giá 1.000.000 VNĐ - 5.000.000 VNĐ  ----");
                else if ($price == 10000000)
                    $("#method-filter-nav-pdc").html("----  Danh sách sản phẩm có giá 5.000.000 VNĐ - 10.000.000 VNĐ  ----");
                else if ($price == 10000001)
                    $("#method-filter-nav-pdc").html("----  Danh sách sản phẩm có giá trên 10.000.000 VNĐ  ----");
            }

            $.ajax({
                url: "{{ route('ajax-filter-product') }}",
                method: "GET",
                data: $data,
                dataType: "json",
                success: function($data) {
                    var $list_product_main = "";
                    $("#list-paging").html("");
                    for (var $pro of $data) {
                        $list_product_main += '<li>';
                        $list_product_main +=
                            '<a href="' + $pro.link_detail_product +
                            '" title="" class="thumb"><img src="' + $pro.product_thumb + '"></a>';
                        $list_product_main += '<a href="' + $pro.link_detail_product +
                            '" title="" class="product-name">';
                        $list_product_main += $pro.product_name;
                        $list_product_main += '</a>';
                        $list_product_main += '<div class="price">';
                        $list_product_main += '<span class="old">' + $pro.price_old + '</span>';
                        $list_product_main += '<span class="new">' + $pro.price_new + '</span>';
                        $list_product_main += '</div>';
                        $list_product_main += '<div class="action clearfix">';
                        $list_product_main += '<a href="' + $pro.url_add_cart +
                            '" title="Thêm giỏ hàng" class="add-cart fl-left">Thêm giỏ hàng</a>';
                        $list_product_main += '<a href="' + $pro.url_buy_now +
                            '" title="Mua ngay" class="buy-now fl-right">Mua ngay</a> ';
                        $list_product_main += '</div>';
                        $list_product_main += '</li>';
                    }
                    if ($data == "") {
                        $list_product_main = "";
                        $list_product_main += '<div class="no-product-found">';
                        $list_product_main +=
                            '<img src="https://www.foodworldmd.com/templates/default-new/images/no-product-found.png" class="no-product-found-icon">';
                        $list_product_main +=
                            '<div class="no-product-found-title">Tiếc quá, không tìm thấy kết quả nào</div>';
                        $list_product_main +=
                            '<div class="no-product-found-hint">Hãy thử tìm mức giá khác nhé!</div>';
                        $list_product_main += '</div>';
                    }
                    $("#list-product-main ul").html($list_product_main);
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + " - " + ajaxOptions + " - " + thrownError);
                }
            });

        });


        // FILTER PRODUCT NAV
        $("#select-filter-product").on("change", function() {
            $method = $(this).val();
            var $data = {
                ajax_filter_method_sidebar: $method,
            };

            $.ajax({
                url: "{{ route('ajax-filter-product') }}",
                method: "GET",
                data: $data,
                dataType: "json",
                success: function($data) {
                    var $list_product_main = "";
                    $("#list-paging").html("");
                    for (var $pro of $data) {
                        $list_product_main += '<li>';
                        $list_product_main +=
                            '<a href="' + $pro.link_detail_product +
                            '" title="" class="thumb"><img src="' + $pro.product_thumb + '"></a>';
                        $list_product_main += '<a href="' + $pro.link_detail_product +
                            '" title="" class="product-name">';
                        $list_product_main += $pro.product_name;
                        $list_product_main += '</a>';
                        $list_product_main += '<div class="price">';
                        $list_product_main += '<span class="old">' + $pro.price_old + '</span>';
                        $list_product_main += '<span class="new">' + $pro.price_new + '</span>';
                        $list_product_main += '</div>';
                        $list_product_main += '<div class="action clearfix">';
                        $list_product_main += '<a href="' + $pro.url_add_cart +
                            '" title="Thêm giỏ hàng" class="add-cart fl-left">Thêm giỏ hàng</a>';
                        $list_product_main += '<a href="' + $pro.url_buy_now +
                            '" title="Mua ngay" class="buy-now fl-right">Mua ngay</a> ';
                        $list_product_main += '</div>';
                        $list_product_main += '</li>';
                    }
                    if ($data == "") {
                        $list_product_main = "";
                        $list_product_main += '<div class="no-product-found">';
                        $list_product_main +=
                            '<img src="https://www.foodworldmd.com/templates/default-new/images/no-product-found.png" class="no-product-found-icon">';
                        $list_product_main +=
                            '<div class="no-product-found-title">Tiếc quá, không tìm thấy kết quả nào</div>';
                        $list_product_main +=
                            '<div class="no-product-found-hint">Hãy thử tìm mức giá khác nhé!</div>';
                        $list_product_main += '</div>';
                    }
                    $("#list-product-main ul").html($list_product_main);
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + " - " + ajaxOptions + " - " + thrownError);
                }
            });
        });
    </script>

</body>

</html>

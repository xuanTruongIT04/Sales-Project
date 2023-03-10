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
                        <a href="" title="" id="payment-link" class="fl-left">H??nh th???c thanh to??n</a>
                        <div id="main-menu-wp" class="fl-right">
                            <ul id="main-menu" class="clearfix">
                                <li>
                                    <a href="trang-chu.html" title="">Trang ch???</a>
                                </li>
                                <li>
                                    <a href="san-pham.html" title="">S???n ph???m</a>
                                </li>
                                <li>
                                    <a href="bai-viet.html" title="">Blog</a>
                                </li>
                                <li>
                                    <a href="gioi-thieu.html" title="">Gi???i thi???u</a>
                                </li>
                                <li>
                                    <a href="lien-he.html" title="">Li??n h???</a>
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
                                    placeholder="Nh???p t??? kh??a t??m ki???m t???i ????y!" value="{{ Old('key_word') }}">
                                <button type="submit" id="sm-s">T??m ki???m</button>

                                <div id="ajax-nav-search" class="ajax-search-result">
                                </div>

                            </form>
                        </div>

                        <div id="action-wp" class="fl-right">
                            <div id="advisory-wp" class="fl-left">
                                <span class="title">T?? v???n</span>
                                <span class="phone">0374.993.702</span>
                            </div>
                            <div id="btn-respon" class="fl-right"><i class="fa fa-bars" aria-hidden="true"></i>
                            </div>
                            <a href="gio-hang.html" title="gi??? h??ng" id="cart-respon-wp" class="fl-right">
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
                                        <p class="desc">C?? <span><?php echo $count_product_order; ?> s???n ph???m</span> trong gi??? h??ng
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
                                                    <p class="qty">S??? l?????ng: <span><?php echo $item->qty; ?></span></p>
                                                </div>
                                            </li>
                                            <?php
                                                }
                                                ?>
                                        </ul>

                                        <div class="total-price clearfix">
                                            <p class="title fl-left">T???ng:</p>
                                            <p class="price fl-right"><?php echo Cart::total() . ' VN??'; ?></p>
                                        </div>
                                        <dic class="action-cart clearfix">
                                            <a href="gio-hang.html" title="Gi??? h??ng" class="view-cart fl-left">Gi???
                                                h??ng</a>
                                            <a href="thanh-toan" title="Thanh to??n" class="checkout fl-right">Thanh
                                                to??n</a>
                                        </dic>
                                    </div>
                                    <?php
                                        } else {
                                ?>
                                    <img src="https://bizweb.dktcdn.net/100/440/012/themes/839260/assets/empty_cart.png?1653287637639"
                                        alt="Tr???ng gi??? h??ng">
                                    <span class="mess-order-header">H??y th??m c??c s???n ph???m v??o gi??? h??ng c???a
                                        b???n!!!</span>
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
                    <p class="desc">ISMART lu??n cung c???p lu??n l?? s???n ph???m ch??nh h??ng c?? th??ng tin r?? r??ng, ch??nh s??ch
                        ??u ????i c???c l???n cho kh??ch h??ng c?? th??? th??nh vi??n.</p>
                    <div id="payment">
                        <div class="thumb">
                            <img src="{{ asset('images/img-foot.png') }}" alt="">
                        </div>
                    </div>
                </div>
                <div class="block menu-ft" id="info-shop">
                    <h3 class="title">Th??ng tin c???a h??ng</h3>
                    <ul class="list-item">
                        <li>
                            <p>39/195 - ???????ng C???u Di???n - Ph?????ng C???u Di???n</p>
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
                    <h3 class="title">Ch??nh s??ch mua h??ng</h3>
                    <ul class="list-item">
                        <li>
                            <a href="" title="">Quy ?????nh - ch??nh s??ch</a>
                        </li>
                        <li>
                            <a href="" title="">Ch??nh s??ch b???o h??nh - ?????i tr???</a>
                        </li>
                        <li>
                            <a href="" title="">Ch??nh s??ch h???i vi???n</a>
                        </li>
                        <li>
                            <a href="" title="">Giao h??ng - l???p ?????t</a>
                        </li>
                    </ul>
                </div>
                <div class="block" id="newfeed">
                    <h3 class="title">B???ng tin</h3>
                    <p class="desc">????ng k?? v???i chung t??i ????? nh???n ???????c th??ng tin ??u ????i s???m nh???t</p>
                    <div id="form-reg">
                        <form method="POST" action="">
                            <input type="email" name="email" id="email" placeholder="Nh???p email t???i ????y">
                            <button type="submit" id="sm-reg">????ng k??</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div id="foot-bot">
            <div class="wp-inner">
                <p id="copyright">?? B???n quy???n thu???c v??? unitop.vn | Php Master</p>
            </div>
        </div>
    </div>
    </div>
    <div id="menu-respon">
        <a href="?page=home" title="" class="logo">VSHOP</a>
        <div id="menu-respon-wp">
            <ul class="" id="main-menu-respon">
                <li>
                    <a href="?page=home" title>Trang ch???</a>
                </li>
                <li>
                    <a href="?page=category_product" title>??i???n tho???i</a>
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
                    <a href="?page=category_product" title>M??y t??nh b???ng</a>
                </li>
                <li>
                    <a href="?page=category_product" title>Laptop</a>
                </li>
                <li>
                    <a href="?page=category_product" title>????? d??ng sinh ho???t</a>
                </li>
                <li>
                    <a href="?page=blog" title>Blog</a>
                </li>
                <li>
                    <a href="#" title>Li??n h???</a>
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
                                '</span><span class="ajax-item-price-old">Gi?? c??: ' + pro.price_old +
                                '</span></p>';
                            $list_product += '</div>';
                            $list_product += '</a>';
                        }
                        if (res != "")
                            $list_product += '<span class="ajax-nav-search-more"><a href="' +
                            $link_bonus +
                            '" title="Click ????? xem th??m c??c s???n ph???m trong danh s??ch">Xem th??m</a></span>';
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
                    $("#method-filter-nav-pdc").html("----  Danh s??ch s???n ph???m c?? gi?? d?????i 500.000 VN??  ----");
                else if ($price == 1000000)
                    $("#method-filter-nav-pdc").html("----  Danh s??ch s???n ph???m c?? gi?? 500.000 VN?? - 1.000.000 VN??  ----");
                else if ($price == 5000000)
                    $("#method-filter-nav-pdc").html("----  Danh s??ch s???n ph???m c?? gi?? 1.000.000 VN?? - 5.000.000 VN??  ----");
                else if ($price == 10000000)
                    $("#method-filter-nav-pdc").html("----  Danh s??ch s???n ph???m c?? gi?? 5.000.000 VN?? - 10.000.000 VN??  ----");
                else if ($price == 10000001)
                    $("#method-filter-nav-pdc").html("----  Danh s??ch s???n ph???m c?? gi?? tr??n 10.000.000 VN??  ----");
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
                            '" title="Th??m gi??? h??ng" class="add-cart fl-left">Th??m gi??? h??ng</a>';
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
                            '<div class="no-product-found-title">Ti???c qu??, kh??ng t??m th???y k???t qu??? n??o</div>';
                        $list_product_main +=
                            '<div class="no-product-found-hint">H??y th??? t??m m???c gi?? kh??c nh??!</div>';
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
                            '" title="Th??m gi??? h??ng" class="add-cart fl-left">Th??m gi??? h??ng</a>';
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
                            '<div class="no-product-found-title">Ti???c qu??, kh??ng t??m th???y k???t qu??? n??o</div>';
                        $list_product_main +=
                            '<div class="no-product-found-hint">H??y th??? t??m m???c gi?? kh??c nh??!</div>';
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

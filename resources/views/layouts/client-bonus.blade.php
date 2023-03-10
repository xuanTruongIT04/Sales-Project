<?php
$list_product_order = get_list_product_order();
$count_product_order = 0;
if (!empty($list_product_order) && is_array($list_product_order)) {
    foreach ($list_product_order as $item) {
        $count_product_order += $item['qty_order'];
    }
}

$total_price = get_total_price();
?>

<!DOCTYPE html>
<html>

<head>
    <title>ISMART STORE</title>
    <base href="{!! url('/') !!}/" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&display=swap"
        rel="stylesheet">
</head>

<body>
    <style>

    </style>
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
                        <a href="" title="" id="logo" class="fl-left"><img
                                src="{{ asset('images/logo.png') }}" /></a>
                        <div id="search-wp" class="fl-left">
                            <form method="GET" action="search/">
                                <input type="text" name="key_word" id="s"
                                    placeholder="Nh???p t??? kh??a t??m ki???m t???i ????y!" value="<?php if (!empty($_GET['key_word'])) {
                                        echo $_GET['key_word'];
                                    } ?>">
                                <button type="submit" id="sm-s">T??m ki???m</button>
                            </form>
                        </div>
                        <div id="action-wp" class="fl-right">
                            <div id="advisory-wp" class="fl-left">
                                <span class="title">T?? v???n</span>
                                <span class="phone">0374.993.702</span>
                            </div>
                            <div id="btn-respon" class="fl-right"><i class="fa fa-bars" aria-hidden="true"></i></div>
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
                                        if (!empty($list_product_order) && is_array($list_product_order)) {
                                        ?>

                                        <ul class="list-cart">
                                            <?php
                                                foreach ($list_product_order as $item) {
                                                ?>
                                            <li class="clearfix">
                                                <a href="<?php echo set_slug_product(get_slug_product($item['product_id'])); ?>" title="<?php echo $item['product_name']; ?>"
                                                    class="thumb fl-left">
                                                    <img src="<?php echo get_product_main_thumb($item['product_id']); ?>" alt="">
                                                </a>
                                                <div class="info fl-right">
                                                    <a href="<?php echo set_slug_product(get_slug_product($item['product_id'])); ?>" title="<?php echo $item['product_name']; ?>"
                                                        class="product-name"><?php echo $item['product_name']; ?></a>
                                                    <p class="price"><?php echo currency_format($item['price_new']); ?></p>
                                                    <p class="qty">S??? l?????ng: <span><?php echo $item['qty_order']; ?></span></p>
                                                </div>
                                            </li>
                                            <?php
                                                }
                                                ?>
                                        </ul>

                                        <div class="total-price clearfix">
                                            <p class="title fl-left">T???ng:</p>
                                            <p class="price fl-right"><?php echo currency_format($total_price); ?></p>
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
    </script>
    {{-- Script Boostrap --}}
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
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

</body>

</html>

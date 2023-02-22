<div
    style="margin:0;padding:0;width:100%!important;font-family:Arial,Helvetica,sans-serif;font-size: 15px;color:#444;line-height:18px">
    <div class="adM">
    </div>
    <div style="width:1170px;height:auto;padding:15px;margin:0px auto;background-color:#f2f2f2">
        <div class="adM">
        </div>
        <div>
            <div class="adM">
            </div>
            <div>
                <div class="adM">
                </div>
                <h1 style="font-size: 19px;font-weight:bold;color:#444;padding:0 0 5px 0;margin:0">Chào
                    {{ $customer_name }}. Đơn <span class="il">hàng</span> của bạn đã <span class="il">đặt</span>
                    <span class="il">thành</span> <span class="il">công</span>!
                </h1>
                <p
                    style="margin:4px 0;font-family:Arial,Helvetica,sans-serif;font-size: 15px;color:#444;line-height:18px;font-weight:normal">
                    Chúng tôi đang chuẩn bị <span class="il">hàng</span> để bàn giao cho đơn vị vận chuyển</p>
                <h3
                    style="font-size: 15px;font-weight:bold;color:#004cffd9;text-transform:uppercase;margin:12px 0 0 0;border-bottom:1px solid #ddd">
                    MÃ ĐƠN <span class="il">HÀNG</span>: <b>{{ $order_code }}</b> <br>
                    <span>NGÀY <span class="il">ĐẶT</span>: {{ $date_order }} </span>
                </h3>
            </div>
            <div>


                <table
                    style="margin:20px 0px;width:100%;border-collapse:collapse;border-spacing:2px;background:#f5f5f5;display:table;box-sizing:border-box;border:0;border-color:grey">
                    <thead style="background:#00800087">
                        <tr>
                            <th
                                style="text-align:left;background-color:#00800087;padding:6px 9px;color:#fff;text-transform:uppercase;font-family:Arial,Helvetica,sans-serif;font-size: 14px;line-height: 20px;">
                                <strong>Tên khách <span class="il">hàng</span></strong>
                            </th>
                            <th
                                style="text-align:left;background-color:#00800087;padding:6px 9px;color:#fff;text-transform:uppercase;font-family:Arial,Helvetica,sans-serif;font-size: 14px;line-height: 20px;">
                                <strong>Email</strong>
                            </th>
                            <th
                                style="text-align:left;background-color:#00800087;padding:6px 9px;color:#fff;text-transform:uppercase;font-family:Arial,Helvetica,sans-serif;font-size: 14px;line-height: 20px;">
                                <strong>SĐT</strong>
                            </th>
                            <th
                                style="text-align:left;background-color:#00800087;padding:6px 9px;color:#fff;text-transform:uppercase;font-family:Arial,Helvetica,sans-serif;font-size: 14px;line-height: 20px;">
                                <strong>Địa chỉ giao <span class="il">hàng</span></strong>
                            </th>
                            <th
                                style="text-align:left;background-color:#00800087;padding:6px 9px;color:#fff;text-transform:uppercase;font-family:Arial,Helvetica,sans-serif;font-size: 14px;line-height: 20px;">
                                <strong>Trạng thái đơn <span class="il">hàng</span></strong>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="border-bottom:1px solid #e1dcdc;font-size:14px;margin-top:10px;line-height:30px">
                            <td style="padding:3px 9px"><strong>{{ $customer_name }}</strong></td>
                            <td style="padding:3px 9px"><strong><a href="mailto:{{ $email }}"
                                        target="_blank">{{ $email }}</a></strong></td>
                            <td style="padding:3px 9px"><strong>{{ $number_phone }}</strong></td>
                            <td style="padding:3px 9px"><strong>{{ $address_delivery }}</strong></td>
                            <td style="padding:3px 9px"><strong><span style="color:#00890099">{!! $order_status !!}</span></strong></td>
                        </tr>
                    </tbody>
                </table>
                @if (!empty($list_product) && is_array($list_product))
                    <table
                        style="margin:20px 0px;width:100%;border-collapse:collapse;border-spacing:2px;background:#f5f5f5;display:table;box-sizing:border-box;border:0;border-color:grey">
                        <thead style="background:#00800087">
                            <tr>
                                <td
                                    style="text-align:left;background-color:#00800087;padding:6px 9px;color:#fff;text-transform:uppercase;font-family:Arial,Helvetica,sans-serif;font-size: 15px;line-height:14px">
                                    <strong>Ảnh</strong>
                                </td>
                                <td
                                    style="text-align:left;background-color:#00800087;padding:6px 9px;color:#fff;text-transform:uppercase;font-family:Arial,Helvetica,sans-serif;font-size: 15px;line-height:14px">
                                    <strong>Tên sản phẩm</strong>
                                </td>
                                <td
                                    style="text-align:left;background-color:#00800087;padding:6px 9px;color:#fff;text-transform:uppercase;font-family:Arial,Helvetica,sans-serif;font-size: 15px;line-height:14px">
                                    <strong>Giá</strong>
                                </td>
                                <td
                                    style="text-align:left;background-color:#00800087;padding:6px 9px;color:#fff;text-transform:uppercase;font-family:Arial,Helvetica,sans-serif;font-size: 15px;line-height:14px">
                                    <strong>Số lượng</strong>
                                </td>
                                <td
                                    style="text-align:left;background-color:#00800087;padding:6px 9px;color:#fff;text-transform:uppercase;font-family:Arial,Helvetica,sans-serif;font-size: 15px;line-height:14px">
                                    <strong><span class="il">Thành</span> tiền</strong>
                                </td>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $total_price = 0;
                                $sub_total = 0;
                                $qty_order = 0;
                            @endphp
                            @foreach ($list_product as $item)
                                @php
                                    $qty_order = get_qty_order($item['id']);
                                    $sub_total =  $item['price_new'] * $qty_order;
                                    $total_price += $sub_total;
                                    $product_thumb = get_product_main_thumb($item["id"]);
                                @endphp
                                <tr style="border-bottom:1px solid #e1dcdc">
                                    <td style="padding:4px"><img
                                            style="display:block;width:55px;height:70px;object-fit:cover"
                                            src="{{ url($product_thumb) }}"
                                            class="CToWUd a6T" data-bit="iit" tabindex="0">
                                        <div class="a6S" dir="ltr"
                                            style="opacity: 0.01; left: 178px; top: 273px;">
                                            <div id=":260" class="T-I J-J5-Ji aQv T-I-ax7 L3 a5q" title="Tải xuống"
                                                role="button" tabindex="0" aria-label="Tải xuống tệp đính kèm "
                                                jslog="91252; u014N:cOuCgd,Kr2w4b,xr6bB" data-tooltip-class="a1V">
                                                <div class="akn">
                                                    <div class="aSK J-J5-Ji aYr"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="padding:3px 9px;vertical-align:middle">
                                        <strong>{{ $item['product_name'] }}</strong></td>
                                    <td style="padding:3px 9px;vertical-align:middle"><strong>{!! currency_format($item['price_new']) !!}</strong></td>
                                    <td style="padding:3px 9px;vertical-align:middle"><strong>{{ $qty_order }}</strong></td>
                                    <td style="padding:3px 9px;vertical-align:middle"><strong>{!! currency_format($sub_total) !!}</strong></td>
                                </tr>
                            @endforeach
                            <tr style="height:12px;background:#00800087;line-height:26px;font-size:14px">
                                <td colspan="4"
                                    style="text-align:left;background:#00800087;padding:6px 9px;margin-top:30px;color:rgb(255,255,255);text-transform:uppercase;font-family:Arial,Helvetica,sans-serif;font-size: 15px;line-height:14px">
                                    <strong>Tổng đơn <span class="il">hàng</span>: </strong>
                                </td>
                                <td colspan="1" style="text-align:left;background:#00800087;padding:6px 9px;color:rgb(255,255,255)">
                                    <strong>{!! currency_format($total_price) !!}</strong>
                                </td>
                            </tr>
                        </tbody>

                    </table>
                @endif
                <div>
                    <p>Quý khách vui lòng giữ lại hóa đơn, hộp sản phẩm và phiếu bảo hành (nếu có) để đổi trả <span
                            class="il">hàng</span> hoặc bảo hành khi cần thiết.</p>
                    <p>Liên hệ Hotline <strong style="color:#099202">0374.993.702</strong> (8-21h cả T7,CN).</p>
            
                    <div style="height:auto">
                        <p>
                            Quý khách nhận được email này vì đã dùng email này <span class="il">đặt</span> <span
                                class="il">hàng</span> tại cửa <span class="il">hàng</span> trực tuyến I8mart.
                            <br><br>
                            Nếu không phải quý khách <span class="il">đặt</span> <span class="il">hàng</span>
                            vui lòng liên hệ số điện thoại 0374.993.702 hoặc email <a style="color: red; font-weight: 550;" href="mailto:{{ $email }}"
                            target="_blank">{{ $email }}</a> để hủy đơn <span
                                class="il">hàng</span>
                        </p>
                    </div>
                    <p><strong>ISmart cảm ơn quý khách đã <span class="il">đặt</span> <span
                        class="il">hàng</span>, chúng tôi sẽ không ngừng nổ lực để phục vụ quý khách tốt
                    hơn!</strong></p>
                </div>
              
            </div>
        </div>
    </div>
</div>


@extends('layouts.admin')

@section('content')
    @php
        $qty_remain = get_total_quantity_remain();
        $qty_sold = get_total_quantity_sold();
        $order_success = get_order_success();
        $order_pending = get_order_pending();
        $order_shipping = get_order_shipping();
        $total_product_sales = get_total_product_sales();
    @endphp
    <div class="container-fluid py-5">
        <div class="row">
            <div class="col pr-8">
                <div class="card card-dasboard text-white bg-info mb-3">
                    <div class="card-header card-header-dasboard">SẢN PHẨM TRONG KHO</div>
                    <div class="card-body card-body-dasboard">
                        <h5 class="card-title card-title-dasboard text-center individual-number">{!! number_format($qty_remain) !!}</h5>
                        <p class="card-text card-text-dasboard">Số lượng sản phẩm trong kho</p>
                    </div>
                </div>
            </div>
            <div class="col pl-8 pr-8">
                <div class="card card-dasboard text-dark bg-light mb-3">
                    <div class="card-header card-header-dasboard">SẢN PHẨM BÁN RA</div>
                    <div class="card-body card-body-dasboard">
                        <h5 class="card-title card-title-dasboard text-center individual-number">{{ $qty_sold }}</h5>
                        <p class="card-text card-text-dasboard">Số lượng sản phẩm đã bán</p>
                    </div>
                </div>
            </div>

            <div class="col pl-8 pr-8">
                <div class="card card-dasboard text-white bg-warning mb-3">
                    <div class="card-header card-header-dasboard">DOANH SỐ</div>
                    <div class="card-body card-body-dasboard">
                        <h5 class="card-title card-title-dasboard text-center">{!! number_format($total_product_sales) !!}</h5>
                        <p class="card-text card-text-dasboard">(VNĐ) - Doanh số hệ thống </p>
                    </div>
                </div>
            </div>

            <div class="col pl-8 pr-8">
                <div class="card card-dasboard text-white bg-success mb-3">
                    <div class="card-header card-header-dasboard max-width-13r ">ĐƠN HÀNG THÀNH CÔNG</div>
                    <div class="card-body card-body-dasboard">
                        <h5 class="card-title card-title-dasboard text-center individual-number">{{ $order_success }}</h5>
                        <p class="card-text card-text-dasboard">Đơn hàng giao dịch thành công</p>
                    </div>
                </div>
            </div>
            <div class="col pl-8 pr-8">
                <div class="card card-dasboard text-white bg-primary mb-3">
                    <div class="card-header card-header-dasboard max-width-10r">ĐANG XỬ LÝ</div>
                    <div class="card-body card-body-dasboard">
                        <h5 class="card-title card-title-dasboard text-center individual-number">{{ $order_pending }}</h5>
                        <p class="card-text card-text-dasboard">Đơn hàng đang xử lý</p>
                    </div>
                </div>
            </div>
            <div class="col pl-8">
                <div class="card card-dasboard text-white bg-danger mb-3">
                    <div class="card-header card-header-dasboard">ĐANG VẬN CHUYỂN</div>
                    <div class="card-body card-body-dasboard">
                        <h5 class="card-title card-title-dasboard text-center individual-number">{{ $order_shipping }}</h5>
                        <p class="card-text card-text-dasboard">Đơn hàng đang vận chuyển</p>
                    </div>
                </div>
            </div>

        </div>
        <!-- end analytic  -->
        <div class="card">
            <div class="card-header font-weight-bold">
                ĐƠN HÀNG MỚI
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Mã</th>
                            <th scope="col">Khách hàng</th>
                            <th scope="col">Số lượng</th>
                            <th scope="col">Giá trị</th>
                            <th scope="col">Trạng thái</th>
                            <th scope="col">Thời gian</th>
                            <th scope="col">Tác vụ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($count_order > 0)
                            @php
                                $cnt = empty(request()->page) ? 0 : (request()->page - 1) * 20;
                                $total_price = 0;
                            @endphp
                            @foreach ($orders as $order)
                                @php
                                    $cnt++;
                                    $order_id = $order->id;
                                    $order_total = DB::table('order_product')
                                        ->selectRaw("sum(number_order) as 'qty_order'")
                                        ->where('order_id', $order_id)
                                        ->first()->qty_order;
                                    $total_price = get_total_price_order($order_id);
                                @endphp
                                <tr>
                                    <th scope=" row">{{ $cnt }}</th>
                                    <td><a class="text-primary"
                                            href="{{ route('admin.order.detail', $order->id) }}">{{ $order->order_code }}</a>
                                    </td>
                                    <td>{{ $order->customer->customer_name }}<BR>{{ $order->customer->number_phone }}</td>
                                    <td>{{ $order_total }}</td>
                                    <td>{!! currency_format($total_price) !!}</td>
                                    <td>{!! field_status_order($order->order_status) !!}</td>
                                    <td>{!! date('H:i:s-d/m/Y', strtotime($order->created_at)) !!}</td>

                                    <td>
                                        <a href="{{ route('admin.order.edit', $order->id) }}"
                                            class="btn btn-success btn-sm rounded-0 text-white" type="button"
                                            data-toggle="tooltip" data-placement="top" title="Edit"><i
                                                class="fa fa-edit"></i></a>
                                        <a href="{{ route('admin.order.delete', $order->id) }}"
                                            class="btn btn-danger btn-sm rounded-0 text-white" type="button"
                                            data-toggle="tooltip"
                                            onclick="return confirm('Bạn có chắc chắn muốn xoá đơn hàng {{ $order->order_code }}?')"
                                            data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="7" class="bg-white">Không tìm thấy đơn hàng nào!</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
              {{ $orders -> links() }}
            </div>
        </div>

    </div>
@endsection

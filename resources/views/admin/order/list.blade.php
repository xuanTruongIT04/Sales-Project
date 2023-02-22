@extends('layouts.admin')

@php
    $total_product_sales = get_total_product_sales();
@endphp

@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
                <h5 class="m-0 ">Danh sách đơn hàng</h5>
                <div class="form-search form-inline">
                    <form action="#" method="">
                        @csrf
                        <input type="text" class="form-control form-search" name="key_word"
                            value="{{ request()->input('key_word') }}" placeholder="Tìm kiếm">
                        <input type="submit" name="btn_search" value="Tìm kiếm" class="btn btn-primary">
                        <input type="hidden" name="status"
                            value="{{ empty(request()->input('status')) ? 'active' : request()->input('status') }}" />
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="analytic">
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'active']) }}" class="text-primary">Kích
                        hoạt<span class="text-muted">({{ $count_order_status[0] }})</span></a>
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'delivery_successful']) }}"
                        class="text-primary">Giao hàng thành công<span
                            class="text-muted">({{ $count_order_status[1] }})</span></a>
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'shipping']) }}" class="text-primary">Đang vận
                        chuyển
                        <span class="text-muted">({{ $count_order_status[2] }})</span></a>
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'pending']) }}" class="text-primary">Chờ xét duyệt
                        <span class="text-muted">({{ $count_order_status[3] }})</span></a>
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'trashed']) }}" class="text-primary">Vô hiệu
                        hoá<span class="text-muted">({{ $count_order_status[4] }})</span></a>
                    <span class="badge badge-warning sales">Doanh số: {!! number_format($total_product_sales) !!} VNĐ</span>

                </div>
                <form action="{{ url('admin/order/action') }}" method="GET">
                    <div class="form-action form-inline py-3">
                        <select class="form-control mr-1" name="act" id="">
                            <option value="">Chọn</option>
                            @foreach ($list_act as $k => $act)
                                <option value="{{ $k }}">{{ $act }}</option>
                            @endforeach
                        </select>
                        <input type="submit" name="btn-search" value="Áp dụng" class="btn btn-primary">
                    </div>
                    @if (!empty(request()->key_word))
                        <div class="count-user"><span>Kết quả tìm kiếm: <b>{{ $count_order }}</b> đơn hàng</span></div>
                    @endif
                    <table class="table table-striped table-checkall">
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" name="checkAll">
                                </th>
                                <th scope="col">#</th>
                                <th scope="col">Mã đơn hàng</th>
                                <th scope="col">Tên khách hàng</th>
                                <th scope="col">Tổng giá</th>
                                <th scope="col">Trạng thái</th>
                                <th scope="col">Thời gian đặt</th>
                                @if (request()->status != 'trashed')
                                    <th scope="col">Chi tiết</th>
                                @endif
                                <th scope="col">Tác vụ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($count_order > 0)
                                @php
                                $total_price = 0;
                                    $cnt = empty(request() -> page) ? 0 : (request() -> page - 1) * 20;
                                @endphp
                                @foreach ($orders as $order)
                                    @php
                                    $total_price = get_total_price_order($order->id);
                                        $cnt++;
                                    @endphp
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="list_check[]" value="{{ $order->id }}"">
                                        </td>
                                        <th scope=" row">{{ $cnt }}</th>
                                        <td><a class="text-primary"
                                                href="{{ route('admin.order.edit', $order->id) }}">{{ $order->order_code }}</a>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.customer.edit', $order->customer->id) }}"
                                                class="text-primary">{{ $order->customer->customer_name }}
                                            </a>
                                        </td>
                               
                                        <td>{!! currency_format($total_price) !!}</td>
                                        <td>{!! field_status_order($order->order_status) !!}</td>
                                        <td>{!! date('H:i:s-d/m/Y', strtotime($order->created_at)) !!}</td>
                                        @if (request()->status != 'trashed')
                                            <td><a href="{{ route('admin.order.detail', $order->id) }}"
                                                    class="btn btn-success btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip" data-placement="top" title="Chi tiết">Chi tiết</a>
                                            </td>
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
                                        @else
                                            <td>
                                                <a href="{{ route('admin.order.restore', $order->id) }}"
                                                    class="btn btn-success btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip"
                                                    onclick="return confirm('Bạn có chắc chắn muốn khôi phục đơn hàng {{ $order->order_code }}?')"
                                                    data-placement="top" title="Restore"><i
                                                        class="fas fa-trash-restore-alt"></i></a>
                                                <a href="{{ route('admin.order.delete', $order->id) }}"
                                                    class="btn btn-danger btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip"
                                                    onclick="return confirm('Bạn có chắc chắn muốn xoá vĩnh viễn đơn hàng {{ $order->order_code }}?')"
                                                    data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7" class="bg-white">Không tìm thấy đơn hàng nào!</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </form>
                {{ $orders->links() }}
            </div>
        </div>
    </div>

@endsection

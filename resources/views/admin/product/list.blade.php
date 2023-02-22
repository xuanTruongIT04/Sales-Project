@extends('layouts.admin')


@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
                <div id="title-btn-add">
                    <h5 class="m-0 ">Danh sách sản phẩm</h5>
                    <a href="{{ route("admin.product.add") }}" class="btn btn-primary ml-3">THÊM MỚI</a>
                </div>
                <div class="form-search form-inline">
                    <form action="#" method="GET">
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
                        hoạt<span class="text-muted">({{ $count_product_status[0] }})</span></a>
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'licensed']) }}" class="text-primary">Đã cấp quyền
                        <span class="text-muted">({{ $count_product_status[1] }})</span></a>
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'pending']) }}" class="text-primary">Chờ xét duyệt
                        <span class="text-muted">({{ $count_product_status[2] }})</span></a>
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'trashed']) }}" class="text-primary">Vô hiệu
                        hoá<span class="text-muted">({{ $count_product_status[3] }})</span></a>
                </div>
                <form action="{{ url('admin/product/action') }}" method="GET">
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
                        <div class="count-product"><span>Kết quả tìm kiếm: <b>{{ $count_product }}</b> sản phẩm</span>
                        </div>
                    @endif
                    <table class="table table-striped table-checkall">
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" name="checkAll">
                                </th>
                                <th scope="col">#</th>
                                <th scope="col">Mã sản phẩm</th>
                                <th scope="col">Hình ảnh</th>
                                <th scope="col">Tên sản phẩm</th>
                                <th scope="col">Giá cũ</th>
                                <th scope="col">Giá mới</th>
                                <th scope="col">Đã bán</th>
                                <th scope="col">Số lượng kho</th>
                                <th scope="col">Trạng thái</th>
                                <th scope="col">Tác vụ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($count_product > 0)
                                @php
                                    $cnt = empty(request()->page) ? 0 : (request()->page - 1) * 20;
                                @endphp
                                @foreach ($products as $product)
                                    @php
                                        $cnt++;
                                    @endphp
                                    <tr class="row-in-list">
                                        <td>
                                            <input type="checkbox" name="list_check[]" value="{{ $product->id }}"">
                                        </td>
                                        <th scope=" row">{{ $cnt }}</th>
                                        <td>{{ $product->product_code }}</td>
                                        @if (request()->status != 'trashed')
                                            <td>
                                                <div class=" product_thumb_main">
                                                    <a href="{{ route('admin.product.edit', $product->id) }}"
                                                        class="thumbnail">
                                                        <img src="@if (!empty(get_main_image($product->id))) {{ url(get_main_image($product->id)) }}@else{{ url('public/uploads/img-product2.png') }} @endif"
                                                            alt="Ảnh của sản phẩm {{ $product->product_name }}"
                                                            title="Ảnh của sản phẩm {{ $product->product_name }}"
                                                            id="thumbnail_img">
                                                    </a>
                                                    <a class="cover-bonus-product-thumb" title="Cập nhật danh sách hình ảnh cho sản phẩm tại đây"
                                                        href="{{ route('admin.image.addMulti', $product->id) }}">
                                                        <img src="{{ url('public/uploads/hinhdaucong.png') }}"
                                                            alt="" class="thumbnail_img bonus_product_thumb">
                                                    </a>
                                                </div>
                                            </td>
                                        @else
                                            <td>
                                                <div href="{{ route('admin.product.edit', $product->id) }}"
                                                    class="thumbnail">
                                                    <img src="@if (!empty(get_main_image($product->id))) {{ url(get_main_image($product->id)) }}@else{{ url('public/uploads/img-product2.png') }} @endif"
                                                        alt="Ảnh của sản phẩm {{ $product->product_name }}"
                                                        title="Ảnh của sản phẩm {{ $product->product_name }}"
                                                        id="thumbnail_img">
                                                </div>
                                            </td>
                                        @endif
                                        <td><a class="text-primary" 
                                                href="{{ route('admin.product.edit', $product->id) }}">{{ $product->product_name }}</a>
                                        </td>
                                        <td>{!! currency_format($product->price_old) !!}</td>
                                        <td>{!! currency_format($product->price_new) !!}</td>
                                        <td>{{ $product->qty_sold }}</td>
                                        <td>{{ $product->qty_remain }}</td>
                                        <td>{!! field_status($product->product_status) !!}</td>
                                        @if (request()->status != 'trashed')
                                            <td>
                                                <a href="{{ route('admin.product.edit', $product->id) }}"
                                                    class="btn btn-success btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip" data-placement="top" title="Edit"><i
                                                        class="fa fa-edit"></i></a>
                                                <a href="{{ route('admin.product.delete', $product->id) }}"
                                                    class="btn btn-danger btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip"
                                                    onclick="return confirm('Bạn có chắc chắn muốn xoá sản phẩm {{ $product->product_name }}?')"
                                                    data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
                                            </td>
                                        @else
                                            <td>
                                                <a href="{{ route('admin.product.restore', $product->id) }}"
                                                    class="btn btn-success btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip"
                                                    onclick="return confirm('Bạn có chắc chắn muốn khôi phục sản phẩm {{ $product->product_name }}?')"
                                                    data-placement="top" title="Restore"><i
                                                        class="fas fa-trash-restore-alt"></i></a>
                                                <a href="{{ route('admin.product.delete', $product->id) }}"
                                                    class="btn btn-danger btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip"
                                                    onclick="return confirm('Bạn có chắc chắn muốn xoá vĩnh viễn sản phẩm {{ $product->product_name }}?')"
                                                    data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
                                            </td>
                                        @endif

                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7" class="bg-white">Không tìm thấy sản phẩm nào!</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </form>
                {{ $products->links() }}
            </div>
        </div>
    </div>

@endsection

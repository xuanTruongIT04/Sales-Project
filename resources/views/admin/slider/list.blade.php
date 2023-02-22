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
                    <h5 class="m-0 ">Danh sách slider</h5>
                    <a href="{{ route('admin.slider.add') }}" class="btn btn-primary ml-3">THÊM MỚI</a>
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
                        hoạt<span class="text-muted">({{ $count_slider_status[0] }})</span></a>
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'licensed']) }}" class="text-primary">Đã cấp quyền
                        <span class="text-muted">({{ $count_slider_status[1] }})</span></a>
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'pending']) }}" class="text-primary">Chờ xét duyệt
                        <span class="text-muted">({{ $count_slider_status[2] }})</span></a>
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'trashed']) }}" class="text-primary">Vô hiệu
                        hoá<span class="text-muted">({{ $count_slider_status[3] }})</span></a>
                </div>
                <form action="{{ url('admin/slider/action') }}" method="GET">
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
                        <div class="count-slider"><span>Kết quả tìm kiếm: <b>{{ $count_slider }}</b> slider</span></div>
                    @endif
                    <table class="table table-striped table-checkall">
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" name="checkAll">
                                </th>
                                <th scope="col">#</th>
                                <th scope="col">Ảnh slider</th>
                                <th scope="col">Tên slider</th>
                                <th scope="col">Thứ tự</th>
                                <th scope="col">Ngày tạo</th>
                                <th scope="col">Trạng thái</th>
                                <th scope="col">Tác vụ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($count_slider > 0)
                                @php
                                    $cnt = empty(request()->page) ? 0 : (request()->page - 1) * 20;
                                @endphp
                                @foreach ($sliders as $slider)
                                    @php
                                        $cnt++;
                                    @endphp
                                    <tr class="row-in-list">
                                        <td>
                                            <input type="checkbox" name="list_check[]" value="{{ $slider->id }}"">
                                        </td>
                                        <th scope=" row">{{ $cnt }}</th>
                                        @if (request()->status != 'trashed')
                                            <td><a href="{{ route('admin.slider.edit', $slider->id) }}"
                                                    class="thumbnail h-100">
                                                    <img src="@if (!empty($slider->image->image_link)) {{ url($slider->image->image_link) }}@else{{ url('public/uploads/img-product2.png') }} @endif"
                                                        alt="Ảnh đại diện của {{ $slider->slider_name }}"
                                                        title="Ảnh đại diện của {{ $slider->slider_name }}"
                                                        id="thumbnail_img"></a>
                                            </td>
                                        @else
                                            <td>
                                                <div href="{{ route('admin.slider.edit', $slider->id) }}"
                                                    class="thumbnail h-100">
                                                    <img src="@if (!empty($slider->image->image_link)) {{ url($slider->image->image_link) }}@else{{ url('public/uploads/img-product2.png') }} @endif"
                                                        alt="Ảnh đại diện của {{ $slider->slider_name }}"
                                                        title="Ảnh đại diện của {{ $slider->slider_name }}"
                                                        id="thumbnail_img">
                                                </div>
                                            </td>
                                        @endif
                                        <td> <a href="{{ route('admin.slider.edit', $slider->id) }}"
                                                class="text-primary">{{ $slider->slider_name }}
                                            </a></td>
                                        <td>{!! $slider->order !!}</td>


                                        <td>{!! date('H:i:s-d/m/Y', strtotime($slider->created_at)) !!}</td>
                                        <td>{!! field_status($slider->slider_status) !!}</td>
                                        @if (request()->status != 'trashed')
                                            <td>
                                                <a href="{{ route('admin.slider.edit', $slider->id) }}"
                                                    class="btn btn-success btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip" data-placement="top" title="Edit"><i
                                                        class="fa fa-edit"></i></a>
                                                <a href="{{ route('admin.slider.delete', $slider->id) }}"
                                                    class="btn btn-danger btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip"
                                                    onclick="return confirm('Bạn có chắc chắn muốn xoá slider {{ $slider->slider_name }}?')"
                                                    data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
                                            </td>
                                        @else
                                            <td>
                                                <a href="{{ route('admin.slider.restore', $slider->id) }}"
                                                    class="btn btn-success btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip"
                                                    onclick="return confirm('Bạn có chắc chắn muốn khôi phục slider {{ $slider->slider_name }}?')"
                                                    data-placement="top" title="Restore"><i
                                                        class="fas fa-trash-restore-alt"></i></a>
                                                <a href="{{ route('admin.slider.delete', $slider->id) }}"
                                                    class="btn btn-danger btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip"
                                                    onclick="return confirm('Bạn có chắc chắn muốn xoá vĩnh viễn slider {{ $slider->slider_name }}?')"
                                                    data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
                                            </td>
                                        @endif

                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7" class="bg-white">Không tìm thấy slider nào!</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </form>
                {{ $sliders->links() }}
            </div>
        </div>
    </div>

@endsection

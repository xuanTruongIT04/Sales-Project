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
                    <h5 class="m-0 ">Danh sách hình ảnh</h5>
                    <a href="{{ route("admin.image.add") }}" class="btn btn-primary ml-3">THÊM MỚI</a>
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
                        hoạt<span class="text-muted">({{ $count_image_status[0] }})</span></a>
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'trashed']) }}" class="text-primary">Vô hiệu
                        hoá<span class="text-muted">({{ $count_image_status[1] }})</span></a>
                </div>
                <form action="{{ url('admin/image/action') }}" method="GET">
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
                        <div class="count-user"><span>Kết quả tìm kiếm: <b>{{ $count_image }}</b> hình ảnh</span></div>
                    @endif
                    <table class="table table-striped table-checkall">
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" name="checkAll">
                                </th>
                                <th scope="col">#</th>
                                <th scope="col">Hình ảnh</th>
                                <th scope="col">Đường dẫn ảnh</th>
                                <th scope="col">Ngày tạo</th>
                                <th scope="col">Tác vụ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($count_image > 0)
                                @php
                                    $cnt = empty(request() -> page) ? 0 : (request() -> page - 1) * 20;
                                @endphp
                                @foreach ($images as $image)
                                    @php
                                        $cnt++;
                                    @endphp
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="list_check[]" value="{{ $image->id }}"">
                                        </td>
                                        <th scope=" row">{{ $cnt }}</th>
                                        <td><a href="{{ route('admin.image.edit', $image->id) }}" class="thumbnail"><img
                                                    src="
                                            @if (!empty($image->image_link)) {{ url($image->image_link) }}
                                                   @else
                                                       {{ url('public/uploads/img-product2.png') }} @endif"
                                                    alt="Ảnh đại diện của {{ $image->image_link }}"
                                                    title="Ảnh đại diện của {{ $image->image_link }}"
                                                    id="thumbnail_img"></a>
                                        </td>
                                        <td>{{ $image->image_link }}</td>
                                        <td>{!! date('H:i:s-d/m/Y', strtotime($image->created_at)) !!}</td>
                                        @if (request()->status != 'trashed')
                                            <td>
                                                <a href="{{ route('admin.image.edit', $image->id) }}"
                                                    class="btn btn-success btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip" data-placement="top" title="Edit"><i
                                                        class="fa fa-edit"></i></a>
                                                <a href="{{ route('admin.image.delete', $image->id) }}"
                                                    class="btn btn-danger btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip"
                                                    onclick="return confirm('Bạn có chắc chắn muốn xoá hình ảnh {{ $image->image_link }}?')"
                                                    data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
                                            </td>
                                        @else
                                            <td>
                                                <a href="{{ route('admin.image.restore', $image->id) }}"
                                                    class="btn btn-success btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip"
                                                    onclick="return confirm('Bạn có chắc chắn muốn khôi phục hình ảnh {{ $image->image_link }}?')"
                                                    data-placement="top" title="Restore"><i
                                                        class="fas fa-trash-restore-alt"></i></a>
                                                <a href="{{ route('admin.image.delete', $image->id) }}"
                                                    class="btn btn-danger btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip"
                                                    onclick="return confirm('Bạn có chắc chắn muốn xoá vĩnh viễn hình ảnh {{ $image->image_link }}?')"
                                                    data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7" class="bg-white">Không tìm thấy ảnh nào!</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </form>
                {{ $images->links() }}
            </div>
        </div>
    </div>

@endsection

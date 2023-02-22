@extends('layouts.admin')

@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                Chỉnh sửa thông tin trang
            </div>
            <div class="card-body">
                <form id="form-upload" action="{{ url("admin/page/update/{$page->id}") }}" method='POST'
                    enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label for="name" class="fw-550">Tiêu đề</label>
                        <input class="form-control" type="text" name="page_title" id="name"
                            value="{{ $page -> page_title }}">
                        @error('page_title')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="slug" class="fw-550">Slug (Friendly Url)</label>
                        <input class="form-control" type="text" name="slug" id="slug"
                            value="{{ $page -> slug }}">
                        @error('slug')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="page-desc" class="fw-550">Mô tả</label>
                        <textarea class="form-control" name="page_desc" id="page-desc">{{ $page -> page_desc }}</textarea>
                        @error('page_desc')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="page-content" class="fw-550">Nội dung trang</label>
                        <textarea class="form-control" name="page_content" id="page-content">{{ $page -> page_content }}</textarea>
                        @error('page_content')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="status" class="fw-550">Trạng thái</label>

                        @if (!empty($page->page_status))
                            {!! template_update_status($page->page_status) !!}
                        @endif

                        @error('status')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror

                    </div>

                    <input type="submit" name="btn_update" class="btn btn-primary mt-4" value="Cập nhật">
                </form>
            </div>
        </div>
    </div>
@endsection

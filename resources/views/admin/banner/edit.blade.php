@extends('layouts.admin')

@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                Chỉnh sửa thông tin banner
            </div>
            <div class="card-body">
                <form id="form-upload" action="{{ url("admin/banner/update/{$banner->id}") }}" method='POST'
                    enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="name" class="fw-550">Tên banner</label>
                        <input class="form-control" type="text" name="banner_name" id="name"
                            value="{{ $banner->banner_name }}">
                        @error('banner_name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for='banner_thumb' class="fw-550">Ảnh banner</label> <BR>
                        <div id="uploadFile">
                            <input type="file" name="banner_thumb" class="form-control-file upload_file"
                                id="banner_thumb" onchange="upload_image(this)">
                            <img src={{ url($banner->image->image_link) }} id="image_upload_file" class="mt-3">
                        </div>

                        @error('banner_thumb')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="status" class="fw-550">Trạng thái</label>

                        @if (!empty($banner->banner_status))
                            {!! template_update_status($banner->banner_status) !!}
                        @endif

                        @error('status')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror

                    </div>

                    <input type="submit" name="btn_update" class="btn btn-primary mt-4" value="Thêm mới">
                </form>
            </div>
        </div>
    </div>
@endsection

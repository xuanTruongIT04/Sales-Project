@extends('layouts.admin')

@section('content')
<div id="content" class="container-fluid">
    <div class="card">
        <div class="card-header font-weight-bold">
            Thêm hình ảnh
        </div>
        <div class="card-body">
            <form action="{{ url("admin/image/store") }}" method='POST' enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label for='image-link' class="fw-550">Hình ảnh</label> <BR>
                    <div id="uploadFile">
                        <input type="file" name="image_link" id="image-link" class="form-control-file upload_file" onchange="upload_image(this)">
                        <img src={{ url('public/uploads/img-product1.png') }} id="image_upload_file" class="mt-3">
                    </div>

                    @error('image_link')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <input type="submit" name="btn_add" class="btn btn-primary" value="Thêm mới">
            </form>
        </div>
    </div>
</div>
@endsection

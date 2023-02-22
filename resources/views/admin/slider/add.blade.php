@extends('layouts.admin')


@section('content')
<div id="content" class="container-fluid">
    <div class="card">
        <div class="card-header font-weight-bold">
            Thêm slider
        </div>
        <div class="card-body">
            <form action="{{ url('admin/slider/store') }}" method='POST' enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="name" class="fw-550">Tên slider</label>
                    <input class="form-control" type="text" name="slider_name" id="name" value="{{ Old('slider_name') }}">
                    @error('slider_name')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div> 

                <div class="form-group">
                    <label for='slider_thumb' class="fw-550">Ảnh slider</label> <BR>
                    <div id="uploadFile">
                        <input type="file" name="slider_thumb" class="form-control-file upload_file" id="slider_thumb"onchange="upload_image(this)">
                        <img src={{ url("public/uploads/img-product-4.png") }} id="image_upload_file" class="mt-2 w500-h250" >
                    </div>

                    @error('slider_thumb')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="order" class="fw-550">Thứ tự</label>
                    <input class="form-control" type="number" min="0" name="order" id="order" value="{{ !empty(Old('level')) ? Old('level') : 0 }}">
                    @error('order')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div> 

                <input type="submit" name="btn_add" class="btn btn-primary" value="Thêm mới">
            </form>
        </div>
    </div>
</div>
@endsection

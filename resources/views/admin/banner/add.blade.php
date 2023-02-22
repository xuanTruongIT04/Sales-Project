@extends('layouts.admin')

@section('content')
<div id="content" class="container-fluid">
    <div class="card">
        <div class="card-header font-weight-bold">
            Thêm banner
        </div>
        <div class="card-body">
            <form action="{{ url('admin/banner/store') }}" method='POST' enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="name" class="fw-550">Tên banner</label>
                    <input class="form-control" type="text" name="banner_name" id="name" value="{{ Old('banner_name') }}">
                    @error('banner_name')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div> 

                <div class="form-group">
                    <label for='banner_thumb' class="fw-550">Ảnh banner</label> <BR>
                    <div id="uploadFile">
                        <input type="file" name="banner_thumb" class="form-control-file upload_file" id="banner_thumb"onchange="upload_image(this)">
                        <img src={{ url("public/uploads/img-product-3.png") }} id="image_upload_file" class="mt-2 w250-h500" >
                    </div>

                    @error('banner_thumb')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <input type="submit" name="btn_add" class="btn btn-primary" value="Thêm mới">
            </form>
        </div>
    </div>
</div>
@endsection

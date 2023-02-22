@extends('layouts.admin')

@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                Chỉnh sửa thông tin slider
            </div>
            <div class="card-body">
                <form id="form-upload" action="{{ url("admin/slider/update/{$slider->id}") }}" method='POST'
                    enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="name" class="fw-550">Tên slider</label>
                        <input class="form-control" type="text" name="slider_name" id="name"
                            value="{{ $slider->slider_name }}">
                        @error('slider_name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for='slider_thumb' class="fw-550">Ảnh slider</label> <BR>
                        <div id="uploadFile">
                            <input type="file" name="slider_thumb" class="form-control-file upload_file"
                                id="slider_thumb" onchange="upload_image(this)">
                            <img src={{ url($slider->image->image_link) }} id="image_upload_file" class="mt-3">
                        </div>

                        @error('slider_thumb')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>


                    <div class="form-group">
                        <label for="order" class="fw-550">Thứ tự</label>
                        <input class="form-control" type="number" min="0" name="order" id="order"
                            value="{{  $slider->order }}">
                        @error('order')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="status" class="fw-550">Trạng thái</label>

                        @if (!empty($slider->slider_status))
                            {!! template_update_status($slider->slider_status) !!}
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

@extends('layouts.admin')

@section('content')
<div id="content" class="container-fluid">
    <div class="card">
        <div class="card-header font-weight-bold">
            Chỉnh sửa thông tin hình ảnh
        </div>
        <div class="card-body">
            <form action="{{ url("admin/image/update/{$image->id}") }}" method='POST'>
                @csrf
                <div class="form-group">
                    <label for='image_upload_file' class="fw-550">Ảnh đại diện <i class="text-muted">(Không cập nhật ảnh)</i></label> <BR>
                    <div id="uploadFile">
                        <img src={{ url($image->image_link) }} id="image_upload_file">
                    </div>
                </div>
                <div class="form-group">
                    <label for="path" class="fw-550">Đường dẫn hình ảnh</label>
                    <input class="form-control" type="text" name=" image_link" id="path" value="{{ $image-> image_link }}">
                    @error(' image_link')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <input type="submit" name="btn_update" class="btn btn-primary" value="Cập nhật">
            </form>
        </div>
    </div>
</div>
@endsection

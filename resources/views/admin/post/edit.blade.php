@extends('layouts.admin')

@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                Chỉnh sửa thông tin trang
            </div>
            <div class="card-body">
                <form id="form-upload" action="{{ url("admin/post/update/{$post->id}") }}" method='POST'
                    enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label for="name" class="fw-550">Tiêu đề</label>
                        <input class="form-control" type="text" name="post_title" id="name"
                            value="{{ $post->post_title }}">
                        @error('post_title')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="slug" class="fw-550">Slug (Friendly Url)</label>
                        <input class="form-control" type="text" name="slug" id="slug"
                            value="{{ $post->slug }}">
                        @error('slug')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="post-desc" class="fw-550">Mô tả</label>
                        <textarea class="form-control" name="post_desc" id="post-desc">{{ $post->post_desc }}</textarea>
                        @error('post_desc')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="post-content" class="fw-550">Nội dung trang</label>
                        <textarea class="form-control" name="post_content" id="post-content">{{ $post->post_content }}</textarea>
                        @error('post_content')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for='post-thumb' class="fw-550">Hình ảnh</label> <BR>
                        <div id="uploadFile">
                            <input type="file" id="post-thumb" name="post_thumb" class="form-control-file upload_file"
                                onchange="upload_image(this)">
                            <img src="@if (!empty($post->image->image_link)) {{ url($post->image->image_link) }} @else{{ url('public/uploads/img-product2.png') }} @endif"
                                id="image_upload_file" alt="Ảnh của bài viết có tiêu đề {{ $post->post_title }}"
                                title="Ảnh của bài viết có tiêu đề {{ $post->post_title }}" class="mt-3">
                        </div>

                        @error('post_thumb')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    {{-- Danh mục bài viết cha --}}
                    <div class="form-group">
                        <label for="post_cat" class="fw-550">Danh mục cha</label>
                        @if (!empty($list_post_cat))
                            <select name="post_cat" id="post_cat" class="form-control">
                                <option value="">-- Chọn danh mục --</option>
                                <option value="-1" style="font-style: italic;">Không có danh mục cha</option>
                                @foreach ($list_post_cat as $item)
                                    @php
                                        $sel = '';
                                    @endphp
                                    @if ($post->post_cat_id == $item->id)
                                        @php
                                            $sel = "selected = 'seleceted'";
                                        @endphp
                                    @endif
                                    <option value="{{ $item->id }}" {{ $sel }}>
                                        {{ str_repeat('-', $item->level) . ' ' . $item->post_cat_title }}
                                    </option>
                                @endforeach
                            </select>
                        @else
                            <p class="empty-task">Không tồn tại danh mục nào</p>
                        @endif
                        @error('post_cat')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="status" class="fw-550">Trạng thái</label>

                        @if (!empty($post->post_status))
                            {!! template_update_status($post->post_status) !!}
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

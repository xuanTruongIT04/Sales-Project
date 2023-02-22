    @extends('layouts.admin')
    @section('content')
        <div id="content" class="container-fluid">
            <div class="card">
                <div class="card-header font-weight-bold">
                    Thêm bài viết
                </div>
                <div class="card-body">
                    <form action="{{ url('admin/post/store') }}" method='POST' enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="name" class="fw-550">Tiêu đề</label>
                            <input class="form-control" type="text" name="post_title" id="name"
                                value="{{ Old('post_title') }}">
                            @error('post_title')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>


                        <div class="form-group">
                            <label for="slug" class="fw-550">Slug (Friendly Url)</label>
                            <input class="form-control" type="text" name="slug" id="slug"
                                value="{{ Old('slug') }}">
                            @error('slug')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="post-desc" class="fw-550">Mô tả</label>
                            <textarea class="form-control" name="post_desc" id="post-desc">{{ Old('post_desc') }}</textarea>
                            @error('post_desc')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="post-content" class="fw-550">Nội dung bài viết</label>
                            <textarea class="form-control" name="post_content" id="post-content">{{ Old('post_content') }}</textarea>
                            @error('post_content')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for='post-thumb' class="fw-550">Hình ảnh</label> <BR>
                            <div id="uploadFile">
                                <input type="file" name="post_thumb" id="post-thumb" class="form-control-file upload_file"
                                    onchange="upload_image(this)">
                                <img src={{ url('public/uploads/img-product1.png') }} id="image_upload_file" class="mt-3">
                            </div>

                            @error('post_thumb')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        {{-- Danh mục bài viết --}}
                        <div class="form-group">
                            <label for="post_cat" class="fw-550">Danh mục</label>

                            @if (!empty($list_post_cat))
                                <select name="post_cat" id="post_cat" class="form-control">
                                    @php
                                        $sel = '';
                                    @endphp
                                    <option value="">-- Chọn danh mục --</option>
                                    @foreach ($list_post_cat as $post_cat)
                                        @php
                                            if ($post_cat->id == Old('post_cat')) {
                                                $sel = "selected='selected'";
                                            }
                                        @endphp
                                        <option value="{{ $post_cat->id }}" {{ $sel }}>
                                            {{ str_repeat('-', $post_cat->level) . ' ' . $post_cat->post_cat_title }}
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

                        <input type="submit" name="btn_add" class="btn btn-primary" value="Thêm mới">
                    </form>
                </div>
            </div>
        </div>
    @endsection

@extends('layouts.admin')

@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                Chỉnh sửa thông tin danh mục bài viết
            </div>
            <div class="card-body">
                <form id="form-upload" action="{{ url("admin/post/cat/update/{$post_cat->id}") }}" method='POST'
                    enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label for="name" class="fw-550">Tiêu đề</label>
                        <input class="form-control" type="text" name="post_cat_title" id="name"
                            value="{{ $post_cat->post_cat_title }}">
                        @error('post_cat_title')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="slug" class="fw-550">Slug (Friendly Url)</label>
                        <input class="form-control" type="text" name="slug" id="slug"
                            value="{{ $post_cat->slug }}">
                        @error('slug')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="level" class="fw-550">Cấp độ danh mục</label>
                        <input type="number" name="level" id="level" style="background: #FFF; cursor: default;"
                            class="form-control" min="0" max="100" value="{{ $post_cat->level }}">
                        @error('level')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Danh mục bài viết cha --}}
                    <div class="form-group">
                        <label for="cat_parent" class="fw-550">Danh mục cha</label>
                        @if (!empty($list_post_cat))
                            <select name="cat_parent" id="cat_parent" class="form-control">
                                <option value="">-- Chọn danh mục --</option>
                                <option value="-1" style="font-style: italic;">Không có danh mục cha</option>
                                @foreach ($list_post_cat as $post_cat_parent)
                                    @php
                                        $sel = '';
                                    @endphp
                                    @if ($post_cat->cat_parent_id == $post_cat_parent->id)
                                        @php
                                            $sel = "selected = 'seleceted'";
                                        @endphp
                                    @endif
                                    <option value="{{ $post_cat_parent->id }}" {{ $sel }}>
                                        {{ str_repeat('-', $post_cat_parent->level) . ' ' . $post_cat_parent->post_cat_title }}
                                    </option>   
                                @endforeach
                            </select>
                        @else
                            <p class="empty-task">Không tồn tại danh mục nào</p>
                        @endif
                        @error('cat_parent')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="status" class="fw-550">Trạng thái</label>

                        @if (!empty($post_cat->post_cat_status))
                            {!! template_update_status($post_cat->post_cat_status) !!}
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

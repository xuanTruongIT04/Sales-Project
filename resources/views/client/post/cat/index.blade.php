@extends('layouts.client')

@section('content')
    <div id="main-content-wp" class="clearfix blog-page">

        <div class="wp-inner">
            <div class="secion" id="breadcrumb-wp">
                <div class="secion-detail">
                    <ul class="list-item clearfix">
                        <li>
                            <a href="trang-chu" title="Trang chủ">Trang chủ</a>
                        </li>
                        <li>
                            <a class="children-bread-crumb">Blog</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="main-content fl-right">
                <div class="section" id="list-blog-wp">
                    <div class="section-head clearfix">
                        <h3 class="section-title">Blog</h3>
                    </div>
                    @if (!empty($list_post_cat) && count($list_post_cat) > 0)
                        <div class="section-detail">
                            <ul class="list-item">
                                @foreach ($list_post_cat as $post_cat)
                                    <li class="clearfix">
                                        <a href="{!! set_slug(get_slug($post_cat->id)) !!}" title=""
                                            class="thumb fl-left">
                                            <img style="height: 175px;"
                                                src="{!!  get_image_fk($post_cat->image_id) !!}" alt="">
                                        </a>
                                        <div class="info fl-right">
                                            <p class="post-cat-title">{!! $post_cat->post_cat_title !!}</p>
                                            <a href="{!! set_slug(get_slug($post_cat->id)) !!}" title=""
                                                class="title">{!! $post_cat->post_title !!}</a>
                                            <span class="create-date">{!! date('d/m/Y', strtotime($post_cat->created_at)) !!}</span>
                                            <span style="margin-top: 4px;" class="desc"
                                                id="desc_list_post">
                                                    @if (strlen($post_cat->post_desc) > 250)
                                                        {!! get_words($post_cat->post_desc, 30) !!}
                                                    @else
                                                        {!! $post_cat->post_desc !!}
                                                    @endif
                                                </span>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @else
                        {!! "<p style='font-size:20px; color: #F00; font-family: italic;'>Không có bài viết nào !!!</p>" !!};
                    @endif


                </div>
                <div class="section" id="paging-wp">
                    <div class="section-detail clearfix">
                        @php
                            global $page, $num_page;
                            echo get_paging($page, $num_page, 'bai-viet');
                        @endphp
                    </div>
                </div>
            </div>
            @include('layouts.sidebar-page')
        </div>
    </div>
@endsection

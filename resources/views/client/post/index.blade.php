@extends('layouts.client')

@section('content')
    <div id="main-content-wp" class="clearfix detail-blog-page">
        <div class="wp-inner">
            <div class="secion" id="breadcrumb-wp">
                <div class="secion-detail">
                    <ul class="list-item clearfix">
                        <li>
                            <a href="trang-chu" title="Trang chủ">Trang chủ</a>
                        </li>
                        <li>
                            <a href="bai-viet" class="" title="Blog">Blog</a>
                        </li>
                        <li>
                            <a class="children-bread-crumb">Chi tiết blog</a>
                        </li>
                    </ul>
                </div>
            </div>
            @if (!empty($info_post))
                <div class="main-content fl-right">
                    <div class="section" id="detail-blog-wp" style="padding: 4px;">
                        <div class="section-head clearfix">
                            <h3 class="section-title">{!! $info_post->post_title !!}</h3>
                        </div>
                        <div class="section-detail">
                            <span class="create-date">{!! date('d/m/Y', strtotime($info_post->created_at)) !!}</span>
                            <div class="detail">
                                {!! $info_post->post_content !!}
                            </div>
                        </div>
                    </div>
                    <div class="section" id="social-wp">
                        <div class="section-detail">
                            <div class="fb-like" data-href="" data-layout="button_count" data-action="like"
                                data-size="small" data-show-faces="true" data-share="true"></div>
                            <div class="g-plusone-wp">
                                <div class="g-plusone" data-size="medium"></div>
                            </div>
                            <div class="fb-comments" id="fb-comment" data-href="" data-numposts="5"></div>
                        </div>
                    </div>
                </div>
            @endif

            @include('layouts.sidebar-page')
        </div>
    </div>
@endsection

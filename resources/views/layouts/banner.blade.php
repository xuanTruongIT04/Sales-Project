<div class="section" id="banner-wp">
    @php
        $list_banner = get_list_banner();
    @endphp
    @if (!empty($list_banner))
        <div class="section-detail">
            @foreach ($list_banner as $banner)
                <a href="#" title="{!! $banner->banner_name !!}" class="thumb">
                    <img src="{!! get_image_fk($banner -> image_id) !!}"
                        alt="Đây là {!! $banner -> banner_name !!}">
                </a>
            @endforeach

        </div>
    @endif

</div>

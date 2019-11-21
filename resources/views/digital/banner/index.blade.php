<!-- Swiper -->
@if (count($banner) > 0)
    @if (count($banner) > 1)
        <div id="banner" class="swiper-container">
            <div class="swiper-wrapper">
                @foreach ($banner as $item)
                    <div class="swiper-slide">
                        <a href="/digital/banner/{{urlencode("$item->slug")}}">
                            <img src="{{asset("$item->image_path")}}" class="banner-img">
                        </a>
                    </div>
                @endforeach
            </div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
            <!-- Add Pagination -->
            {{-- <div class="swiper-pagination"></div> --}}
            <!-- Add Navigation -->
        </div>
    @else
        <div class="swiper-container">
            <div class="w-100">
                <a href="/digital/banner/{{urlencode($banner[0]->slug)}}">
                    <img src="{{asset($banner[0]->image_path)}}" class="banner-img">
                </a>
            </div>
        </div>
    @endif
@endif
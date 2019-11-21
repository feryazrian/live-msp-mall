@extends('layouts.app')

@section('title'){{ $home_title }}@endsection

@section('description'){{ $home_description }}@endsection

@section('content')

<section class="bg-brand pt-1 slider">
    <div class="container">

        <div class="d-none d-xl-table-cell align-top category-list">
        @foreach ($categories->where('id', '!=', 12) as $item)
            <a href="{{ route('category.detail', ['slug' => $item->slug]) }}">
                <img src="{{ asset('uploads/categories/'.$item->icon) }}">
                {{ $item->name }}
            </a>
        @endforeach

        @foreach ($categories as $item)
        @if ($item->id == 12)
            <a href="{{ route('category.detail', ['slug' => $item->slug]) }}" style="margin-top:5px;">
                <img src="{{ asset('uploads/categories/'.$item->icon) }}">
                {{ $item->name }}
            </a>
        @endif
        @endforeach
        </div>

        <div class="pl-c d-sm-block d-xl-table-cell slide">
            <div class="swiper-container front-slide">
                <div class="swiper-wrapper">
                @foreach ($slides as $key => $item)
                    <a href="{{ $item->url }}" class="swiper-slide">
                        <img src="{{ asset('uploads/slides/'.$item->photo) }}" alt="{{ $item->name }}" width="100%">
                    </a>
                @endforeach
                </div>
                 <!-- Add Pagination -->
                <div class="swiper-pagination"></div>
                <!-- Add Arrows -->
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        </div>

        <div class="d-xl-none d-md-block align-top category-icon-list">
            @foreach ($categories as $item)
                <a href="{{ route('category.detail', ['slug' => $item->slug]) }}">
                    <img src="{{ asset('uploads/categories/'.$item->icon) }}">
                    {{ $item->name }}
                </a>
            @endforeach
        </div>

    </div>
</section>
{{-- @if (Auth::check()) --}}
    {{--  @if (Auth::user()->username === 'daniel_nicklaus')  --}}
        <section class="bg-grey-light product-trend pt-4">
            <div class="container">
                @include('digital.ppob.index')
            </div>
        </section>
    {{--  @endif  --}}
{{-- @endif --}}
@if (!empty($countdown_flashsale))
@if ($countdown_flashsale > $now)
<section class="bg-brand flash-sale" @if(!empty($bg_flashsale)) style="background: url('{{ asset('uploads/options/'.$bg_flashsale) }}');" @endif>
    <div class="container">
        
        <div class="section-arrow">
            <button data-section="section-flashsale" class="btn leftArrow" type="button">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button data-section="section-flashsale" class="btn rightArrow" type="button">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>

        <div class="scroll" id="section-flashsale">
            <div class="main py-4">

                <div class="product-list">
                    <div class="head">
                        <div class="title">FLASH SALE</div>
                        <div class="countdown">
                            <div class="caption">Berakhir Dalam</div>
                            <div class="counter flashsale-countdown"></div>
                        </div>
                        <a href="{{ route('search', ['sort' => 'sale']) }}" class="link">LIHAT SEMUANYA ></a>
                    </div>
                
                @foreach ($productSale as $item)
                    @include('layouts.card-product')
                @endforeach

                </div>

            </div>
        </div>
        
    </div>
</section>
@endif
@endif

@if ($seasons->isNotEmpty())
@foreach ($seasons as $season)
<section class="bg-brand flash-sale" @if(!empty($season->background)) style="background: url('{{ asset('uploads/seasons/'.$season->background) }}');" @endif>
    <div class="container">
        
        <div class="section-arrow">
            <button data-section="section-season{{ $season->id }}" class="btn leftArrow" type="button">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button data-section="section-season{{ $season->id }}" class="btn rightArrow" type="button">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>

        <div class="scroll" id="section-season{{ $season->id }}">
            <div class="main py-4">

                <div class="product-list">
                    <div class="head">
                        <div class="title">{{ $season->name }}</div>
                        <div class="countdown">
                            <div class="caption">Berakhir Dalam</div>
                            <div class="counter season-countdown{{ $season->id }}"></div>
                        </div>
                        <a href="{{ route('season', ['slug' => $season->slug]) }}" class="link">LIHAT SEMUANYA ></a>
                    </div>
                
                    @foreach ($season->seasonproduct as $seasonproduct)
                    @php
                        $item = $seasonproduct->product;
                    @endphp
                        @include('layouts.card-product')
                    @endforeach
    
                    <script type="text/javascript">
                        $(function () {
                            var {{ 'ca'.$season->id }} = '{!! substr($season->expired,0,4) !!}';
                            var {{ 'cb'.$season->id }} = '{!! substr($season->expired,5,2) !!}';
                            var {{ 'cc'.$season->id }} = '{!! substr($season->expired,8,2) !!}';
                            var {{ 'cd'.$season->id }} = '{!! substr($season->expired,11,2) !!}';
                            var {{ 'ce'.$season->id }} = '{!! substr($season->expired,14,2) !!}';
                            var {{ 'cf'.$season->id }} = '{!! substr($season->expired,17,2) !!}';
                
                            var {{ 'countdown'.$season->id }} = new Date({{ 'ca'.$season->id }}, {{ 'cb'.$season->id }} - 1, {{ 'cc'.$season->id }}, {{ 'cd'.$season->id }}, {{ 'ce'.$season->id }}, {{ 'cf'.$season->id }}, 0);
                            
                            $('.season-countdown{{ $season->id }}').countdown({until: {{ 'countdown'.$season->id }}, compact: true, format: 'HMS'});
                        });
                    </script>
                </div>

            </div>
        </div>
        
    </div>
</section>
@endforeach
@endif

@if ($productPreorder->count() > 0)
<section class="bg-grey-light product-trend" @if(!empty($bg_groupbuy)) style="background: url('{{ asset('uploads/options/'.$bg_groupbuy) }}');" @endif>
    <div class="container">

        <div class="d-table section-head w-100 py-3">
            <div class="d-table-cell">GROUP BUY</div>
            <div class="d-table-cell text-right">
                <a href="{{ route('search', ['sort' => 'preorder']) }}">LIHAT SEMUANYA ></a>
            </div>
        </div>
        
        <div class="section-arrow">
            <button data-section="section-groupbuy" class="btn leftArrow" type="button">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button data-section="section-groupbuy" class="btn rightArrow" type="button">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>

        <div class="scroll" id="section-groupbuy">
            <div class="main">
                <div class="product-list">

                @foreach ($productPreorder as $item)
                    @include('layouts.card-product')
                @endforeach

                </div>
            </div>
        </div>

    </div>
</section>
@endif

<section class="bg-grey-light product-trend" @if(!empty($bg_bestseller)) style="background: url('{{ asset('uploads/options/'.$bg_bestseller) }}');" @endif>
    <div class="container">

        <div class="d-table section-head w-100 py-3">
            <div class="d-table-cell">PALING LARIS</div>
            <div class="d-table-cell text-right">
                <a href="{{ route('search', ['sort' => 'bestseller']) }}">LIHAT SEMUANYA ></a>
            </div>
        </div>
        
        <div class="section-arrow">
            <button data-section="section-popular" class="btn leftArrow" type="button">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button data-section="section-popular" class="btn rightArrow" type="button">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>

        <div class="scroll" id="section-popular">
            <div class="main">
                <div class="product-list">

                @foreach ($productSold as $item)
                    @include('layouts.card-product')
                @endforeach

                </div>
            </div>
        </div>

    </div>
</section>

<section class="bg-grey-light product-trend pb-30" @if(!empty($bg_newest)) style="background: url('{{ asset('uploads/options/'.$bg_newest) }}');" @endif>
    <div class="container">

        <div class="d-table section-head w-100 py-3">
            <div class="d-table-cell">PALING BARU</div>
            <div class="d-table-cell text-right">
                <a href="{{ route('search', ['sort' => 'new']) }}">LIHAT SEMUANYA ></a>
            </div>
        </div>
        
        <div class="section-arrow">
            <button data-section="section-newest" class="btn leftArrow" type="button">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button data-section="section-newest" class="btn rightArrow" type="button">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>

        <div class="scroll" id="section-newest">
            <div class="main">
                <div class="product-list">
                    
                @foreach ($productNew as $item)
                    @include('layouts.card-product')
                @endforeach

                </div>
            </div>
        </div>

    </div>
</section>

@foreach ($categoryHighlight as $highlight)
<section class="product-category pb-30" @if(!empty($highlight->background)) style="background: url('{{ asset('uploads/categories/'.$highlight->background) }}');" @endif>
    <div class="container">

        <div class="d-table section-head w-100 py-3">
            <div class="d-table-cell">{{ $highlight->name }}</div>
            <div class="d-table-cell text-right">
                <a href="{{ route('category.detail', ['slug' => $highlight->slug]) }}">LIHAT SEMUANYA ></a>
            </div>
        </div>
        
        <div class="section-arrow">
            <button data-section="section-category{{ $highlight->id }}" class="btn leftArrow" type="button">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button data-section="section-category{{ $highlight->id }}" class="btn rightArrow" type="button">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>

        <div class="scroll" id="section-category{{ $highlight->id }}">
            <div class="main">
                <div class="product-list row">

                    <a href="{{ route('category.detail', ['slug' => $highlight->slug]) }}" class="category-image">
                        <img src="{{ asset('uploads/categories/'.$highlight->cover) }}">
                    </a>

                    @foreach ($highlight->product_highlight as $item)
                        @include('layouts.card-product')
                    @endforeach

                </div>
            </div>
        </div>

    </div>
</section>
@endforeach

<script>
    var swiper = new Swiper('.swiper-container', {
        spaceBetween: 10,
        centeredSlides: true,
        autoplay: {
        delay: 2500,
        disableOnInteraction: false,
        },
        pagination: {
        el: '.swiper-pagination',
        clickable: true,
        },
        navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
        },
    });
</script>
<script>
    var move = 100;

    $(".rightArrow").click(function() {
        var view = $('#'+$(this).attr('data-section'));
        view.animate({ scrollLeft: view.scrollLeft() + move }, 300);
    });

    $(".leftArrow").click(function() {
        var view = $('#'+$(this).attr('data-section'));
        view.animate({ scrollLeft: view.scrollLeft() - move }, 300);
    });
</script>
@endsection

@section('scripts')
   <!-- Currency JS -->
   <script src="https://unpkg.com/currency.js@1.2.1/dist/currency.min.js"></script>

   <!-- Link all Digital Scripts -->
   <script src="{{ asset('assets/js/digital/index.js') }}"></script>
   <script src="{{ asset('assets/js/digital/pulsa.js') }}"></script>
   <script src="{{ asset('assets/js/digital/data.js') }}"></script>
@endsection

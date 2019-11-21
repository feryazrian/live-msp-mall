@extends('layouts.app')

@section('title'){{ str_replace('[TITLE]', $pageTitle, $seo_title) }}@endsection

@section('description'){{ str_replace('[TITLE]', $pageTitle, $seo_description) }}@endsection

@section('css')
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css">
<link rel="stylesheet" href="{{ asset('css/search.css') }}">
@endsection

@section('content')

<section class="search-product pb-5 bg-white">
    <div class="container">

        @if ($categoryDetail && !empty($categoryDetail->background))
        <div class="d-flex">
            <div class="section-name bg-light mt-2 rounded shadow"
                style="background:url('{{asset('/uploads/categories/'.$categoryDetail->background) }}')">
                <div class="container">
                    <div class="section-heading">
                        <h5 class="title-section">{{ $categoryDetail->name }}</h5>
                        <input type="hidden" name="category-slug" value="{{ $categoryDetail->slug }}"
                            id="category-slug">
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="d-table search-head w-100 py-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-white mb-0">
                    @if ($categoryDetail)
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    @if ($categoryDetail->parent)
                    <li class="breadcrumb-item"><a
                            href="{{ route('category.detail', $categoryDetail->parent->id) }}">{{ $categoryDetail->parent->name }}</a>
                    </li>
                    @endif
                    <li class="breadcrumb-item active text-dark" aria-current="page">{{ $categoryDetail->name }}</li>
                    @else
                    <li class="breadcrumb-item active text-dark" aria-current="page">Hasil Pencarian
                        {{ $search_keyword }}
                    </li>
                    @endif
                </ol>
            </nav>

            <div class="input-search d-none mx-2">
                <div class="input-group">
                    <input type="text" class="form-control px-4 search-keyword mobile" value="{{ $search_keyword }}"
                        placeholder="Cari Produk atau toko ...">
                    <div class="input-group-append px-2">
                        <button type="button" class="btn d-print-inline-block search-button">
                            <span class="icon-search"></span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="input-select d-table-cell align-top text-right mb-2">
                <button class="btn btn-filter d-none btn-dark">
                    <span>Filter Produk</span>
                    <span class="icon-filter"></span>
                </button>
                <select
                    class="form-control btn-sorting select select-secondary custom-select select-block text-left m-0 search-sort">
                    <option value="new" @if($search_sort=='new' ) selected @endif>Produk Terbaru</option>
                    <option value="bestseller" @if($search_sort=='bestseller' ) selected @endif>Produk Terlaris</option>
                    <option value="expensive" @if($search_sort=='expensive' ) selected @endif>Harga Tertinggi</option>
                    <option value="cheap" @if($search_sort=='cheap' ) selected @endif>Harga Terendah</option>
                    <option value="sale" @if($search_sort=='sale' ) selected @endif>Flash Sale</option>
                    <option value="preorder" @if($search_sort=='preorder' ) selected @endif>Group Buy</option>
                </select>
                <span class="sorting">
                    <span class="icon-sorting"></span>
                </span>
            </div>
        </div>

        <div class="d-table search-main w-100">

            <div class="d-table-cell form align-top">
                <form method="post" class="shadow form-filter rounded">

                    <div class="title text-dark">
                        <b>Filter Produk</b>
                        <button type="button" class="btn-filter-close">x</button>
                    </div>

                    <div class="form-group border-0">
                        <select class="form-control select-block text-left m-0 mb-2 search-condition">
                            <option value="1" @if($search_condition=='1' ) selected @endif>Produk Baru</option>
                            <option value="2" @if($search_condition=='2' ) selected @endif>Produk Bekas</option>
                        </select>

                        <select class="form-control select-block text-left m-0 mb-2 search-category">
                            <option value="0">Kategori</option>

                            @foreach ($categories as $item)
                            <option value="{{ $item->slug }}" @if (!empty($category)) @if ($item->slug ==
                                $category->slug)
                                selected @endif @endif @if (!empty($search_category)) @if ($item->slug ==
                                $search_category)
                                selected @endif @endif>{{ $item->name }}</option>

                            @foreach ($item->child as $item)
                            <option value="{{ $item->slug }}" @if (!empty($category)) @if ($item->slug ==
                                $category->slug)
                                selected @endif @endif @if (!empty($search_category)) @if ($item->slug ==
                                $search_category)
                                selected @endif @endif>-- {{ $item->name }}</option>
                            @endforeach
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group border-0 py-0">
                        <article class="card-group-item">
                            <div class="title p-0 rounded">
                                <a href="#" class="d-flex flex-row justify-content-between align-items-center text-dark"
                                    data-toggle="collapse" data-target="#collapsePriceRange">
                                    <span class="title text-dark">Rentang Harga </span>
                                    <i class="title text-dark icon-action fa fa-chevron-down"></i>
                                </a>
                            </div>
                            <div class="filter-content collapse show" id="collapsePriceRange">
                                <div class="card-body px-0 pb-1">
                                    {{-- <input type="range" class="custom-range" min="1" max="100" name=""> --}}
                                    <div id="slider-range" class="price-filter-range" name="rangeInput"></div>
                                    <div class="d-flex flex-row justify-content-center align-items-center">
                                        <div class="">
                                            <label>Min</label>
                                            <input class="form-control numeric search-min" value="{{ $search_min }}"
                                                placeholder="{{ $min_value }}" min="1" type="text"
                                                oninput="validity.valid||(value=0);" id="min_price">
                                        </div>
                                        <div class="">
                                            <label>Max</label>
                                            <input class="form-control numeric search-max" value="{{ $search_max }}"
                                                placeholder="{{ $max_value }}" min="1" type="text"
                                                oninput="validity.valid||(value=0);" id="max_price">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </article>
                    </div>

                    <div class="form-group border-0">
                        <select class="form-control text-left m-0 search-location">
                            <option value="0">Lokasi</option>
                        </select>
                    </div>

                    <div class="form-group border-0">
                        <button type="button" class="btn btn-primary btn-block search-button pt-1">Tampilkan</button>
                    </div>

                </form>
            </div>

            <div class="col-sm-12 col-md-12">
                <div class="text-center skeleton-container">
                    <div class="stv-radio-tabs-wrapper w-75">
                        @for ($i = 0; $i < 2; $i++)
                        <section class="skeleton-card">
                            <div class="skeleton-card-detail p-0 pt-3">
                                <p class="skeleton-text loading w-50 mx-auto"></p>
                            </div>
                        </section>
                        @endfor
                        <div class="col-md-6"></div>
                    </div>
                    <div class="skeleton-grid">
                        @for ($i = 0; $i < count($products); $i++)
                            <section class="skeleton-card">
                                <figure class="skeleton-card-image loading"></figure>
                                <div class="skeleton-card-detail">
                                    <p class="skeleton-text loading"></p>
                                    <p class="skeleton-text loading w-50 mx-auto"></p>
                                    <p class="skeleton-text loading w-75 mx-auto"></p>
                                    <p class="skeleton-text loading w-50 mx-auto"></p>
                                </div>
                            </section>
                        @endfor
                    </div>
                </div>

                <div class="stv-radio-tabs-wrapper">
                    <div class="container-stv-radio-tab">
                        <input type="radio" class="stv-radio-tab" name="ct" value="product" id="ct-product" />
                        <label for="ct-product" class="stv-label-tab text-center"><i class="fa fa-box-open"></i> Produk</label>
                    </div>
                    <div class="container-stv-radio-tab">
                        <input type="radio" class="stv-radio-tab" name="ct" value="shop" id="ct-shop" />
                        <label for="ct-shop" class="stv-label-tab text-center"><i class="fa fa-store"></i> Toko</label>
                    </div>
                </div>

                <div id="ctn-product">
                    <div class="d-table-cell align-top product-list">
                        @if (count($products) === 0)
                        <div class="jumbotron p-0 bg-white text-center">
                            <img src="{{ asset('images/page-not-found.png') }}" alt="No Product Found" width="100%" height="200%">
                            <p class="text-secondary"><b>Oops!! Maaf, Produk yang dicari tidak ditemukan</b></p>
                        </div>
                        @endif

                        @if (!empty($products))
                        {{ csrf_field() }}

                        @foreach ($products as $item)
                        @include('layouts.card-product')
                        @endforeach

                        @if ($show_more)
                        <button type="button" class="btn btn-outline-light form-control text-dark p-0" id="loadMoreProduct">
                            <b>Muat lebih banyak produk</b><br><i class="fas fa-angle-double-down text-primary"></i></i>
                        </button>
                        @endif
                        @endif
                    </div>
                </div>

                <div id="ctn-shop">
                    <div class="d-table-cell align-top product-list">
                        @if (count($users) === 0)
                        <div class="jumbotron p-0 bg-white text-center">
                            <img src="{{ asset('images/page-not-found.png') }}" alt="No Product Found" width="100%" height="200%">
                            <p class="text-secondary"><b>Oops!! Maaf, Toko yang dicari tidak ditemukan</b></p>
                        </div>
                        @endif

                        @if (!empty($users))
                        {{ csrf_field() }}
                        @foreach ($users as $item)
                        @include('layouts.card-user')
                        @endforeach

                        @if (count($users) > 15)
                        <button type="button" class="btn btn-outline-light form-control text-dark p-0" id="loadMoreShop">
                            <b>Muat lebih banyak toko</b><br><i class="fas fa-angle-double-down text-primary"></i></i>
                        </button>
                        @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js"></script>
<script>
    // global var
    var current_product_page = 1;
    var current_shop_page = 1;
    var productCanLoad = true;
    var shopCanLoad = true;
    var sLoc = {!! json_encode($search_location) !!};

    $(document).ready(function(){
        // Load Locations
        load_kabupaten();
        $('.search-condition').selectize();
        $('.search-category').selectize();

        // Content Tab Active
        setTimeout(function() {
            $('#ct-product').trigger('click');
            var cSearch = window.location.search;
            if (!cSearch) {
                $('#ct-shop').parent().hide();
            }
            $('.skeleton-container').hide();
        },3000);

        $('button.btn-filter').on('click', function (e) {
            $('.form-filter').show();
        });
        $('button.btn-filter-close').on('click', function (e) {
            $('.form-filter').hide();
        });

        $("input[name='ct']").click(function(){
            switchContent(this.value)
        });

        $('#price-range-submit').hide();

        $("#min_price,#max_price").on('change', function () {
            $('#price-range-submit').show();

            var min_price_range = parseInt($("#min_price").val());
            var max_price_range = parseInt($("#max_price").val());

            if (min_price_range > max_price_range) {
                $('#max_price').val(min_price_range);
            }

            $("#slider-range").slider({
                values: [min_price_range, max_price_range]
            });
        });

        $("#min_price,#max_price").on("paste keyup", function () {
            $('#price-range-submit').show();

            var min_price_range = parseInt($("#min_price").val());
            var max_price_range = parseInt($("#max_price").val());

            if(min_price_range == max_price_range){
                max_price_range = min_price_range + 100;
                $("#min_price").val(min_price_range);		
                $("#max_price").val(max_price_range);
            }

            $("#slider-range").slider({
                values: [min_price_range, max_price_range]
            });
        });

        $(function () {
            var minVal = {!! !empty($min_value) ? $min_value : 0 !!};
            var maxVal = {!! !empty($max_value) ? $max_value : 999999999 !!};
            var minSearch = {!! !empty($search_min) ? $search_min : 0 !!};
            var maxSearch = {!! !empty($search_max) ? $search_max : 999999999 !!};
            $("#slider-range").slider({
                range: true,
                orientation: "horizontal",
                min: minVal,
                max: maxVal,
                values: [minSearch, maxSearch],
                step: 1000,

                slide: function (event, ui) {
                if (ui.values[0] == ui.values[1]) {
                    return false;
                }

                $("#min_price").val(ui.values[0]);
                $("#max_price").val(ui.values[1]);
                }
            });

            $("#min_price").val($("#slider-range").slider("values", 0));
            $("#max_price").val($("#slider-range").slider("values", 1));
        });

        $("#slider-range,#price-range-submit").click(function () {
            var min_price = $('#min_price').val();
            var max_price = $('#max_price').val();

            $("#searchResults").text("Here List of products will be shown which are cost between " + min_price  +" "+ "and" + " "+ max_price + ".");
        });

        // Load More Product
        var _token = $('input[name="_token"]').val();

        $(document).on('click', '#loadMoreProduct', function(){
            load_data(_token);
        });

        // Infinite load Produk
        $(window).scroll(function() {
            if($(window).scrollTop() + $(window).height() >= $(document).height()) {
                if (productCanLoad || shopCanLoad) {
                    load_data(_token)
                }
            }
        });
    });

    function load_data(_token)
    {
        var ct = $('input[name=ct]:checked').val();
        var params = window.location.search.slice(window.location.search.indexOf('?')+1);
        var origin = window.location.origin;
        var path = (ct == 'shop') ? '/load-more/shop?' : '/load-more/product?';
        var categorySlug = window.location.pathname.slice(window.location.pathname.indexOf('/')+1).replace('/', '=');
        var current_page = 1;
        var loadMoreBtn = $('#loadMoreProduct');
        var appendProductList = $('#ctn-product .product-list');
        var loadMoreTxt = 'Muat lebih banyak produk';
        switch (ct) {
            case 'shop':
                current_shop_page += 1;
                current_page = current_shop_page;
                loadMoreBtn = $('#loadMoreShop');
                appendProductList = $('#ctn-shop .product-list');
                var loadMoreTxt = 'Muat lebih banyak toko';
                break;
            default:
                current_product_page += 1;
                current_page = current_product_page;
                break;
        }
        // current_page += 1;
        var url = origin + path + params + '&' + categorySlug + '&ct=' + ct + '&page=' +current_page;
        $.ajax({
            url: url,
            method:"GET",
            beforeSend: function()
            {
                loadMoreBtn.html('<img src="/assets/digital/loading-ripple.svg" alt="" class="prefix-img"><b> Memuat data</b>');
            },
            success: function(result)
            {
                loadMoreBtn.remove();
                var canLoad = false;
                if (current_page <= result.items.last_page) {
                    canLoad = true;
                    appendProductList.append(result.html);
                    appendProductList.append('<button type="button" class="btn btn-outline-light form-control text-dark p-0" id="'+ loadMoreBtn[0].getAttribute('id') +'"><b>'+ loadMoreTxt +'</b><br><i class="fas fa-angle-double-down text-primary"></i></i></button>');
                } else {
                    canLoad = false;
                    loadMoreBtn.html('<b>No More Data</b>');
                }
                switch (ct) {
                    case 'shop':
                        shopCanLoad = canLoad;
                        break;
                    default:
                        productCanLoad = canLoad;
                        break;
                }
            },
            error: function(err)
            {
                productCanLoad = false;
                shopCanLoad = false
                loadMoreBtn.html('<b>Opps! Something when wrong. Please Reload Page</b>');
            }
        })
    }

    // Read a page's GET URL variables and return them as an associative array.
    function getUrlVars()
    {
        var vars = [], hash;
        var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
        for(var i = 0; i < hashes.length; i++)
        {
            hash = hashes[i].split('=');
            vars.push(hash[0]);
            vars[hash[0]] = hash[1];
        }
        return vars;
    }

    // Content Tab Active
    function switchContent(tab) {
        var ctnProduct = $('#ctn-product');
        var ctnShop = $('#ctn-shop');

        switch (tab) {
            case 'shop':
                ctnProduct.hide();
                ctnShop.show();
                break;
            default:
                ctnShop.hide();
                ctnProduct.show();
                break;
        }
    }

    // Load Kabupaten
    function load_kabupaten() {
        $.ajax({
            url: window.location.origin +'/kabupaten',
            method: "GET",
            success: function(res)
            {
                if (res && res.length > 0) {
                    res.forEach(item => {
                        var selected = sLoc == item['id'] ? true : false;
                        var o = new Option(item['name'], item['id'], selected);
                        // jquerify the DOM object 'o' so we can use the html method
                        $(".search-location").append(o);
                    });
                    $(".search-location").selectize();
                }
            }
        })
    }
</script>
@endsection
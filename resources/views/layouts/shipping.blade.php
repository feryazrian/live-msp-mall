<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <meta name="robots" content="noindex">
    <meta name="googlebot" content="noindex">
    
    <title>@yield('title')</title>

    <meta name="title" content="@yield('title')"/>
    <meta name="description" content="@yield('description')"/>
    <meta name="keywords" content="{{ $seo_keywords }}">
    <meta name="copyright" content="{{ $seo_copyright }}">

    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="@yield('title')">
    <meta itemprop="description" content="@yield('description')">

    <!-- Twitter Card data -->
    <meta name="twitter:title" content="@yield('title')">
    <meta name="twitter:description" content="@yield('description')">

    <!-- Open Graph data -->
    <meta property="fb:app_id" content="1907526829361575" />
    <meta property="og:type" content="website" /> 
    <meta property="og:title" content="@yield('title')"/>
    <meta property="og:description" content="@yield('description')"/>
    <meta property="og:site_name" content="{{ config('app.name') }}"/>
    <meta property="og:url" content="{{ url()->current() }}"/>
    
    @if (!empty($seo_image))
    <meta itemprop="image" content="{{ $seo_image }}" />
    <meta name="twitter:image" content="{{ $seo_image }}" />
    <meta property="og:image" content="{{ $seo_image }}" />
    <link rel="image_src" type="image/png" href="{{ $seo_image }}" />
    @else
    <meta itemprop="image" content="{{ asset('uploads/options/'.$logo) }}" />
    <meta name="twitter:image" content="{{ asset('uploads/options/'.$logo) }}" />
    <meta property="og:image" content="{{ asset('uploads/options/'.$logo) }}" />
    <link rel="image_src" type="image/png" href="{{ asset('uploads/options/'.$logo) }}" />
    @endif

    <link rel="icon" type="image/png" href="{{ asset('favicon.ico') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    {!! $meta_header !!}

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- CSS -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">

    <link href="https://fonts.googleapis.com/css?family=Oswald:300,400,500,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Varela+Round" rel="stylesheet">

    <!-- Loading Bootstrap -->
    <link href="{{ asset('css/vendor/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Loading Flat UI Pro -->
    <link href="{{ asset('css/flat-ui-pro.css') }}" rel="stylesheet">

    <!-- Font -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="{{ asset('scripts/jquery.fortune.min.js') }}"></script>

    <!-- Swiper -->
    <link href="{{ asset('css/swiper.css') }}" rel="stylesheet">
    <script src="{{ asset('scripts/swiper.min.js') }}"></script>
</head>
<body>
    <header class="bg-brand text-white">
        <div id="sidenav" class="sidenav">
            <div class="head d-table w-100">
                <div class="d-table-cell text-left">
                    <div style="width:50px;"></div>
                </div>
                <div class="d-table-cell">
                    <a href="{{ route('shipping.home') }}" class="btn">
                        <img src="{{ asset('uploads/options/'.$logo) }}" height="35px">
                    </a>
                </div>
                <div class="d-table-cell text-right">
                    <a href="javascript:void(0)" onclick="closeNav()" class="btn btn-close">
                        <span class="icon-close"></span>
                    </a>
                </div>
            </div>
            <div class="navlist">
                <a href="{{ route('shipping.home') }}">
                    <span class="icon-home-white"></span> Home
                </a>
                <a href="{{ route('shipping.pricing') }}">
                    <span class="icon-order-white"></span> Ongkos Kirim
                </a>
                <a href="{{ route('shipping.tracking') }}">
                    <span class="icon-ads-white"></span> Lacak Kiriman
                </a>
                <a href="{{ route('shipping.page') }}">
                    <span class="icon-help-white"></span> Bantuan
                </a>
            </div>
        </div>

        <div class="container d-table">

            <div class="d-none left-button">
                <button class="btn btn-menu" onclick="openNav()">
                    <span class="icon-menu"></span>
                </button>
            </div>

            <a href="{{ route('shipping.home') }}" class="d-table-cell align-top logo">
                <img src="{{ asset('uploads/options/'.$logo) }}" height="35px">
                <div class="d-none mobile">
                @if (!empty($headTitle))
                    <div class="title">{{ $pageTitle }}</div>
                @else
                    <img src="{{ asset('uploads/options/'.$logo) }}" height="35px">
                @endif
                </div>
            </a>
            
            <div class="d-table-cell menu-shipping align-middle pl-4 pr-3 w-100">
                <div class="d-table w-100">
          
                    <a href="{{ route('shipping.home') }}" class="btn btn-primary btn-daftar">Home</a>
                    <a href="{{ route('shipping.pricing') }}" class="btn btn-primary btn-daftar">Ongkos Kirim</a>
                    <a href="{{ route('shipping.tracking') }}" class="btn btn-primary btn-daftar">Lacak Kiriman</a>
                    <a href="{{ route('shipping.page') }}" class="btn btn-primary btn-daftar">Bantuan</a>

                </div>
            </div>

            <div class="d-table-cell align-top action-guest">

                <a href="{{ route('shipping.page.detail', ['slug' => 'hubungi-kami']) }}" class="btn btn-primary btn-masuk">
                    Hubungi Kami
                </a>

            </div>

        </div>
    </header>
	
    <section class="bg-brand pt-1 slider">
        <div class="container p-0">
    
            <div class="slide">
                <div class="swiper-container">
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
    
        </div>
    </section>
    
	@yield('content')

    @if (empty($hideFooter))
	<footer class="bg-brand">
        <div class="links py-5">
            <div class="container">
                <div class="row">

                @foreach ($footer_shipping as $footer)
                    <div class="col-md-3">
                        <div class="title">{{ $footer->name }}</div>

                    @foreach ($footer->page as $item)
                        <a href="{{ route('shipping.page.detail', ['slug' => $item->slug]) }}">{{ $item->name }}</a>
                    @endforeach
                    </div>
                @endforeach

                    <div class="col-md-3">
                        <div class="title">Hubungi</div>

                        <p>{{ $shipping_address }}</p>
                        <a href="{ 'tel:'.$shipping_phone }}">{{ $shipping_phone }}</a>
                        <a href="{ 'mailto:'.$shipping_email }}">{{ $shipping_email }}</a>
                    </div>

                </div>
            </div>
        </div>
    
        <div class="copyright py-3">
            <div class="container">
            <div class="d-table w-100">

                <a href="{{ route('shipping.home') }}" class="d-table-cell align-middle logo w-50">
                    <img src="{{ asset('uploads/options/'.$logo) }}" height="35px">
                </a>

                <div class="d-table-cell text-right w-50">
                    <a href="{{ $link_facebook }}" class="btn btn-rounded btn-facebook">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="{{ $link_instagram }}" class="btn btn-rounded btn-instagram">
                        <i class="fab fa-instagram"></i>
                    </a>
                </div>
                
            </div>
            </div>
        </div>
    </footer>
    @endif

    <!-- Bootstrap 4 requires Popper.js -->
    <script src="https://unpkg.com/popper.js@1.14.1/dist/umd/popper.min.js" crossorigin="anonymous"></script>
    <!--<script src="https://vjs.zencdn.net/6.6.3/video.js"></script>-->

    <script src="{{ asset('scripts/flat-ui-pro.min.js') }}"></script>

    <script src="{{ asset('scripts/jquery.elevatezoom.js') }}"></script>

    <script src="{{ asset('scripts/jquery.numeric.min.js') }}"></script>

    <script src="{{ asset('scripts/jquery.chained.min.js') }}"></script>

    <script src="{{ asset('scripts/jquery.chained.remote.min.js') }}"></script>

    <script src="{{ asset('scripts/jquery.plugin.min.js') }}"></script>
    <script src="{{ asset('scripts/jquery.countdown.js') }}"></script>

    <script type="text/javascript">
        // Window
        var window_width = $(window).width();

        $(".dropdownx-button").click(function() {
            $(".dropdownx-menu").toggle();
        });
        
        // Slider
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

        // Nav Tabs
        $('.nav-tabs.smart a').on('click', function (e) {
            e.preventDefault();
            $(this).tab('show');
        });
        
        // Input Elements
        $(':checkbox').radiocheck();
    
        $("input.numeric").numeric({ negative: false });

        $(".select-smart").select2({dropdownCssClass: 'dropdown-inverse'});
    
        /* For jquery.chained.js */
        $("#kabupaten").chained("#provinsi");
        $("#kecamatan").chained("#kabupaten");
        $("#desa").chained("#kecamatan");

        /* For jquery.chained.remote.js */
        $("#kabupaten").remoteChained({
            parents : "#provinsi",
            url : "{{ route('json.kabupaten') }}",
            loading : "Kota / Kabupaten"
        });
        $("#kecamatan").remoteChained({
            parents : "#kabupaten",
            url : "{{ route('json.kecamatan') }}",
            loading : "Kecamatan"
        });
        $("#desa").remoteChained({
            parents : "#kecamatan",
            url : "{{ route('json.desa') }}",
            loading : "Kelurahan / Desa",
            clear : true
        });

        /* Set the width of the side navigation to 250px */
        function openNav() {
            document.getElementById("sidenav").style.marginLeft = "0";
        }

        /* Set the width of the side navigation to 0 */
        function closeNav() {
            document.getElementById("sidenav").style.marginLeft = "-100%";
        } 
        
        //initiate the plugin and pass the id of the div containing gallery images
        $("#zoomImage").elevateZoom({gallery:'zoomGallery', cursor: 'pointer', galleryActiveClass: 'active', imageCrossfade: true, loadingIcon: 'https://rs543.pbsrc.com/albums/gg467/RREF/Random/loading_wh.gif~c200'}); 

        //pass the images to Fancybox
        $("#zoomImage").bind("click", function(e) {  
          var ez =   $('#zoomImage').data('elevateZoom');   
            $.fancybox(ez.getGalleryList());
          return false;
        });
        
        // Date Picker
        var datepickerSelector = $('.datepicker-01');
        datepickerSelector.datepicker({
            showOtherMonths: true,
            selectOtherMonths: true,
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-m-d',
            yearRange: '-100:+0'
        }).prev('.input-group-btn').on('click', function (e) {
            e && e.preventDefault();
            datepickerSelector.focus();
        });
        $.extend($.datepicker, { _checkOffset: function (inst,offset,isFixed) { return offset; } });

        // Now let's align datepicker with the prepend button
        var datepickerLeft = datepickerSelector.prev('.input-group-btn').outerWidth();
        datepickerSelector.datepicker('widget').css({ 'margin-left': -datepickerLeft });

        // jQuery UI Spinner
        $.widget('ui.customspinner', $.ui.spinner, {
            widgetEventPrefix: $.ui.spinner.prototype.widgetEventPrefix,
            _buttonHtml: function () { // Remove arrows on the buttons
                return '' +
                '<a class="ui-spinner-button ui-spinner-up ui-corner-tr">' +
                '<span class="ui-icon ' + this.options.icons.up + '"></span>' +
                '</a>' +
                '<a class="ui-spinner-button ui-spinner-down ui-corner-br">' +
                '<span class="ui-icon ' + this.options.icons.down + '"></span>' +
                '</a>';
            }
        });

        $('#spinner-01, #spinner-02, #spinner-03, #spinner-04, #spinner-05').customspinner({
            min: $(this).attr('min'),
            max: $(this).attr('max')
        }).on('focus', function () {
            $(this).closest('.ui-spinner').addClass('focus');
        }).on('blur', function () {
            $(this).closest('.ui-spinner').removeClass('focus');
        });
    </script>

</body>
</html>
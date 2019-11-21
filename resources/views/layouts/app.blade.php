<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    {{-- <meta name="robots" content="noindex">
    <meta name="googlebot" content="noindex"> --}}
    
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

    <!-- Google Play -->
    {{-- <meta name="google-play-app" content="{{ 'app-id='.$appid_playstore }}" app-argument="{{ url('/') }}"> --}}

    <!-- Start SmartBanner configuration -->
    {{-- <meta name="smartbanner:title" content="{{ $app_title }}">
    <meta name="smartbanner:author" content="{{ $app_author }}">

    <meta name="smartbanner:price" content="FREE">
    <meta name="smartbanner:price-suffix-google" content=" - In Google Play">
    <meta name="smartbanner:icon-google" content="{{ asset('uploads/options/'.$logo_square) }}">
    <meta name="smartbanner:button" content="{{ $app_button }}">
    <meta name="smartbanner:button-url-google" content="{{ $link_playstore }}">
    <meta name="smartbanner:enabled-platforms" content="android"> --}}
    <!-- End SmartBanner configuration -->

    <link rel="stylesheet" href="{{ asset('scripts/smartbanner/smartbanner.min.css?ver='.date('ymdhis')) }}">
    <script src="{{ asset('scripts/smartbanner/smartbanner.min.js?ver='.date('ymdhis')) }}"></script>

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/vendor/fontawesome5.9.0/css/all.min.css') }}" >

    <link href="https://fonts.googleapis.com/css?family=Oswald:300,400,500,700" rel="stylesheet">
    {{-- <link href="https://fonts.googleapis.com/css?family=Varela+Round" rel="stylesheet"> --}}

    <!-- Loading Bootstrap -->
    <link href="{{ asset('css/vendor/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Loading Flat UI Pro -->
    <link href="{{ asset('css/flat-ui-pro.min.css') }}" rel="stylesheet">

    <!-- Font -->
    <link href="{{ asset('css/style.min.css') }}" rel="stylesheet">

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="{{ asset('scripts/jquery.fortune.js') }}"></script>
    <script src="{{ asset('scripts/jquery.plugin.min.js') }}"></script>
    <script src="{{ asset('scripts/jquery.countdown.js') }}"></script>

    <!-- Swiper -->
    <link href="{{ asset('css/swiper.min.css') }}" rel="stylesheet">
    <script src="{{ asset('scripts/swiper.min.js') }}"></script>

    <!-- Chained -->
    <script src="{{ asset('scripts/jquery.chained.min.js') }}"></script>
    <script src="{{ asset('scripts/jquery.chained.remote.min.js') }}"></script>

    <!-- Inhouse -->
    <link href="{{ asset('css/inhouse.css') }}" rel="stylesheet">
    <!-- DateTimePicker -->
    <link rel="stylesheet" type="text/css" href="{{ asset('scripts/DateTimePicker/DateTimePicker.min.css') }}" />

    <!-- Include CSS -->
    @yield("css")

    @auth
    <!-- Pusher -->
    <script src="{{ asset('scripts/echo.min.js') }}"></script>
    <script src="https://js.pusher.com/4.1/pusher.min.js"></script>
    <script type="text/javascript">
        // Broadcast
        window.Echo = new Echo({
            broadcaster: 'pusher',
            key: '{{ env('PUSHER_APP_KEY') }}',
            cluster: 'ap1',
            encrypted: true,
        });
    </script>
    @endauth
    <!-- Vue -->
    {{-- 
    <link rel=stylesheet href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900|Sansita:400,700,800,900|Open+Sans:300,400,600,700|Roboto+Condensed:300,400,700|Open+Sans+Condensed:300,700|Roboto+Mono:100:300:400:500:700">
    <script src=https://cdnjs.cloudflare.com/ajax/libs/iScroll/5.2.0/iscroll-probe.min.js></script>
    <link rel=preload as=script href="{{ asset('/assets/vue-app/js/app.59ddb3e7.js') }}">
    <link rel=preload as=script href="{{ asset('/assets/vue-app/js/chunk-vendors.8f30490e.js') }}">
    <link rel=preload as=style href="{{ asset('/assets/vue-app/css/app.27c5a500.css') }}">
    <link rel=preload as=style href="{{ asset('/assets/vue-app/css/chunk-vendors.9059245e.css') }}">
    <link rel=stylesheet href="{{ asset('/assets/vue-app/css/app.27c5a500.css') }}">
    <link rel=stylesheet href="{{ asset('/assets/vue-app/css/chunk-vendors.9059245e.css') }}">
    <link rel=manifest href=/manifest.json>
    <meta name=apple-mobile-web-app-capable content=no>
    <meta name=apple-mobile-web-app-status-bar-style content=default>
    <meta name=msapplication-TileColor content=#000000> --}}

    <!-- New Addons For Header and Footer -->
    <!-- Google font -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,700" rel="stylesheet">

    <!-- Custom stlylesheet -->
    <link type="text/css" rel="stylesheet" href="{{ asset('custom/css/style.min.css')}}"/>

    <!-- Digital stlylesheet -->
    <link rel="stylesheet" href="{{ asset('assets/css/digital/index.css') }}">
</head>

<body>
    <!-- HEADER -->
        @include('layouts.includes.header')
    <!-- /HEADER -->
    
    @auth
        @if (Auth::user()->activated == 0)
            <section class="notif fixed">
                <div class="container">Selangkah lagi menjadi bagian dari kami! Kami telah mengirim Activation Code, harap segera cek Email Anda.</div>
            </section>
        @endif
    @endauth
	
    @yield('content')
    <input type="hidden" name="ctoken" value="">

    @if (empty($hideFooter))
        <!-- FOOTER -->
            @include('layouts.includes.footer')
        <!-- /FOOTER -->
    @endif

    <!-- Floating Button -->
    @if (!empty($contact_button))
        @if (empty($contactHide))
            <a href="{{ route('contact') }}" class="btn btn-primary btn-rounded contact-button" id="contactBtn">
                <i class="fas fa-headphones"></i> Hubungi Kami
            </a>
        @endif
    @endif

    <!-- Start Floating Button Live Streaming -->
    <div class="float d-none" id="streamBtn">
        <a href="/streaming/live" class="draggable">
            <img src="{{ asset('images/live.png') }}" class="rounded mx-auto d-block" />
        </a>
    </div>
    <div class="label-container">
        <div class="label-text">Ngobral Ngobrol</div>
    </div>
    <!-- End of Floating Button Live Streaming -->
    <!-- /Floating Button -->

    <!-- Modal Includes -->
        {{-- @include('layouts.includes.modal.signin')
        @include('layouts.includes.modal.forget')
        @include('layouts.includes.modal.signup') --}}
    <!-- /Modal Includes -->

    <!-- Javascript Sources -->
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    {{-- <script src="{{ asset('scripts/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('scripts/jquery.fortune.js') }}"></script>
    <script src="{{ asset('scripts/jquery.plugin.min.js') }}"></script>
    <script src="{{ asset('scripts/jquery.countdown.js') }}"></script>

    <!-- Chained -->
    <script src="{{ asset('scripts/jquery.chained.min.js') }}"></script>
    <script src="{{ asset('scripts/jquery.chained.remote.min.js') }}"></script>

    @auth
        <!-- Pusher -->
        <script src="{{ asset('scripts/echo.js') }}"></script>
        <script src="{{ asset('scripts/pusher.min.js') }}"></script>
        <script type="text/javascript">
            // Broadcast
            window.Echo = new Echo({
                broadcaster: 'pusher',
                key: '{{ env('PUSHER_APP_KEY') }}',
                cluster: 'ap1',
                encrypted: true,
            });
        </script>
    @endauth --}}

    <!-- Javascript Custom for header and footer -->
    <script src="{{ asset('custom/js/main.js')}}"></script>

    <!-- jQuery UI -->
    <script src="{{ asset('scripts/jquery-ui.min.js') }}"></script>

    <!-- Bootstrap 4 requires Popper.js -->
    <script src="{{ asset('scripts/popper.min.js') }}"></script>
    <!--<script src="https://vjs.zencdn.net/6.6.3/video.js"></script>-->

    <script src="{{ asset('scripts/flat-ui-pro.min.js') }}"></script>

    <script src="{{ asset('scripts/jquery.elevatezoom.js') }}"></script>

    <script src="{{ asset('scripts/jquery.numeric.min.js') }}"></script>

    <script src="{{ asset('scripts/inhouse.js') }}"></script>

    <script type="text/javascript" src="{{ asset('scripts/DateTimePicker/DateTimePicker.js') }}"></script>

    <!--[if lt IE 9]>
        <link rel="stylesheet" type="text/css" href="{{ asset('scripts/DateTimePicker/DateTimePicker-ltie9.css') }}" />
        <script type="text/javascript" src="{{ asset('scripts/DateTimePicker/DateTimePicker-ltie9.js') }}"></script>
    <![endif]-->

    <script type="text/javascript">
        $(document).ready(function() {
            // Hide floating button live streaming 
            var pathName = window.location.pathname;
            var blacklistFloatBtn = ["/streaming/live", "/cart", "/checkout", "/gateway"];

            if (blacklistFloatBtn.indexOf(pathName) != -1) {
                $('#streamBtn').hide();
                $('#contactBtn').hide();
            }

            // Dragable Stream Button
            $('#streamBtn .draggable').draggable({ cancel: false});

            // Listen to new live stream
            $.getJSON("/streaming/listener", function(res) {
                if (res.status_message) {
                    $('#streamBtn').removeClass('d-none');
                }
            })
            .fail(function(e) {
                console.log("Nothing to show" );
            })
        });

        // Window
        var window_width = $(window).width();

        $(".dropdownx-button").click(function() {
            $(".dropdownx-menu").toggle();
        });
        
        // Search Keyword
        if (window_width <= 768) {
            $('.search-keyword.desktop').remove();
        }
        if (window_width <= 1200) {
            $('.search-category.desktop').remove();
        }
        if (window_width > 1200) {
            $('.search-category.mobile').remove();
        }

        function search_submit() {
            var search_keyword = $('.search-keyword').val();
            var search_type = $('.search-type option:selected').val();
            var search_condition = $('.search-condition option:selected').val();
            var search_category = $('.search-category option:selected').val();
            var search_min = $('.search-min').val();
            var search_max = $('.search-max').val();
            var search_location = $('.search-location option:selected').val();
            var search_sort = $('.search-sort').val();

            if (typeof search_type === "undefined") {
                search_type = 1;
            }
            if (typeof search_condition === "undefined") {
                search_condition = 1;
            }
            if (typeof search_category === "undefined") {
                search_category = 0;
            }
            if (typeof search_min === "undefined") {
                search_min = '';
            }
            if (typeof search_max === "undefined") {
                search_max = '';
            }
            if (typeof search_location === "undefined") {
                search_location = 0;
            }
            if (typeof search_sort === "undefined") {
                search_sort = 'new';
            }
            
            var search = '{{ url("search?category='+search_category+'&keyword='+search_keyword+'&type='+search_type+'&condition='+search_condition+'&location='+search_location+'&min='+search_min+'&max='+search_max+'&sort='+search_sort+'") }}';
           
            window.location.href = search;
        }

        // Search Submit
        $(".search-button").click(function() {
            search_submit();
        });

            
        // Search Enter
        $('.search-keyword').on("keydown", function(e) {
            if (e.keyCode == 13 && e.shiftKey) { }
            else if ( e.keyCode == 13 ) {
                search_submit();
            }
        });

        // Search Sort
        $(".search-sort").change(function() {
            search_submit();
        });

        // Search Category
        $(".search-category").change(function() {
            search_submit();
        });
        
    @auth
        // Message Content
        $.getJSON('{{ route("json.stats") }}',
            function(data) {
                if (data.cart > 0) {
                    $('.cart-count').html('<span class="m-0">'+data.cart+'</span>');
                } else {
                    $('.cart-count').parent().hide();
                }
                if (data.message > 0) {
                    $('.message-count').html('<span class="m-0">'+data.message+'</span>');
                }
                if (data.buy > 0) {
                    $('.buy-count').html('<span class="m-0">'+data.buy+'</span>');
                }
                if (data.sell > 0) {
                    $('.sell-count').html('<span class="m-0">'+data.sell+'</span>');
                }
        });
        
        // Notification
        Echo.private('counter.{{ Auth::user()->id }}')
        .listen('CounterNotification', (data) => {
            if (data.counter.cart > 0) {
                $('.cart-count').html('<span>'+data.counter.cart+'</span>');
            } else {
                $('.cart-count').html('');
            }

            if (data.counter.message > 0) {
                $('.message-count').html('<span>'+data.counter.message+'</span>');
            } else {
                $('.message-count').html('');
            }
            
            if (data.counter.buy > 0) {
                $('.buy-count').html('<span>'+data.counter.buy+'</span>');
            } else {
                $('.buy-count').html('');
            }
            
            if (data.counter.sell > 0) {
                $('.sell-count').html('<span>'+data.counter.sell+'</span>');
            } else {
                $('.sell-count').html('');
            }
        });

        // Product Preorder Form
        $(".select-preorder").change(function() {
            var preorder = $(this).val();
            if (preorder == 1)
            {
                $(".form-preorder").show();
            }
            if (preorder == 0)
            {
                $(".form-preorder").hide();
            }
        });
    @endauth

        // Countdown
    @if(!empty($countdown_flashsale))
        $(function () {
            var ca = '{!! substr($countdown_flashsale,0,4) !!}';
            var cb = '{!! substr($countdown_flashsale,5,2) !!}';
            var cc = '{!! substr($countdown_flashsale,8,2) !!}';
            var cd = '{!! substr($countdown_flashsale,11,2) !!}';
            var ce = '{!! substr($countdown_flashsale,14,2) !!}';
            var cf = '{!! substr($countdown_flashsale,17,2) !!}';

            var countdown = new Date(ca, cb - 1, cc, cd, ce, cf, 0);
            
            $('.flashsale-countdown').countdown({until: countdown, compact: true, format: 'HMS'});
        });
    @endif

        // Nav Tabs
        $('.nav-tabs.smart a').on('click', function (e) {
            e.preventDefault();
            $(this).tab('show');
        });

        // Show Password
        $('.show-password').on('click', function () {
            var type = $('.input-password').attr('type');

            if (type == 'password') {
                $('.input-password').attr('type', 'text');
                $('.show-password').html('<i class="far fa-eye-slash"></i>');
            } else {
                $('.input-password').attr('type', 'password');
                $('.show-password').html('<i class="far fa-eye"></i>');
            }
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
            document.getElementById("sidenav").style.marginRight = "0";
        }

        /* Set the width of the side navigation to 0 */
        function closeNav() {
            document.getElementById("sidenav").style.marginRight = "-100%";
        } 
        
        //initiate the plugin and pass the id of the div containing gallery images
        $("#zoomImage").elevateZoom({gallery:'zoomGallery', cursor: 'pointer', galleryActiveClass: 'active', imageCrossfade: true, loadingIcon: 'https://rs543.pbsrc.com/albums/gg467/RREF/Random/loading_wh.gif~c200'}); 

        //pass the images to Fancybox
        $("#zoomImage").bind("click", function(e) {  
          var ez =   $('#zoomImage').data('elevateZoom');   
            $.fancybox(ez.getGalleryList());
          return false;
        });
        
        // Date Time Picker
        $(".datetimepicker").DateTimePicker({
            dateTimeFormat: "yyyy-MM-dd HH:mm:ss",
            dateFormat: "yyyy-MM-dd",
            timeFormat: "HH:mm:ss",

            addEventHandlers: function()
            {
                var oDTP = this;
            
                oDTP.settings.minDateTime = oDTP.getDateTimeStringInFormat("DateTime", "yyyy-MM-dd HH:mm:ss", new Date());
            }
        });

        // Date Picker
        var datepickerSelector = $('.datepicker-01');
        datepickerSelector.datepicker({
            showOtherMonths: true,
            selectOtherMonths: true,
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
            maxDate: -1,
            yearRange: '-100:+0'
        }).prev('.input-group-btn').on('click', function (e) {
            e && e.preventDefault();
            datepickerSelector.focus();
        });
        $.extend($.datepicker, { _checkOffset: function (inst,offset,isFixed) { return offset; } });

        // Now let's align datepicker with the prepend button
        var datepickerLeft = datepickerSelector.prev('.input-group-btn').outerWidth();
        datepickerSelector.datepicker('widget').css({ 'margin-left': -datepickerLeft });

        // Date Picker Future
        var datepickerSelectorFuture = $('.datepicker-02');
        datepickerSelectorFuture.datepicker({
            showOtherMonths: true,
            selectOtherMonths: true,
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
            minDate: +1,
            yearRange: '0:+100'
        }).prev('.input-group-btn').on('click', function (e) {
            e && e.preventDefault();
            datepickerSelectorFuture.focus();
        });
        $.extend($.datepicker, { _checkOffset: function (inst,offset,isFixed) { return offset; } });

        // Now let's align datepicker with the prepend button
        var datepickerLeftFuture = datepickerSelectorFuture.prev('.input-group-btn').outerWidth();
        datepickerSelectorFuture.datepicker('widget').css({ 'margin-left': -datepickerLeftFuture });

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
    @yield("scripts")
    {{-- <div id="vueApp" data-args="{{ $args }}"></div>
    <script src="{{ asset('/assets/vue-app/js/app.59ddb3e7.js') }}"></script>
    <script src="{{ asset('/assets/vue-app/js/chunk-vendors.8f30490e.js') }}"></script> --}}
</body>
</html>
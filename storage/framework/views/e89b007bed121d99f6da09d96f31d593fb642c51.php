<!DOCTYPE html>
<html lang="<?php echo e(app()->getLocale()); ?>">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    
    
    <title><?php echo $__env->yieldContent('title'); ?></title>

    <meta name="title" content="<?php echo $__env->yieldContent('title'); ?>"/>
    <meta name="description" content="<?php echo $__env->yieldContent('description'); ?>"/>
    <meta name="keywords" content="<?php echo e($seo_keywords); ?>">
    <meta name="copyright" content="<?php echo e($seo_copyright); ?>">

    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="<?php echo $__env->yieldContent('title'); ?>">
    <meta itemprop="description" content="<?php echo $__env->yieldContent('description'); ?>">

    <!-- Twitter Card data -->
    <meta name="twitter:title" content="<?php echo $__env->yieldContent('title'); ?>">
    <meta name="twitter:description" content="<?php echo $__env->yieldContent('description'); ?>">

    <!-- Open Graph data -->
    <meta property="fb:app_id" content="1907526829361575" />
    <meta property="og:type" content="website" /> 
    <meta property="og:title" content="<?php echo $__env->yieldContent('title'); ?>"/>
    <meta property="og:description" content="<?php echo $__env->yieldContent('description'); ?>"/>
    <meta property="og:site_name" content="<?php echo e(config('app.name')); ?>"/>
    <meta property="og:url" content="<?php echo e(url()->current()); ?>"/>
    
    <?php if(!empty($seo_image)): ?>
    <meta itemprop="image" content="<?php echo e($seo_image); ?>" />
    <meta name="twitter:image" content="<?php echo e($seo_image); ?>" />
    <meta property="og:image" content="<?php echo e($seo_image); ?>" />
    <link rel="image_src" type="image/png" href="<?php echo e($seo_image); ?>" />
    <?php else: ?>
    <meta itemprop="image" content="<?php echo e(asset('uploads/options/'.$logo)); ?>" />
    <meta name="twitter:image" content="<?php echo e(asset('uploads/options/'.$logo)); ?>" />
    <meta property="og:image" content="<?php echo e(asset('uploads/options/'.$logo)); ?>" />
    <link rel="image_src" type="image/png" href="<?php echo e(asset('uploads/options/'.$logo)); ?>" />
    <?php endif; ?>

    <link rel="icon" type="image/png" href="<?php echo e(asset('favicon.ico')); ?>">
    <link rel="shortcut icon" href="<?php echo e(asset('favicon.ico')); ?>">

    <?php echo $meta_header; ?>


    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <!-- Google Play -->
    <meta name="google-play-app" content="<?php echo e('app-id='.$appid_playstore); ?>" app-argument="<?php echo e(url('/')); ?>">

    <!-- Start SmartBanner configuration -->
    <meta name="smartbanner:title" content="<?php echo e($app_title); ?>">
    <meta name="smartbanner:author" content="<?php echo e($app_author); ?>">

    <meta name="smartbanner:price" content="FREE">
    <meta name="smartbanner:price-suffix-google" content=" - In Google Play">
    <meta name="smartbanner:icon-google" content="<?php echo e(asset('uploads/options/'.$logo_square)); ?>">
    <meta name="smartbanner:button" content="<?php echo e($app_button); ?>">
    <meta name="smartbanner:button-url-google" content="<?php echo e($link_playstore); ?>">
    <meta name="smartbanner:enabled-platforms" content="android">
    <!-- End SmartBanner configuration -->

    <link rel="stylesheet" href="<?php echo e(asset('scripts/smartbanner/smartbanner.min.css?ver='.date('ymdhis'))); ?>">
    <script src="<?php echo e(asset('scripts/smartbanner/smartbanner.min.js?ver='.date('ymdhis'))); ?>"></script>

    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo e(asset('css/vendor/fontawesome5.9.0/css/all.min.css')); ?>" >

    

    <!-- Loading Bootstrap -->
    <link href="<?php echo e(asset('css/vendor/bootstrap.min.css')); ?>" rel="stylesheet">

    <!-- Loading Flat UI Pro -->
    <link href="<?php echo e(asset('css/flat-ui-pro.min.css')); ?>" rel="stylesheet">

    <!-- Font -->
    <link href="<?php echo e(asset('css/style.min.css')); ?>" rel="stylesheet">

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="<?php echo e(asset('scripts/jquery.fortune.js')); ?>"></script>
    <script src="<?php echo e(asset('scripts/jquery.plugin.min.js')); ?>"></script>
    <script src="<?php echo e(asset('scripts/jquery.countdown.js')); ?>"></script>

    <!-- Swiper -->
    <link href="<?php echo e(asset('css/swiper.min.css')); ?>" rel="stylesheet">
    <script src="<?php echo e(asset('scripts/swiper.min.js')); ?>"></script>

    <!-- Chained -->
    <script src="<?php echo e(asset('scripts/jquery.chained.min.js')); ?>"></script>
    <script src="<?php echo e(asset('scripts/jquery.chained.remote.min.js')); ?>"></script>

    <!-- Inhouse -->
    <link href="<?php echo e(asset('css/inhouse.css')); ?>" rel="stylesheet">
    <!-- DateTimePicker -->
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('scripts/DateTimePicker/DateTimePicker.min.css')); ?>" />

    <!-- Include CSS -->
    <?php echo $__env->yieldContent("css"); ?>

    <?php if(auth()->guard()->check()): ?>
    <!-- Pusher -->
    <script src="<?php echo e(asset('scripts/echo.min.js')); ?>"></script>
    <script src="https://js.pusher.com/4.1/pusher.min.js"></script>
    <script type="text/javascript">
        // Broadcast
        window.Echo = new Echo({
            broadcaster: 'pusher',
            key: '<?php echo e(env('PUSHER_APP_KEY')); ?>',
            cluster: 'ap1',
            encrypted: true,
        });
    </script>
    <?php endif; ?>
    <!-- Vue -->
    

    <!-- New Addons For Header and Footer -->
    <!-- Google font -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,700" rel="stylesheet">

    <!-- Custom stlylesheet -->
    

    <!-- Digital stlylesheet -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/digital/index.css')); ?>">
</head>

<body>
    <header class="bg-brand text-white">
        <div id="sidenav" class="sidenav">
            <div class="head d-table w-100">
                <div class="d-table-cell text-left">

                <?php if(auth()->guard()->check()): ?>
                    <a href="<?php echo e(route('setting')); ?>" class="btn btn-setting">
                        <span class="icon-setting"></span>
                    </a>
                <?php else: ?>
                    <div style="width:50px;"></div>
                <?php endif; ?>

                </div>
                <div class="d-table-cell">

                <?php if(auth()->guard()->check()): ?>
                    <a href="<?php echo e(route('user.detail', ['username' => Auth::user()->username])); ?>" class="btn btn-user">
                        <img src="<?php echo e(asset('uploads/photos/'.Auth::user()->photo)); ?>">
                    </a>
                <?php else: ?>
                    <a href="<?php echo e(route('login')); ?>" class="btn btn-user">
                        <img src="<?php echo e(asset('images/profile.png')); ?>">
                    </a>
                <?php endif; ?>

                </div>
                <div class="d-table-cell text-right">
                    <a href="javascript:void(0)" onclick="closeNav()" class="btn btn-close">
                        <span class="icon-close"></span>
                    </a>
                </div>
            </div>
            <?php echo $__env->make('layouts.nav-white', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        </div>

        <div class="container d-table">

            <div class="d-none left-button">
                <button class="btn btn-menu" onclick="openNav()">
                    <span class="icon-menu"></span>
                </button>
            </div>

            <a href="<?php echo e(route('home')); ?>" class="d-table-cell align-top logo">
                <img src="<?php echo e(asset('uploads/options/'.$logo)); ?>" height="35px">
                <div class="d-none mobile">
                <?php if(!empty($headTitle)): ?>
                    <div class="title"><?php echo e($pageTitle); ?></div>
                <?php else: ?>
                    <img src="<?php echo e(asset('uploads/options/'.$logo)); ?>" height="35px">
                <?php endif; ?>
                </div>
            </a>
            
            <form method="get" action="<?php echo e(route('search')); ?>" class="d-table-cell form-search align-middle pl-4 pr-3 w-100">
                <div class="d-table w-100">
                    
                    <div class="d-table-cell select-search align-middle pr-4">
                        <select class="form-control select select-primary select-smart select-block m-0 search-category desktop">
                            <option value="">Kategori</option>
                        
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($item->slug); ?>" <?php if(!empty($category)): ?> <?php if($item->slug == $category->slug): ?> selected <?php endif; ?> <?php endif; ?> <?php if(!empty($search_category)): ?> <?php if($item->slug == $search_category): ?> selected <?php endif; ?> <?php endif; ?>><?php echo e($item->name); ?></option>
                            
                        <?php $__currentLoopData = $item->child; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($item->slug); ?>" <?php if(!empty($category)): ?> <?php if($item->slug == $category->slug): ?> selected <?php endif; ?> <?php endif; ?> <?php if(!empty($search_category)): ?> <?php if($item->slug == $search_category): ?> selected <?php endif; ?> <?php endif; ?>>-- <?php echo e($item->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="d-table-cell align-middle w-100">
                        <div class="input-search input-group">
                            <input type="text" name="keyword" class="form-control px-4 search-keyword desktop" placeholder="Cari Produk atau toko ..." <?php if(!empty($search_keyword)): ?> value="<?php echo e($search_keyword); ?>" <?php endif; ?>>

                            <div class="input-group-append px-2">
                                <button type="button" class="btn d-print-inline-block search-button"><span class="icon-search"></span></button>
                            </div>
                        </div>
                    </div>

                </div>
            </form>

            <?php if(auth()->guard()->check()): ?>
            <div class="d-table-cell align-top action-user">

                <a href="<?php echo e(route('cart')); ?>" class="btn btn-cart mr-1">
                    <span class="cart-count count"></span>
                    <span class="icon-cart"></span>
                </a>

                <button class="btn btn-user" onclick="openNav()">
                    <img src="<?php echo e(asset('uploads/photos/'.Auth::user()->photo)); ?>">
                </button>

                <!--
                <button class="btn btn-user dropdownx-button">
                    <img src="<?php echo e(asset('uploads/photos/'.Auth::user()->photo)); ?>">
                </button>
                <div class="navuser dropdownx-menu">
                    <div class="arrow-up"></div>
                    <?php echo $__env->make('layouts.nav-white', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                </div>
                -->

                <a href="<?php echo e(route('search')); ?>" class="btn btn-search d-none">
                    <span class="icon-search-white"></span>
                </a>

            </div>
            
            <?php else: ?>
            <div class="d-table-cell align-top action-guest">

                <a href="<?php echo e(route('register')); ?>" class="btn btn-primary btn-daftar">
                    Daftar
                </a>
                <a href="<?php echo e(route('login')); ?>" class="btn btn-primary btn-masuk">
                    Masuk
                </a>

            </div>
            <?php endif; ?>

        </div>
    </header>

    <!-- HEADER -->
        
    <!-- /HEADER -->
    
    <?php if(auth()->guard()->check()): ?>
        <?php if(Auth::user()->activated == 0): ?>
            <section class="notif fixed">
                <div class="container">Selangkah lagi menjadi bagian dari kami! Kami telah mengirim Activation Code, harap segera cek Email Anda.</div>
            </section>
        <?php endif; ?>
    <?php endif; ?>
	
    <?php echo $__env->yieldContent('content'); ?>
    <input type="hidden" name="ctoken" value="">

    <?php if(empty($hideFooter)): ?>
        <footer class="bg-brand">
            <div class="links py-5">
                <div class="container">
                    <div class="row">

                        <div class="col-md-3">
                            <div class="title"><?php echo e($footer_one->name); ?></div>

                            <a href="<?php echo e(route('ads.request')); ?>">Beriklan Sekarang</a>
                            <a href="http://forum.mymspmall.id" target="_blank">MSP Forum</a>
                        
                            <?php $__currentLoopData = $footer_one->page; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <a href="<?php echo e(route('page.detail', ['slug' => $item->slug])); ?>"><?php echo e($item->name); ?></a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                        <div class="col-md-3">
                            <div class="title"><?php echo e($footer_two->name); ?></div>
                    
                            <?php $__currentLoopData = $footer_two->page; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <a href="<?php echo e(route('page.detail', ['slug' => $item->slug])); ?>"><?php echo e($item->name); ?></a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                        <div class="col-md-3">
                            <div class="title"><?php echo e($footer_three->name); ?></div>

                            <a href="<?php echo e(route('merchant.join')); ?>">Menjadi Merchant</a>
                    
                            <?php $__currentLoopData = $footer_three->page; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <a href="<?php echo e(route('page.detail', ['slug' => $item->slug])); ?>"><?php echo e($item->name); ?></a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                        <div class="col-md-3">
                            <div class="title"><?php echo e($footer_four->name); ?></div>

                            <?php if(auth()->guard()->guest()): ?>
                                <a href="<?php echo e(route('password.request')); ?>">Reset Password</a>
                            <?php endif; ?>
                        
                            <?php $__currentLoopData = $footer_four->page; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <a href="<?php echo e(route('page.detail', ['slug' => $item->slug])); ?>"><?php echo e($item->name); ?></a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                    </div>
                </div>
            </div>

            <div class="copyright py-3">
                <div class="container">
                <div class="d-table w-100">

                    <a href="<?php echo e(route('home')); ?>" class="d-table-cell align-middle logo w-50">
                        <img src="<?php echo e(asset('uploads/options/'.$logo)); ?>" height="35px">
                    </a>

                    <div class="d-table-cell text-right w-50">
                        <a href="<?php echo e($link_facebook); ?>" class="btn btn-rounded btn-facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="<?php echo e($link_instagram); ?>" class="btn btn-rounded btn-instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div>
                    
                </div>
                </div>
            </div>
        </footer>

        <!-- FOOTER -->
            
        <!-- /FOOTER -->
    <?php endif; ?>

    <!-- Floating Button -->
    <?php if(!empty($contact_button)): ?>
        <?php if(empty($contactHide)): ?>
            <a href="<?php echo e(route('contact')); ?>" class="btn btn-primary btn-rounded contact-button" id="contactBtn">
                <i class="fas fa-headphones"></i> Hubungi Kami
            </a>
        <?php endif; ?>
    <?php endif; ?>

    <!-- Start Floating Button Live Streaming -->
    <div class="float d-none" id="streamBtn">
        <a href="/streaming/live" class="draggable">
            <img src="<?php echo e(asset('images/live.png')); ?>" class="rounded mx-auto d-block" />
        </a>
    </div>
    <div class="label-container">
        <div class="label-text">Ngobral Ngobrol</div>
    </div>
    <!-- End of Floating Button Live Streaming -->
    <!-- /Floating Button -->

    <!-- Modal Includes -->
        
    <!-- /Modal Includes -->

    <!-- Javascript Sources -->
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    

    <!-- Javascript Custom for header and footer -->
    

    <!-- jQuery UI -->
    <script src="<?php echo e(asset('scripts/jquery-ui.min.js')); ?>"></script>

    <!-- Bootstrap 4 requires Popper.js -->
    <script src="<?php echo e(asset('scripts/popper.min.js')); ?>"></script>
    <!--<script src="https://vjs.zencdn.net/6.6.3/video.js"></script>-->

    <script src="<?php echo e(asset('scripts/flat-ui-pro.min.js')); ?>"></script>

    <script src="<?php echo e(asset('scripts/jquery.elevatezoom.js')); ?>"></script>

    <script src="<?php echo e(asset('scripts/jquery.numeric.min.js')); ?>"></script>

    <script src="<?php echo e(asset('scripts/inhouse.js')); ?>"></script>

    <script type="text/javascript" src="<?php echo e(asset('scripts/DateTimePicker/DateTimePicker.js')); ?>"></script>

    <!--[if lt IE 9]>
        <link rel="stylesheet" type="text/css" href="<?php echo e(asset('scripts/DateTimePicker/DateTimePicker-ltie9.css')); ?>" />
        <script type="text/javascript" src="<?php echo e(asset('scripts/DateTimePicker/DateTimePicker-ltie9.js')); ?>"></script>
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
            
            var search = '<?php echo url("search?category='+search_category+'&keyword='+search_keyword+'&type='+search_type+'&condition='+search_condition+'&location='+search_location+'&min='+search_min+'&max='+search_max+'&sort='+search_sort+'"); ?>';
           
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
        
    <?php if(auth()->guard()->check()): ?>
        // Message Content
        $.getJSON('<?php echo e(route("json.stats")); ?>',
            function(data) {
                if (data.cart > 0) {
                    $('.cart-count').html('<span>'+data.cart+'</span>');
                }
                if (data.message > 0) {
                    $('.message-count').html('<span>'+data.message+'</span>');
                }
                if (data.buy > 0) {
                    $('.buy-count').html('<span>'+data.buy+'</span>');
                }
                if (data.sell > 0) {
                    $('.sell-count').html('<span>'+data.sell+'</span>');
                }
        });
        
        // Notification
        Echo.private('counter.<?php echo e(Auth::user()->id); ?>')
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
    <?php endif; ?>

        // Countdown
    <?php if(!empty($countdown_flashsale)): ?>
        $(function () {
            var ca = '<?php echo substr($countdown_flashsale,0,4); ?>';
            var cb = '<?php echo substr($countdown_flashsale,5,2); ?>';
            var cc = '<?php echo substr($countdown_flashsale,8,2); ?>';
            var cd = '<?php echo substr($countdown_flashsale,11,2); ?>';
            var ce = '<?php echo substr($countdown_flashsale,14,2); ?>';
            var cf = '<?php echo substr($countdown_flashsale,17,2); ?>';

            var countdown = new Date(ca, cb - 1, cc, cd, ce, cf, 0);
            
            $('.flashsale-countdown').countdown({until: countdown, compact: true, format: 'HMS'});
        });
    <?php endif; ?>

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
            url : "<?php echo e(route('json.kabupaten')); ?>",
            loading : "Kota / Kabupaten"
        });
        $("#kecamatan").remoteChained({
            parents : "#kabupaten",
            url : "<?php echo e(route('json.kecamatan')); ?>",
            loading : "Kecamatan"
        });
        $("#desa").remoteChained({
            parents : "#kecamatan",
            url : "<?php echo e(route('json.desa')); ?>",
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
    <?php echo $__env->yieldContent("scripts"); ?>
    
</body>
</html>
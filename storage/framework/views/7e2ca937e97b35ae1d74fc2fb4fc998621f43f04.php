<!DOCTYPE html>
<html lang="<?php echo e(app()->getLocale()); ?>">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <meta name="robots" content="noindex">
    <meta name="googlebot" content="noindex">
    
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
    
    <!-- CSS -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">

    <link href="https://fonts.googleapis.com/css?family=Oswald:300,400,500,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Varela+Round" rel="stylesheet">

    <!-- Loading Bootstrap -->
    <link href="<?php echo e(asset('css/vendor/bootstrap.min.css')); ?>" rel="stylesheet">

    <!-- Loading Flat UI Pro -->
    <link href="<?php echo e(asset('css/flat-ui-pro.css')); ?>" rel="stylesheet">

    <!-- Font -->
    <link href="<?php echo e(asset('css/style.css')); ?>" rel="stylesheet">

  </head>
  <body class="bg-brand">
    <header class="bg-brand text-white">
        <div id="sidenav" class="sidenav">
            <div class="head d-table w-100">
                <div class="d-table-cell text-left">
                
                <?php if(auth()->guard()->check()): ?>
                    <a href="#" class="btn btn-setting">
                        <span class="icon-setting"></span>
                    </a>
                <?php else: ?>
                    <div style="width:50px;"></div>
                <?php endif; ?>
                
                </div>
                <div class="d-table-cell">

                <?php if(auth()->guard()->check()): ?>
                    <a href="#" class="btn btn-user">
                        <img src="<?php echo e(asset('images/Fahrizal.png')); ?>">
                    </a>
                <?php else: ?>
                <a href="#" class="btn btn-user">
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

        </div>
    </header>

	<?php echo $__env->yieldContent('content'); ?>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>

    <!-- Bootstrap 4 requires Popper.js -->
    <script src="https://unpkg.com/popper.js@1.14.1/dist/umd/popper.min.js" crossorigin="anonymous"></script>

    <!--<script src="http://vjs.zencdn.net/6.6.3/video.js"></script>-->

    <script src="<?php echo e(asset('scripts/flat-ui-pro.min.js')); ?>"></script>

    <script src="<?php echo e(asset('scripts/jquery.elevatezoom.js')); ?>"></script>

    <script type="text/javascript">
        // Input Elements
        $(':checkbox').radiocheck();
        
        $(".select-smart").select2({dropdownCssClass: 'dropdown-inverse'});

                /* Set the width of the side navigation to 250px */
        function openNav() {
            document.getElementById("sidenav").style.marginLeft = "0";
        }

        /* Set the width of the side navigation to 0 */
        function closeNav() {
            document.getElementById("sidenav").style.marginLeft = "-100%";
        } 
        
        //initiate the plugin and pass the id of the div containing gallery images
        $("#zoomImage").elevateZoom({gallery:'zoomGallery', cursor: 'pointer', galleryActiveClass: 'active', imageCrossfade: true, loadingIcon: 'http://rs543.pbsrc.com/albums/gg467/RREF/Random/loading_wh.gif~c200'}); 

        //pass the images to Fancybox
        $("#zoomImage").bind("click", function(e) {  
          var ez =   $('#zoomImage').data('elevateZoom');   
            $.fancybox(ez.getGalleryList());
          return false;
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
    </script>

  </body>
</html>
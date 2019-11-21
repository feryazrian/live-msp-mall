<!DOCTYPE html>
<html lang=id-ID>
  <head>
    <meta charset=utf-8>
    <meta http-equiv=X-UA-Compatible content="IE=edge">
    <meta name=viewport content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <link rel=icon href=/favicon.ico>
    <title>MSPMall</title>
    <link rel=stylesheet href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900|Sansita:400,700,800,900|Open+Sans:300,400,600,700|Roboto+Condensed:300,400,700|Open+Sans+Condensed:300,700|Roboto+Mono:100,300,400,500,700">
    <script src=https://cdnjs.cloudflare.com/ajax/libs/iScroll/5.2.0/iscroll-probe.min.js></script>
    <script src=https://www.google.com/recaptcha/api.js async defer></script>
    <script>var vmVue = null</script>
    <link rel="preload" as="style" href="<?php echo e($cssApp); ?>">
    <link rel="preload" as="style" href="<?php echo e($cssChunkVendors); ?>">
    <link rel="preload" as="script" href="<?php echo e($jsApp); ?>">
    <link rel="preload" as="script" href="<?php echo e($jsChunkVendors); ?>">
    <link rel="stylesheet" href="<?php echo e($cssChunkVendors); ?>">
    <link rel="stylesheet" href="<?php echo e($cssApp); ?>">
    
    
    
    <meta name="apple-mobile-web-app-capable" content="no">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="MSPMall">
    
    
    
  </head>
<body>
  <noscript><strong>We're sorry but MSPMall doesn't work properly without JavaScript enabled. Please enable it to continue.</strong></noscript>
  <div id="vueApp" usr="<?php echo e(Auth::user()); ?>"></div>
  <script src="<?php echo e($jsChunkVendors); ?>"></script>
  <script src="<?php echo e($jsApp); ?>"></script>
</body>
</html>
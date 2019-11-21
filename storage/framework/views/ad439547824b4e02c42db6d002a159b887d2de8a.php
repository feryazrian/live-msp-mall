<!DOCTYPE html>
<html lang=id-ID>
<?php
    $dir = '/assets/vue';
    $vue = public_path($dir);
    $css = $vue.'/css';
    $js = $vue.'/js';
    $jsApp = '';
    $cssApp = '';
    $jsChunk = '';
    $cssChunk = '';
    $APP = 'app.';
    $CHUNK = 'chunk-vendors.';
    foreach(\File::files($js) as $v) {
      if($v->getExtension() == 'js') {
        $name = $v->getFilename();
        if(strpos($name, $APP) === 0) {
          $jsApp = $dir.'/js/'.$name;
        } else
        if(strpos($name, $CHUNK) === 0)
        $jsChunk = $dir.'/js/'.$name;
      }
    }
    foreach(\File::files($css) as $v) {
      if($v->getExtension() == 'css') {
        $name = $v->getFilename();
        if(strpos($name, $APP) === 0) {
          $cssApp = $dir.'/css/'.$name;
        } else
        if(strpos($name, $CHUNK) === 0)
        $cssChunk = $dir.'/css/'.$name;
      }
    }
    $jsChunkVendors = $jsChunk;
    $cssChunkVendors = $cssChunk;
?>
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
  <div id="vueApp" usr="<?php echo e(Auth::user()); ?>" token="<?php echo e(csrf_token()); ?>" error="<?php echo e($errors); ?>" message="<?php echo e(session('status')); ?>" warning="<?php echo e(session('warning')); ?>"></div>
  <script src="<?php echo e($jsChunkVendors); ?>"></script>
  <script src="<?php echo e($jsApp); ?>"></script>
</body>
</html>
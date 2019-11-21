<!DOCTYPE html>
<html lang=id-ID>
  <head>
    <meta charset=utf-8>
    <meta http-equiv=X-UA-Compatible content="IE=edge">
    <meta name=viewport content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel=icon href=/favicon.ico>
    <title>MSPMall</title>
    <link rel=stylesheet href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900|Sansita:400,700,800,900|Open+Sans:300,400,600,700|Roboto+Condensed:300,400,700|Open+Sans+Condensed:300,700|Roboto+Mono:100,300,400,500,700">
    <script src=https://cdnjs.cloudflare.com/ajax/libs/iScroll/5.2.0/iscroll-probe.min.js></script>
    <script src=https://www.google.com/recaptcha/api.js async defer></script>
    <script>var vmVue = null</script>
    <link rel="preload" as="style" href="{{ $cssApp }}">
    <link rel="preload" as="style" href="{{ $cssChunkVendors }}">
    <link rel="preload" as="script" href="{{ $jsApp }}">
    <link rel="preload" as="script" href="{{ $jsChunkVendors }}">
    <link rel="stylesheet" href="{{ $cssChunkVendors }}">
    <link rel="stylesheet" href="{{ $cssApp }}">
    {{--
    <link rel="icon" type="image/png" sizes="32x32" href="/img/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/img/icons/favicon-16x16.png">
    --}}
    {{-- <link rel="manifest" href="/manifest.json"> --}}
    {{-- <meta name="theme-color" content="#4DBA87"> --}}
    <meta name="apple-mobile-web-app-capable" content="no">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="MSPMall">
    {{-- <link rel="apple-touch-icon" href="/img/icons/apple-touch-icon-152x152.png"> --}}
    {{-- <link rel="mask-icon" href="/img/icons/safari-pinned-tab.svg" color="#4DBA87"> --}}
    {{-- <meta name="msapplication-TileImage" content="/img/icons/msapplication-icon-144x144.png">
    <meta name="msapplication-TileColor" content="#000000"> --}}
  </head>
<body>
  <noscript><strong>We're sorry but MSPMall doesn't work properly without JavaScript enabled. Please enable it to continue.</strong></noscript>
  <div id="vueApp" usr="{{ Auth::user() }}" token="{{ csrf_token() }}"></div>
  <script src="{{ $jsChunkVendors }}"></script>
  <script src="{{ $jsApp }}"></script>
</body>
</html>
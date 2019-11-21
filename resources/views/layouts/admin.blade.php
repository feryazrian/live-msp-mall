<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }}</title>

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link rel="stylesheet" type="text/css" href="{{ asset('boards/libs/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('boards/assets/fonts/line-awesome/css/line-awesome.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('boards/assets/fonts/montserrat/styles.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('boards/libs/tether/css/tether.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('boards/libs/jscrollpane/jquery.jscrollpane.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('boards/libs/flag-icon-css/css/flag-icon.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('boards/assets/styles/common.min.css') }}">
    <!-- END GLOBAL MANDATORY STYLES -->

    <!-- BEGIN THEME STYLES -->
    <link rel="stylesheet" type="text/css" href="{{ asset('boards/assets/styles/themes/old-brick.min.css') }}">
    <link class="ks-sidebar-dark-style" rel="stylesheet" type="text/css"
        href="{{ asset('boards/assets/styles/themes/sidebar-black.min.css') }}">

    <style>
        .ks-navbar {
            background: #ffbb00;
        }
    </style>
    <!-- END THEME STYLES -->

    <script src="{{ asset('boards/libs/jquery/jquery.min.js') }}"></script>

    <link rel="stylesheet" type="text/css" href="{{ asset('boards/assets/fonts/kosmo/styles.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('boards/assets/fonts/weather/css/weather-icons.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('boards/libs/c3js/c3.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('boards/libs/noty/noty.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('boards/assets/styles/widgets/payment.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('boards/assets/styles/widgets/panels.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('boards/assets/styles/dashboard/tabbed-sidebar.min.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('boards/libs/flatpickr/flatpickr.min.css') }}">
    <!-- original -->
    <link rel="stylesheet" type="text/css" href="{{ asset('boards/assets/styles/libs/flatpickr/flatpickr.min.css') }}">
    <!-- customization -->

    <link rel="stylesheet" type="text/css"
        href="{{ asset('boards/libs/datatables-net/media/css/dataTables.bootstrap4.min.css') }}"> <!-- original -->
    <link rel="stylesheet" type="text/css"
        href="{{ asset('boards/libs/datatables-net/extensions/buttons/css/buttons.bootstrap4.min.css') }}">
    <!-- original -->
    <link rel="stylesheet" type="text/css"
        href="{{ asset('boards/assets/styles/libs/datatables-net/datatables.min.css') }}"> <!-- customization -->
    <link rel="stylesheet" type="text/css"
        href="{{ asset('boards/libs/datatables-net/extensions/responsive/css/responsive.bootstrap4.min.css') }}">
    <!-- Original -->
    <link rel="stylesheet" type="text/css" href="{{ asset('boards/libs/select2/css/select2.min.css') }}">
    <!-- Original -->
    <link rel="stylesheet" type="text/css" href="{{ asset('boards/assets/styles/libs/select2/select2.min.css') }}">
    <!-- Customization -->

    <style>
        .carousel-medium {
            max-width: 600px;
        }

        .product-image {
            max-width: 300px;
            display: inline-block;
            border: solid 1px #ebebeb;
        }

        .carousel-control-prev-icon {
            background-image: url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%23000' viewBox='0 0 8 8'%3E%3Cpath d='M5.25 0l-4 4 4 4 1.5-1.5-2.5-2.5 2.5-2.5-1.5-1.5z'/%3E%3C/svg%3E") !important;
        }

        .carousel-control-next-icon {
            background-image: url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%23000' viewBox='0 0 8 8'%3E%3Cpath d='M2.75 0l-1.5 1.5 2.5 2.5-2.5 2.5 1.5 1.5 4-4-4-4z'/%3E%3C/svg%3E") !important;
        }

        .select2-selection__clear {
            z-index: 1;
        }

        .badge-right {
            position: absolute;
            right: 20px;
        }
    </style>
</head>

<body
    class="ks-navbar-fixed ks-sidebar-default ks-sidebar-position-fixed ks-page-header-fixed ks-theme-primary ks-page-loading">
    <!-- remove ks-page-header-fixed to unfix header -->

    <!-- BEGIN HEADER -->
    <nav class="navbar ks-navbar">
        <!-- BEGIN HEADER INNER -->
        <!-- BEGIN LOGO -->
        <div href="index.html" class="navbar-brand">
            <!-- BEGIN RESPONSIVE SIDEBAR TOGGLER -->
            <a href="#" class="ks-sidebar-toggle"><i class="ks-icon la la-bars" aria-hidden="true"></i></a>
            <a href="#" class="ks-sidebar-mobile-toggle"><i class="ks-icon la la-bars" aria-hidden="true"></i></a>
            <!-- END RESPONSIVE SIDEBAR TOGGLER -->

            <div class="ks-navbar-logo">
                <a href="{{ route('admin.dashboard') }}" class="ks-logo">{{ config('app.name') }}</a>
                <!-- END GRID NAVIGATION -->
            </div>
        </div>
        <!-- END LOGO -->

        <!-- BEGIN MENUS -->
        <div class="ks-wrapper">
            <nav class="nav navbar-nav">
                <!-- BEGIN NAVBAR MENU -->
                <div class="ks-navbar-menu">
                </div>
                <!-- END NAVBAR MENU -->

                <!-- BEGIN NAVBAR ACTIONS -->
                <div class="ks-navbar-actions">

                    <!-- BEGIN NAVBAR USER -->
                    <div class="nav-item dropdown ks-user">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button"
                            aria-haspopup="true" aria-expanded="false">
                            <span class="ks-avatar">
                                <img src="{{ asset('/uploads/photos/'.Auth::user()->photo) }}" width="36" height="36">
                            </span>
                            <span class="ks-info">
                                <span class="ks-name">{{ Auth::user()->name }}</span>
                                <span class="ks-description">{{ '@'.Auth::user()->username }}</span>
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="Preview">
                            <a class="dropdown-item" href="{{ route('home') }}">
                                <span class="la la-sign-in ks-icon" aria-hidden="true"></span>
                                <span>Kembali ke Mall</span>
                            </a>
                            <a class="dropdown-item" href="{{ route('user.logout') }}">
                                <span class="la la-sign-out ks-icon" aria-hidden="true"></span>
                                <span>Logout</span>
                            </a>
                        </div>
                    </div>
                    <!-- END NAVBAR USER -->
                </div>
                <!-- END NAVBAR ACTIONS -->
            </nav>

            <!-- BEGIN NAVBAR ACTIONS TOGGLER -->
            <nav class="nav navbar-nav ks-navbar-actions-toggle">
                <a class="nav-item nav-link" href="#">
                    <span class="la la-user ks-icon ks-open"></span>
                    <span class="la la-close ks-icon ks-close"></span>
                </a>
            </nav>
            <!-- END NAVBAR ACTIONS TOGGLER -->
        </div>
        <!-- END MENUS -->
        <!-- END HEADER INNER -->
    </nav>
    <!-- END HEADER -->

    <div class="ks-page-container ks-dashboard-tabbed-sidebar-fixed-tabs">
        @include('layouts.includes.admin-left-sidebar')

        @yield('content')
    </div>

    <script src="{{ asset('boards/libs/d3/d3.min.js') }}"></script>
    <script src="{{ asset('boards/libs/c3js/c3.min.js') }}"></script>
    <script src="{{ asset('boards/libs/noty/noty.min.js') }}"></script>

    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <script src="{{ asset('boards/libs/popper/popper.min.js') }}"></script>
    <script src="{{ asset('boards/libs/responsejs/response.min.js') }}"></script>
    <script src="{{ asset('boards/libs/loading-overlay/loadingoverlay.min.js') }}"></script>
    <script src="{{ asset('boards/libs/tether/js/tether.min.js') }}"></script>
    <script src="{{ asset('boards/libs/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('boards/libs/jscrollpane/jquery.jscrollpane.min.js') }}"></script>
    <script src="{{ asset('boards/libs/jscrollpane/jquery.mousewheel.js') }}"></script>
    <script src="{{ asset('boards/libs/flexibility/flexibility.js') }}"></script>
    <script src="{{ asset('boards/libs/noty/noty.min.js') }}"></script>
    <script src="{{ asset('boards/libs/velocity/velocity.min.js') }}"></script>
    <script src="{{ asset('boards/libs/flatpickr/flatpickr.min.js') }}"></script>
    <!-- END PAGE LEVEL PLUGINS -->

    <!-- BEGIN THEME LAYOUT SCRIPTS -->
    <script src="{{ asset('boards/assets/scripts/common.min.js') }}"></script>

    <!-- PPOB -->
    <script src="{{ asset('assets/js/ppob.js') }}"></script>
    <!-- END THEME LAYOUT SCRIPTS -->

    <!-- BEGIN VALIDATION SCRIPTS -->
    <script src="{{ asset('boards/libs/jquery-form-validator/jquery.form-validator.min.js') }}"></script>
    <script type="application/javascript">
        (function ($) {
            $(document).ready(function() {
                getBadge();

                // Form Validator
                $.validate({
                    modules : 'location, date, security, file',
                    onModulesLoaded : function() {

                    }
                });
                
                // Image Chooser
                function readURL(input) {
                    if (input.files && input.files[0]) {
                        var reader = new FileReader();

                        reader.onload = function(e) {
                            $('#currentimage').hide();
                            $('#previmage').html('<img src="'+e.target.result+'" width="100%" style="max-width: 300px;" class="mb-3" id="previmage">');
                        }

                        reader.readAsDataURL(input.files[0]);
                    }
                }

                $("#choseimage").change(function() {
                    readURL(this);
                });

                // Image Chooser Two
                function readURL2(input) {
                    if (input.files && input.files[0]) {
                        var reader = new FileReader();

                        reader.onload = function(e) {
                            $('#currentimage2').hide();
                            $('#previmage2').html('<img src="'+e.target.result+'" width="100%" style="max-width: 300px;" class="mb-3" id="previmage">');
                        }

                        reader.readAsDataURL(input.files[0]);
                    }
                }

                $("#choseimage2").change(function() {
                    readURL2(this);
                });

                // Image Chooser Three
                function readURL3(input) {
                    if (input.files && input.files[0]) {
                        var reader = new FileReader();

                        reader.onload = function(e) {
                            $('#currentimage3').hide();
                            $('#previmage3').html('<img src="'+e.target.result+'" width="100%" style="max-width: 300px;" class="mb-3" id="previmage">');
                        }

                        reader.readAsDataURL(input.files[0]);
                    }
                }

                $("#choseimage3").change(function() {
                    readURL3(this);
                });
                
                // Datetime Realtime
                setInterval(function(){
                    var now = new Date();
                    var months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

                    $('.datetime-realtime').html(now.getDate()+' '+months[now.getMonth()]+' '+now.getFullYear()+' '+now.getHours()+':'+now.getMinutes()+':'+now.getSeconds());
                }, 1000);

                // Picker
                $(".calendar").flatpickr();

            });
        })(jQuery);

        var getBadge = () => {
            $.ajax({
                url: window.location.origin + "/office/get-badges",
                type: 'GET',
                dataType: "JSON",
                data: {
                },
                success: function (res) {
                    if (res.code == 200) {
                        hideAllBadges();
                        var { product, merchant, withdraw } = res.items;
                        if (product > 0) {
                            $('#badgeApproval').show();
                            $('#badgeApprovalProduct').text(product);
                            $('#badgeApprovalProduct').show();
                        }
                        if (merchant > 0) {
                            $('#badgeApproval').show();
                            $('#badgeApprovalMerchant').text(merchant);
                            $('#badgeApprovalMerchant').show();
                        }
                        if (withdraw > 0) {
                            $('#badgeWithdraw').text(withdraw);
                            $('#badgeWithdraw').show();
                        }
                    }
                    window.setTimeout('getBadge()', 60000); // refresh every 1 Minutes
                },
                error: function(e){
                    console.log('Error:', e)
                }
            });
        }

        function hideAllBadges(){
            $('#badgeApproval').hide();
            $('#badgeApprovalProduct').hide();
            $('#badgeApprovalMerchant').hide();
            $('#badgeWithdraw').hide();
        }

    </script>
    <!-- END VALIDATION SCRIPTS -->

    <!-- BEGIN DATATABLE SCRIPTS -->
    <script src="{{ asset('boards/libs/datatables-net/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('boards/libs/datatables-net/media/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('boards/libs/datatables-net/extensions/buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('boards/libs/datatables-net/extensions/buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('boards/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('boards/libs/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('boards/libs/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('boards/libs/datatables-net/extensions/buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('boards/libs/datatables-net/extensions/buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('boards/libs/datatables-net/extensions/buttons/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('boards/libs/datatables-net/extensions/responsive/js/dataTables.responsive.min.js') }}">
    </script>
    <script src="{{ asset('boards/libs/select2/js/select2.min.js') }}"></script>

    <!-- END DATATABLE SCRIPTS -->

    <!-- BEGIN EDITOR SCRIPTS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('boards/libs/summernote/summernote.css') }}">
    <!-- original -->
    <link rel="stylesheet" type="text/css"
        href="{{ asset('boards/assets/styles/libs/summernote/summernote.min.css') }}"> <!-- customization -->
    <script src="{{ asset('boards/libs/summernote/summernote.min.js') }}"></script>
    <script type="application/javascript">
        (function ($) {
    $('#ks-summernote-editor-default').summernote({
        placeholder: 'Ketikkan deskripsi konten anda...',
        height: 150,
    });

    $('select.ks-select-placeholder-single').select2({
        placeholder: "Tentukan pilihan anda",
        allowClear: true
    });
})(jQuery);
    </script>
    <!-- END EDITOR SCRIPTS -->

    <script src="{{ asset('scripts/jquery.chained.min.js') }}"></script>
    <script src="{{ asset('scripts/jquery.chained.remote.min.js') }}"></script>
    <script type="application/javascript">
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
    </script>

    <div class="ks-mobile-overlay"></div>

    @yield('script')
</body>

</html>
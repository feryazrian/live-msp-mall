@extends('layouts.admin')

@section('content')

    <div class="ks-column ks-page">
        <div class="ks-page-header">
            <section class="ks-title">
                <h3>{{ $pageTitle }}</h3>
            </section>
        </div>

        @switch(Auth::user()->role_id)
            @case(2)
                
                @break
            @default
            <div class="ks-page-content">
                <div class="ks-page-content-body">
                    <div class="ks-dashboard-tabbed-sidebar">
                        <div class="ks-dashboard-tabbed-sidebar-widgets">
    
                            <div class="row">
                                <div class="col-lg-3 col-md-6">
                                    <div class="card ks-widget-payment-simple-amount-item ks-green">
                                        <div class="payment-simple-amount-item-icon-block">
                                            <span class="ks-icon-combo-chart ks-icon"></span>
                                        </div>
    
                                        <div class="payment-simple-amount-item-body">
                                            <div class="payment-simple-amount-item-amount">
                                                <span class="ks-amount">{{ $productToday }}</span>
                                                <span class="ks-amount-icon ks-icon-circled-up-right"></span>
                                            </div>
                                            <div class="payment-simple-amount-item-description">
                                                Produk Baru
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="card ks-widget-payment-simple-amount-item ks-green">
                                        <div class="payment-simple-amount-item-icon-block">
                                            <span class="la la-area-chart ks-icon"></span>
                                        </div>
    
                                        <div class="payment-simple-amount-item-body">
                                            <div class="payment-simple-amount-item-amount">
                                                <span class="ks-amount">{{ $userToday }}</span>
                                                <span class="ks-amount-icon ks-icon-circled-up-right"></span>
                                            </div>
                                            <div class="payment-simple-amount-item-description">
                                                Pengguna Baru
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="card ks-widget-payment-simple-amount-item ks-pink">
                                        <div class="payment-simple-amount-item-icon-block">
                                            <span class="la la-cube ks-icon"></span>
                                        </div>
    
                                        <div class="payment-simple-amount-item-body">
                                            <div class="payment-simple-amount-item-amount">
                                                <span class="ks-amount">{{ $productTotal }}</span>
                                            </div>
                                            <div class="payment-simple-amount-item-description">
                                                Total Produk
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="card ks-widget-payment-simple-amount-item ks-pink">
                                        <div class="payment-simple-amount-item-icon-block">
                                            <span class="ks-icon-user ks-icon"></span>
                                        </div>
    
                                        <div class="payment-simple-amount-item-body">
                                            <div class="payment-simple-amount-item-amount">
                                                <span class="ks-amount">{{ $userTotal }}</span>
                                            </div>
                                            <div class="payment-simple-amount-item-description">
                                                Total Pengguna
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
    
                            <div class="row">
                                <div class="col-lg-3 col-md-6">
                                    <div class="card ks-widget-payment-price-ratio ks-green">
                                        <div class="ks-price-ratio-title">
                                            Total Kategori
                                        </div>
                                        <div class="ks-price-ratio-amount">{{ $categoryTotal }}</div>
                                        <div class="ks-price-ratio-progress">
                                            <span class="ks-icon ks-icon-circled-up-right"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="card ks-widget-payment-price-ratio ks-purple">
                                        <div class="ks-price-ratio-title">
                                            Total Halaman
                                        </div>
                                        <div class="ks-price-ratio-amount">{{ $pageTotal }}</div>
                                        <div class="ks-price-ratio-progress">
                                            <span class="ks-icon ks-icon-circled-up-right"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="card ks-widget-payment-price-ratio ks-yellow">
                                        <div class="ks-price-ratio-title">
                                            Total Slide
                                        </div>
                                        <div class="ks-price-ratio-amount">{{ $slideTotal }}</div>
                                        <div class="ks-price-ratio-progress">
                                            <span class="ks-icon ks-icon-circled-up-right"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="card ks-widget-payment-price-ratio ks-blue">
                                        <div class="ks-price-ratio-title">
                                            Pengaturan
                                        </div>
                                        <div class="ks-price-ratio-amount">{{ $optionTotal }}</div>
                                        <div class="ks-price-ratio-progress">
                                            <span class="ks-icon ks-icon-circled-up-right"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        <!--
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="card ks-card-widget ks-widget-chart-orders">
                                        <h5 class="card-header">
                                            Orders
    
                                            <div class="ks-controls">
                                                <a href="#" class="ks-control-link">January 2017</a>
                                            </div>
                                        </h5>
                                        <div class="card-block">
                                            <div class="ks-chart-orders-block"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        -->
                            
                        </div>
                    </div>
                </div>
            </div>
        @endswitch

    </div>

<!--
    <script src="{{ asset('boards/libs/c3js/c3.min.js') }}"></script>
    <script type="application/javascript">
    (function ($) {
        $(document).ready(function () {
            c3.generate({
                bindto: '.ks-chart-orders-block',
                data: {
                    columns: [
                        ['Revenue', 150, 200, 220, 280, 400, 160, 260, 400, 300, 400, 500, 420, 500, 300, 200, 100, 400, 600, 300, 360, 600],
                        ['Profit', 350, 300,  200, 140, 200, 30, 200, 100, 400, 600, 300, 200, 100, 50, 200, 600, 300, 500, 30, 200, 320]
                    ],
                    colors: {
                        'Revenue': '#f88528',
                        'Profit': '#81c159'
                    }
                },
                point: {
                    r: 5
                },
                grid: {
                    y: {
                        show: true
                    }
                }
            });
        });
    })(jQuery);
    </script>
-->
@endsection
@extends('layouts.app')

@section('title')
Transaksi Saya | {{ config('app.name') }}
@endsection

@section('description')
-
@endsection

@section('content')

        <div class="container-wrap">
            <div class="container">
                <div class="dashboard-timeline">
                    <div class="content tab tab-transaction">

                        <div class="tabbable">
                            <ul class="nav nav-custom">
                                <li @if($tab=="sell") class="active" @endif>
                                    <a href="{{ url('/transaction/sell') }}">
                                        Transaksi Penjualan
                                        @if ($sellCount>0)
                                        <span>{{ $sellCount }}</span>
                                        @endif
                                    </a>
                                </li>
                                <li @if($tab=="buy") class="active" @endif>
                                    <a href="{{ url('/transaction/buy') }}">
                                        Transaksi Pembelian
                                        @if ($buyCount>0)
                                        <span>{{ $buyCount }}</span>
                                        @endif
                                    </a>
                                </li>
                                <li @if($tab=="review") class="active" @endif>
                                    <a href="{{ url('/transaction/review') }}">Ulasan Produk</a>
                                </li>
                            </ul>
                            <div class="tab-content">

                                @if($tab=='sell')
                                <div class="tab-pane active" id="tab1">
                                    @include('tracking.list-sell')
                                </div>
                                @endif

                                @if($tab=='buy')
                                <div class="tab-pane active" id="tab2">
                                    @include('tracking.list-buy')
                                </div>
                                @endif

                                @if($tab=='review')
                                <div class="tab-pane review active" id="tab3">
                                    @include('tracking.list-review')
                                </div>
                                @endif

                            </div> <!-- /tab-content -->
                        </div>

                    </div>
                </div>
            </div> <!-- /container -->
        </div> <!-- /container960 -->

@endsection

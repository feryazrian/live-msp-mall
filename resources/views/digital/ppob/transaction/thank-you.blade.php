@extends('layouts.app')

@section('title'){{ str_replace('[TITLE]', $pageTitle, $seo_title) }}@endsection

@section('description'){{ str_replace('[TITLE]', $pageTitle, $seo_description) }}@endsection

@section('content')
    <section class="page-section pb-5">
        <div class="container py-4 pb-5">
            <div class="row">

                <div class="col-lg-3 d-none d-lg-block py-4">
                    <div class="sidebar">
                        @include('layouts.includes.sidenav-mobile')
                    </div>
                </div>

                <div class="col-md-12 col-lg-9 page-content py-4">
                    <div class="page-title border-bottom d-block text-center">Order Detail</div>
                    <div class="tracking my-3">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-12">
                                    <p class="m-0">Status</p>
                                    @switch($data->status)
                                        @case(0)
                                            <b class="m-0 text-secondary">Transaksi Pending</b>
                                            @break
                                        @case(1)
                                            <b class="m-0 text-success">Transaksi Berhasil</b>
                                            @break
                                        @case(2)
                                            <b class="m-0 text-danger">Transaksi Gagal</b>
                                            @break
                                        @default
                                            <b class="m-0 text-muted">Transaksi Dibatalkan sistem</b>
                                    @endswitch
                                </div>
                            </div>
                        </div>
<hr>
                        <div class="container">
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="m-0" >Tanggal Transaksi</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="m-0 pull-right" >{{ $orderDate }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="m-0" >Kategori Produk</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="m-0 pull-right" >{{ ucfirst($type) }}</p>
                                </div>
                            </div>
                        </div>
<hr>
                        @if ($data->status == 1)
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-6">
                                        <b>{{ $data->reff_id }}</b>
                                    </div>
                                    <div class="col-md-6">
                                        <a href="/digital/{{$type}}/invoice/{{$data->reff_id}}" class="pull-right text-success">Lihat Detail</a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="container">
                            <div class="row mt-3">
                                <div class="col-md-12"><p><b>Detail Pembelian</b></p></div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <span>Jenis Layanan</span>
                                </div>
                                <div class="col-md-8">
                                    <span>
                                        <b>{{ ucfirst($type) }}</b> 
                                        @if(strpos(strtolower($operator->slug), 'smartfren') !== false)
                                            <img src="/assets/digital/smartfren.png" class="prefix-img" style="background-color:#db203f ;border-radius:3px;" />
                                        @endif
                                        @if(strpos(strtolower($operator->slug), 'axis') !== false)
                                            <img src="/assets/digital/axis.png" class="prefix-img"/>
                                        @endif
                                        @if(strpos(strtolower($operator->slug), 'xl') !== false)
                                            <img src="/assets/digital/xl.png" class="prefix-img"/>
                                        @endif
                                        @if(strpos(strtolower($operator->slug), 'tri') !== false)
                                            <img src="/assets/digital/three.png" class="prefix-img"/>
                                        @endif
                                        @if(strpos(strtolower($operator->slug), 'indosat') !== false)
                                            <img src="/assets/digital/indosat.png" class="prefix-img"/>
                                        @endif
                                        @if(strpos(strtolower($operator->slug), 'telkomsel') !== false)
                                            <img src="/assets/digital/telkomsel.png" class="prefix-img"/>
                                        @endif
                                    </span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <span>Nomor</span>
                                </div>
                                <div class="col-md-8">
                                    <span><b>{{ $data->cust_number }}</b></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <span>Nominal</span>
                                </div>
                                <div class="col-md-8">
                                    <span><b>{{ $product->pulsa_nominal }}</b></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <span>Harga</span>
                                </div>
                                <div class="col-md-8">
                                    <span><b>Rp {{ number_format($data->price,0,',','.') }}</b></span>
                                </div>
                            </div>
                            @if ($promo)
                                <div class="row">
                                    @if($promo->promo->discount_type_id === 1)
                                        <div class="col-md-4">
                                            <span>Cash Back Life Point</span>
                                        </div>
                                        <div class="col-md-8">
                                            <span><b class="text-danger">Rp {{ number_format($promo->price,0,',','.') }}</b> <br> Kode Promo: <span class="text-danger">{{$promo->code}}</span></span>
                                        </div>
                                    @else
                                        <div class="col-md-4">
                                            <span>Potongan Discount </span>
                                        </div>
                                        <div class="col-md-8">
                                            <span><b class="text-danger">-Rp {{ number_format($promo->price,0,',','.') }}</b> <br> Kode Promo: <span class="text-danger">{{$promo->code}}</span></span>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <div class="container my-4">
                            <div class="row">
                                <div class="col-md-6"><p class="m-0"><b>Total Pembayaran</b></p></div>
                                <div class="col-md-6"><p class="m-0 text-primary pull-right"><b>Rp {{ number_format($data->total,0,',','.') }}</b></p></div>
                            </div>
                        </div>
                        <div class="container my-5">
                            <div class="text-center">
                                <a href="/digital/{{$type}}" class="btn btn-primary btn-rounded px-5">Beli Lagi</a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <!-- Currency JS -->
    <script src="https://unpkg.com/currency.js@1.2.1/dist/currency.min.js"></script>
    <script>
    </script>
@endsection
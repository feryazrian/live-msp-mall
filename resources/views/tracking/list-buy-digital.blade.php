@extends('layouts.app')

@section('title'){{ str_replace('[TITLE]', $pageTitle, $seo_title) }}@endsection

@section('description'){{ str_replace('[TITLE]', $pageTitle, $seo_description) }}@endsection

@section('content')

<section class="page-section">
    <div class="container">

        <div class="row">

            <div class="col-lg-3 d-none d-lg-block py-4">
                <div class="sidebar">
                    @include('layouts.includes.sidenav-mobile')
                </div>
            </div>

            <div class="col-md-12 col-lg-9 page-content pb-4">
                
                <div class="smarttab">
                    <div class="scroll">
                        <ul class="nav nav-tabs transaction bg-white">
                            <li><a href="{{ route('transaction.buy') }}">Pembelian Produk</a></li>
                            <li><a href="{{ route('transaction.buy.voucher') }}">Pembelian E-Voucher</a></li>
                            <li><a href="{{ route('transaction.buy.digital') }}" class="active">Topup & Tagihan</a></li>
                        </ul>
                    </div>
                </div>
                
                <div class="transaction-lines col-md-12 mb-5 pb-5">

                    @if (session('status'))
                        <div class="alert alert-success">
                            <button class="close fui-cross" data-dismiss="alert"></button>
                            {{ session('status') }}
                        </div>
                    @endif
                
                    @if (session('warning'))
                        <div class="alert alert-danger">
                            <button class="close fui-cross" data-dismiss="alert"></button>
                            {{ session('warning') }}
                        </div>
                    @endif
                    
                    @foreach ($transaction as $item)
                        <div class="col-md-12 p-4 border-bottom">
                            <div class="text-right"><span>{{ $item->order_date }}</span></div>
                            <div class="row">
                                <div class="col-md-6 text-left">
                                    <div class="text-dark"><b>{{ $item->type_name }}</b></div>
                                </div>
                                <div class="col-md-6 text-right">
                                    <div>Total Pembayaran <b class="text-primary">{{ 'Rp '.number_format($item->total,0,',','.') }}</b></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 text-left">
                                    <div class="text-dark">(#{{ $item->transaction_id }})</div>
                                </div>
                                <div class="col-md-6 text-right">
                                    <div><a href="/digital/{{$item->type_slug}}/invoice/{{$item->reff_id}}" class="pull-right text-info">Lihat Detail Tagihan</a></div>
                                </div>
                            </div>
                            <div class="row px-3 pt-3">
                                <div class="col-md-9">
                                    <div class="row">
                                        <div class="col-md-3">
                                            @if(strpos(strtolower($item->opr_slug), 'smartfren') !== false || strpos(strtolower($item->opr_slug), 'smart') !== false)
                                                <img src="/assets/digital/smartfren.png" class="img-opr" style="background-color:#db203f ;border-radius:3px;" />
                                            @endif
                                            @if(strpos(strtolower($item->opr_slug), 'axis') !== false)
                                                <img src="/assets/digital/axis.png" class="img-opr"/>
                                            @endif
                                            @if(strpos(strtolower($item->opr_slug), 'xl') !== false)
                                                <img src="/assets/digital/xl.png" class="img-opr"/>
                                            @endif
                                            @if(strpos(strtolower($item->opr_slug), 'tri') !== false)
                                                <img src="/assets/digital/three.png" class="img-opr"/>
                                            @endif
                                            @if(strpos(strtolower($item->opr_slug), 'indosat') !== false)
                                                <img src="/assets/digital/indosat.png" class="img-opr"/>
                                            @endif
                                            @if(strpos(strtolower($item->opr_slug), 'telkomsel') !== false)
                                                <img src="/assets/digital/telkomsel.png" class="img-opr"/>
                                            @endif
                                        </div>
                                        <div class="col-md-9">
                                            <div>{{ $item->opr_name }} - {{ $item->priceDetail->pulsa_nominal }}</div>
                                            <div><span class="text-primary">{{ 'Rp '.number_format($item->price,0,',','.') }}</span> - {{ $item->cust_number }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 text-right">
                                    <div>Status</div>
                                    <div>
                                        @switch($item->status)
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
                        </div>
                    @endforeach
                    <div class="text-center">
                        {{ $transaction->links() }}
                    </div>
                </div>
            </div>

        </div>

    </div>
</section>

@endsection
@extends('layouts.app')

@section('title'){{ str_replace('[TITLE]', $pageTitle, $seo_title) }}@endsection

@section('description'){{ str_replace('[TITLE]', $pageTitle, $seo_description) }}@endsection

@section('content')

<section class="bg-white py-4">
    <div class="container">

        <div class="row">

            <div class="col-lg-3 d-none d-lg-block">
                <div class="sidebar">
					@include('layouts.includes.sidenav-mobile')
                </div>
            </div>

            <div class="transaction status col-md-12 col-lg-9">

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

                <div class="notif my-4 pb-5">
                    <div class="head">
                        <div class="title text-info">Menunggu Konfirmasi</div>
                        <div class="code my-1">Kode Transaksi <b>{{ $transactionCode }}</b></div>
                    </div>
                    <div class="caption">{!! $transactionMessage !!}</div>
                    <div class="price my-4">
                        <div class="subtitle">Total Transaksi</div>
                        <div class="title">{{ 'Rp '.number_format($transactionTotal,0,',','.') }}</div>
                    </div>
                    <div class="text-info"><b>Transaksi sedang di proses, menunggu konfirmasi dari admin maksimal (H+1 Pukul 16.00 WIB)</b></div>
                    <div class="caption">Terima kasih telah berbelanja melalui {{ config('app.name') }}</div>
                </div>

            </div>

        </div>

    </div>
</section>

@endsection
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
                        <div class="title text-danger">Transaksi Ditolak</div>
                        <div class="code my-1">Kode Transaksi <b>{{ $transactionCode }}</b></div>
                    </div>
                    <div class="caption">{!! $transactionMessage !!}</div>
                    <div class="price my-4">
                        <div class="subtitle">Total Transaksi</div>
                        <div class="title">{{ 'Rp '.number_format($transactionTotal,0,',','.') }}</div>
                    </div>
                    <div class="caption">Harap hubungi Administrator {{ config('app.name') }} untuk informasi lebih lanjut</div>
                </div>

            </div>

        </div>

    </div>
</section>

@endsection

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

                <!--<div class="page-title mb-4">{{ $pageTitle }}</div>-->
                
                <div class="smarttab">
                    <div class="scroll">
                        <ul class="nav nav-tabs transaction bg-white">
                            <li><a href="{{ route('transaction.buy') }}">Pembelian Produk</a></li>
                            <li><a href="{{ route('transaction.buy.voucher') }}" class="active">Pembelian E-Voucher</a></li>
                            <li><a href="{{ route('transaction.buy.digital') }}">Topup & Tagihan</a></li>
                        </ul>
                    </div>
                </div>
                
                <div class="transaction-lines mb-5 pb-5">

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
                
                @foreach ($transactionVoucher as $transaction)
                    <a class="transaction-line" href="{{ route('voucher.transaction', ['id' => $transaction->id]) }}">
                        <div class="content d-table w-100">
                            <div class="d-table-cell text-left">
                                <div class="title">{{ '#'.$transaction->transaction_id }}</div>
                                <div>{{ $transaction->updated_at }}</div>
                            </div>
                            <div class="d-table-cell text-right">
                                <div class="price">{{ 'Rp '.number_format($transaction->payment->gross_amount,0,',','.') }}</div>
                                <div><span>{{ $transaction->unit  }}</span> Unit Voucher</div>
                            </div>
                        </div>

                        @if ($transaction->status == '0')
                        <div class="status text-center bg-info text-white">
                            Menunggu Penyelesaian Pembayaran
                        </div>
                        @endif

                        @if ($transaction->status == '1')
                        <div class="status text-center">
                            Transaksi Selesai
                        </div>
                        @endif

                        @if ($transaction->status == '7')
                        <div class="status text-center bg-danger text-white">
                            Transaksi Dibatalkan Sistem
                        </div>
                        @endif

                    </a>
                @endforeach
                
                </div>
                
            </div>

        </div>

    </div>
</section>

@endsection
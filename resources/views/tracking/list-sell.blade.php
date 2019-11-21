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
                            <li><a href="{{ route('transaction.sell') }}" class="active">Penjualan Produk</a></li>
                            <li><a href="{{ route('transaction.sell.voucher') }}">Penjualan E-Voucher</a></li>
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

                @foreach ($transactionProduct as $transaction)
                    <a class="transaction-line" href="{{ route('transaction.detail', ['id' => $transaction->id]) }}">
                        <div class="content d-table w-100">
                            <div class="d-table-cell text-left">
                                <div class="title">{{ $transaction->transaction_id.'#'.$transaction->user_id }}</div>
                                <div>{{ $transaction->transaction->updated_at }}</div>
                            </div>
                            <div class="d-table-cell text-right">
                                <div class="price">{{ 'Rp '.number_format($transaction->sellerprice,0,',','.') }}</div>
                                <div><span>{{ $transaction->sellerunit  }}</span> Unit Produk</div>
                            </div>
                        </div>

                        @if ($transaction->status == '0')
                        <div class="status text-center bg-info text-white">
                            Menunggu Penyelesaian Pembayaran
                        </div>
                        @endif

                        @if ($transaction->status == '1')
                        <div class="status text-center bg-success text-white">
                            Menunggu Konfirmasi Penjual
                        </div>
                        @endif

                        @if ($transaction->status == '2')
                        <div class="status text-center bg-success text-white">
                            Menunggu Konfirmasi Penerimaan
                        </div>
                        @endif

                        @if ($transaction->status == '3')
                        <div class="status text-center bg-danger text-white">
                            Dalam Diskusi Komplain
                        </div>
                        @endif

                        @if ($transaction->status == '4')
                        <div class="status text-center bg-success text-white">
                            Menunggu Ulasan Penjual
                        </div>
                        @endif

                        @if ($transaction->status == '5')
                        <div class="status text-center">
                            Transaksi Selesai
                        </div>
                        @endif

                        @if ($transaction->status == '6')
                        <div class="status text-center bg-danger text-white">
                            Transaksi Dibatalkan Penjual
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
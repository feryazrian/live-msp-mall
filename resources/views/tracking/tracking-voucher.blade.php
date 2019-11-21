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

            <div class="col-md-12 col-lg-9 page-content py-4">

                <div class="page-title mb-4 d-block">{{ $pageTitle }}</div>
                
                <div class="tracking mb-5 pb-5">

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

                    <div class="detail">

                        <div class="product-info table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <td style="min-width:130px;">Nama Produk</td>
                                        <td class="text-right" style="min-width:130px;">Harga</td>
                                        <td class="text-right" style="min-width:80px;">Jumlah</td>
                                        <td class="text-right" style="min-width:130px;">Nominal</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="d-inline-block mr-1">
                                                {{ $transaction->name }}
                                            </div>
                                        </td>
                                        <td class="text-right">
                                            {{ 'Rp '.number_format(($transaction->price / $transaction->unit),0,',','.') }}
                                        </td>
                                        <td class="text-right">{{ $transaction->unit }}</td>
                                        <td class="text-right">
                                            {{ 'Rp '.number_format($transaction->price,0,',','.') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><b>Total Tagihan</b></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-right">
                                            {{ 'Rp '.number_format($transaction->price,0,',','.') }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="user-info">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="title">Pembeli</div>
                                            
                                            <div class="user-card d-table">
                                                <div class="d-table-cell align-middle">
                                                    <a href="{{ route('user.detail', ['username' => $transaction->user->username]) }}" class="btn user">
                                                        <img src="{{ asset('uploads/photos/'.$transaction->user->photo) }}">
                                                    </a>
                                                </div>
                                                <div class="d-table-cell align-middle pr-3">
                                                    <div class="name">{{ $transaction->user->name }}</div>
                                                    
                                                @if (!empty($transaction->user->place_birth))
                                                    <div class="location">{{ $transaction->user->kabupaten->name }}</div>
                                                @endif
                                                </div>
                                            </div>
                                        </td>
                                        @if (!empty($transaction->product))
                                        <td>
                                            <div class="title">Penjual</div>
                                            
                                            <div class="user-card d-table">
                                                <div class="d-table-cell align-middle">
                                                    <a href="{{ route('user.detail', ['username' => $transaction->product->user->username]) }}" class="btn user">
                                                        <img src="{{ asset('uploads/photos/'.$transaction->product->user->photo) }}">
                                                    </a>
                                                </div>
                                                <div class="d-table-cell align-middle pr-3">
                                                    <div class="name">{{ $transaction->product->user->name }}</div>
                                                    
                                                @if (!empty($transaction->product->user->place_birth))
                                                    <div class="location">{{ $transaction->product->user->kabupaten->name }}</div>
                                                @endif
                                                </div>
                                            </div>
                                        </td>
                                        @endif
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    
                        @if ($status == '1')
                        <div class="product-info table-responsive mt-4">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <td style="min-width:130px;">Kode E-Voucher</td>
                                        <td class="text-center" style="min-width:130px;">Status</td>
                                        <td class="text-center" style="min-width:130px;">Waktu Klaim</td>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($vouchers as $voucher)
                                    <tr>
                                        <td>
                                            <div class="d-inline-block mr-1">
                                                {{ $voucher->code }}
                                            </div>
                                        </td>
                                        <td class="text-center">{!! $voucher->status !!}</td>
                                        <td class="text-center">{{ $voucher->timestamp }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endif

                        <div class="tracking-status text-center mb-4">
                            <div class="caption">
                            @if(date('Y-m-d H:i:s') > $transaction->voucher_expired)
                                <div><b>E-Voucher Telah Kadaluarsa</b> pada <b>{{ $transaction->voucher_expired }}</b></div>
                            @else
                                <div>Harap lakukan Klaim E-Voucher sebelum <b class="text-brand">{{ $transaction->voucher_expired }}</b></div>
                            @endif
                            </div>
                        </div>

                        @if ($access == 1)
                        @if(date('Y-m-d H:i:s') < $transaction->voucher_expired)
                        <div class="tracking-status text-center">
                            <div class="caption mb-3">
                                <div><b>Klaim E-Voucher</b></div>
                                <div>Pilih E-Voucher yang akan di Klaim</div>
                            </div>
                                            
                            <form method="post" role="form" action="{{ route('voucher.claim') }}">

                                {{ csrf_field() }}

                                <input type="hidden" name="transaction" value="{{ $transaction->id }}" />

                                <div class="form-group mb-2 {{ $errors->has('code') ? ' has-error' : '' }}">
                                    <input type="text" name="code" class="form-control" id="code" aria-describedby="code" placeholder="Ketikkan Kode E-Voucher" required>
                                
                                @if ($errors->has('code'))
                                    <small id="code" class="form-text text-danger">
                                        {{ $errors->first('code') }}
                                    </small>
                                @endif
                                </div>

                                <button type="submit" class="btn btn-rounded btn-primary btn-block">Klaim E-Voucher</button>
                            </form>
                        </div>
                        @endif
                        @endif
                        
                        @if ($access == 2)
                        <div class="tracking-status text-center">
                            <div class="title">Pembaruan Status Transaksi
                                <span>{{ $transaction->updated_at->diffForHumans() }}</span>
                            </div>

                            @if ($status == '1')
                                @include('tracking.tracking-complete')
                            @endif

                            @if ($status >= '7')
                                @include('tracking.tracking-cancel')
                            @endif
                        </div>
                        @endif
                    
                    </div>
                </div>
                
            </div>

        </div>

    </div>
</section>

@endsection
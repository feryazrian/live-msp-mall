@extends('layouts.app')

@section('title'){{ str_replace('[TITLE]', $pageTitle, $seo_title) }}@endsection

@section('description'){{ str_replace('[TITLE]', $pageTitle, $seo_description) }}@endsection

@section('content')

<section class="page-section balance">
    <div class="container">

        <div class="row">

            <div class="col-lg-3 d-none d-lg-block py-4">
                <div class="sidebar">
                    @include('layouts.includes.sidenav-mobile')
                </div>
            </div>

            <div class="col-md-12 col-lg-9 page-content py-4">

                <div class="page-title">{{ $pageTitle }}</div>

            <!--
                <div class="smarttab">
                    <div class="scroll">
                        <ul class="nav nav-tabs wallet">
                            <li><a href="{{ route('balance') }}" class="active">Mons Wallet</a></li>
                            <li><a href="{{ route('coupon') }}">Kupon Transaksi</a></li>
                            <li><a href="{{ route('coupon') }}">Kupon Luck Draw</a></li>
                            <li><a href="{{ route('balance.deposit') }}">Deposit</a></li>
                            <li><a href="{{ route('balance.withdraw') }}">Withdraw</a></li>
                        </ul>
                    </div>
                </div>
            -->

                <div class="page-list my-5 pb-5">

                    @if (session('status'))
                        <div class="alert alert-success">
                            <button type="button" class="close fui-cross" data-dismiss="alert"></button> {{ session('status') }}
                        </div>
                    @endif
    
                    @if (session('warning'))
                        <div class="alert alert-danger">
                            <button type="button" class="close fui-cross" data-dismiss="alert"></button> {{ session('warning') }}
                        </div
                        >
                    @endif
                    <div class="point detail">
                        <div class="title">Life Point Saya</div>
                        <div class="point">{{ number_format($lifePoint,0,',','.') }}</div>
                    </div>
                    <br>
                    <br>
                    @if (!empty(Auth::user()->api_msp))
                    <div class="point detail">
                        <div class="title">MSP Point Saya</div>
                        <div class="point">{{ number_format($point,0,',','.') }}</div>
                    </div>
                    <div class="button mt-1 mb-5">
                        <a href="{{ route('point.topup') }}" class="btn btn-rounded btn-primary mx-1 px-4">Top Up MSP</a>
                    </div>
                    @endif

                    <div class="point detail">
                        <div class="title">Saldo {{ $pageTitle }} Saya</div>
                        <div class="point">{{ number_format($myBalance,0,',','.') }}</div>
                    </div>

                    <div class="row mx-4 text-center">
                        {{-- <div class="col-sm-6">
                            <a href="{{ route('coupon.point') }}" class="btn btn-rounded btn-primary btn-block m-1 px-4">Kupon Luck Draw</a>
                        </div> --}}
                        @if (Auth::user()->merchant_id != null)
                            <div class="col-sm-4">
                        @else
                            <div class="col-sm-6">
                        @endif
                            <a href="{{ route('coupon') }}" class="btn btn-rounded btn-outline-primary btn-block m-1 px-4">Kupon Transaksi</a>
                        </div>

                        @if (Auth::user()->merchant_id != null)
                        <div class="col-sm-4">
                            <a href="{{ route('balance.withdraw') }}" class="btn btn-rounded btn-outline-primary btn-block m-1 px-4">Withdraw</a>
                        </div>
                        @endif
                        @if (Auth::user()->merchant_id != null)
                            <div class="col-sm-4">
                        @else
                            <div class="col-sm-6">
                        @endif
                            <a href="{{ route('balance.deposit') }}" class="btn btn-rounded btn-primary btn-block m-1 px-4">Deposit</a>

                        </div>
                    </div>
                    <br>
                    {{-- <div class="button mt-1 mb-5">
                        @if (Auth::user()->merchant_id != null)
                            <a href="{{ route('balance.withdraw') }}" class="btn btn-rounded btn-outline-primary m-1 px-4">Withdraw</a>
                        @endif
                        <a href="{{ route('balance.deposit') }}" class="btn btn-rounded btn-primary m-1 px-4">Deposit</a>
                    </div> --}}

                @if (!empty(Auth::user()->api_msp))
                @if(!empty($pointTopup))
                    <div class="table mb-4">
                        <div class="table-title text-success mb-2">Riwayat Topup</div>
                        <table class="table table-striped table-responsive">
                            <thead>
                                <tr>
                                    <td style="min-width:200px;">Waktu</td>
                                    <td style="min-width:170px;">Mutasi</td>
                                    <td style="min-width:180px;">ID Transaksi</td>
                                    <td class="w-100">Status</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pointTopup as $point)
                                <tr>
                                    <td>{{ $point->created_at }}</td>

                                    <td>
                                    @if ($point->status == 7)
                                        <strike>
                                        <span>
                                    @else
                                        <span class="text-success">
                                    @endif

                                    @if (!empty($point->payment))
                                        +{{ 'Rp '.number_format($point->payment->gross_amount,0,',','.') }}</span>
                                    @else
                                        </span>
                                        -
                                    @endif

                                    @if ($point->status == 7)
                                        </strike>
                                    @endif
                                    </td>

                                    <td><a href="{{ route('point.transaction', ['id' => $point->id]) }}">{{ $point->transaction_id }}</a></td>

                                    <td>
                                        @if ($point->status == 1)
                                        Transaksi Selesai
                                        @elseif ($point->status == 7)
                                        Transaksi Dibatalkan
                                        @else
                                        Menunggu Penyelesaian Pembayaran
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
                @endif

                @if(!empty($balanceDeposit))
                    <div class="table mb-4">
                        <div class="table-title text-success mb-2">Riwayat Deposit</div>
                        <table class="table table-striped table-responsive">
                            <thead>
                                <tr>
                                    <td style="min-width:200px;">Waktu</td>
                                    <td style="min-width:170px;">Mutasi</td>
                                    <td style="min-width:180px;">ID Transaksi</td>
                                    <td class="w-100">Status</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($balanceDeposit as $balance)
                                <tr>
                                    <td>{{ $balance->created_at }}</td>

                                    <td>
                                    @if ($balance->status == 7)
                                        <strike>
                                        <span>
                                    @else
                                        <span class="text-success">
                                    @endif
                                        +{{ 'Rp '.number_format($balance->payment->gross_amount,0,',','.') }}</span>
                                    @if ($balance->status == 7)
                                        </strike>
                                    @endif
                                    </td>

                                    <td><a href="{{ route('balance.transaction', ['id' => $balance->id]) }}">{{ $balance->transaction_id }}</a></td>

                                    <td>
                                        @if ($balance->status == 1)
                                        Transaksi Selesai
                                        @elseif ($balance->status == 7)
                                        Transaksi Dibatalkan
                                        @else
                                        Menunggu Penyelesaian Pembayaran
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                @if(!empty($balancePending))
                    <div class="table mb-4">
                        <div class="table-title text-warning mb-2">Riwayat Withdraw</div>
                        <table class="table table-striped table-responsive">
                            <thead>
                                <tr>
                                    <td style="min-width:200px;">Waktu</td>
                                    <td style="min-width:170px;">Mutasi</td>
                                    <td style="min-width:180px;">Keterangan</td>
                                    <td>Detail</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($balancePending as $balance)
                                <tr>
                                    <td>{{ $balance->created_at }}</td>

                                    @if ($balance->type == 1)
                                    <td>
                                        <span class="text-success">+{{ 'Rp '.number_format($balance->balance,0,',','.') }}</span>
                                    </td>
                                    @endif

                                    @if ($balance->type == 0)
                                    <td>
                                        <span class="text-danger">-{{ 'Rp '.number_format($balance->balance,0,',','.') }}</span>
                                    </td>
                                    @endif

                                    <td>{{ $balance->notes }}</td>

                                    <td>
                                    @if ($balance->type == 0)
                                        Pencairan ke Rekening <b>{{ $balance->withdraw->bank_name }}</b> No. <b>{{ $balance->withdraw->bank_number }}</b> a.n <b>{{ $balance->withdraw->bank_holder }}</b>
                                    @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                    <div class="table">
                        <div class="table-title mb-2 text-success">Riwayat Penggunaan Saldo</div>
                        <table class="table table-striped table-responsive">
                            <thead>
                                <tr>
                                    <td style="min-width:200px;">Waktu</td>
                                    <td style="min-width:160px;">Mutasi</td>
                                    <td style="min-width:160px;">Saldo</td>
                                    <td style="min-width:140px;">ID Transaksi</td>
                                    <td>Keterangan</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($balanceSummary as $balance)
                                <tr>
                                    <td>{{ $balance->created_at }}</td>

                                    @if ($balance->type == 1 || $balance->type == 2)
                                    <td>
                                        <span class="text-success">+{{ 'Rp '.number_format($balance->balance,0,',','.') }}</span>
                                    </td>
                                    @endif

                                    @if ($balance->type == 0 || $balance->type == 3)
                                    <td>
                                        <span class="text-danger">-{{ 'Rp '.number_format($balance->balance,0,',','.') }}</span>
                                    </td>
                                    @endif


                                    <td>{{ 'Rp '.number_format($balance->growth,0,',','.') }}</td>

                                    <td>
                                    @if (!empty($balance->transaction))
                                        {{--  <a href="{{ route('transaction.detail', ['id' => $balance->transaction->transaction->product[0]->id]) }}">  --}}
                                        @if (!empty($balance->voucher))
                                            <a href="{{ route('voucher.transaction', ['id' => $balance->voucher_id]) }}">
                                                {{ '#'.$balance->voucher->transaction_id }}
                                            </a>
                                        @elseif (!empty($balance->ppob))
                                            <a href="{{ route('digital.thank_you', [ 'type' => $balance->ppob->type->slug, 'inv' => $balance->ppob->transaction_id]) }}">
                                                {{ '#'.$balance->ppob->transaction_id }}
                                            </a>
                                        @else
                                            <a href="{{ route('transaction.detail', ['id' => $balance->transaction->transaction->id]) }}">
                                                {{ $balance->transaction->transaction_id.'#'.$balance->transaction->user_id }}
                                            </a>
                                        @endif
                                    @endif
                                        

                                    @if (!empty($balance->deposit))
                                        <a href="{{ route('balance.transaction', ['id' => $balance->deposit_id]) }}">
                                            {{ '#'.$balance->deposit->transaction_id }}
                                        </a>
                                    @endif

                                    @if (!empty($balance->withdraw))
                                        -
                                    @endif
                                    </td>

                                    <td>
                                        {{ $balance->notes }}
                                        @if ($balance->type == 0 AND $balance->withdraw->status == 0)
                                        <span class="text-warning">Sedang Dalam Proses</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                        
                </div>

            </div>

        </div>

    </div>
</section>

@endsection

@extends('layouts.shipping')

@section('title'){{ str_replace('[TITLE]', $pageTitle, $seo_title) }}@endsection

@section('description'){{ str_replace('[TITLE]', $pageTitle, $seo_description) }}@endsection

@section('content')

<section class="page-section bg-grey-light">
    <div class="container">
        <div class="row justify-content-md-center py-4">
            <div class="col col-12 col-sm-12 col-md-10">
                <div class="page-content bg-white p-4">
                    <div class="page-title mb-4">{{ $pageTitle }}</div>
                        
                    <div>
                        <table class="table table-responsive">
                            <tr>
                                <td>
                                    <small class="text-grey">Di Kirim Dari</small>
                                    <div>{{ $origin }}</div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <small class="text-grey">Tujuan Pengiriman</small>
                                    <div>{{ $destination }}</div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <small class="text-grey">Nilai Transaksi</small>
                                    <div>{{ 'Rp '.number_format($transaction,0,',','.') }}</div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <small class="text-grey">Jarak Pengiriman</small>
                                    <div>{{ $distance.' km' }}</div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <small class="text-grey">Ongkos Kirim</small>
                                    <div class="text-price">
                                    @if ($distance > $shipping_maximum)
                                        Jarak Maksimal 12 km
                                    @else
                                        {{ 'Rp '.number_format($price,0,',','.') }}
                                    @endif
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

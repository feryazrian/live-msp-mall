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
                                @php
                                $totalBelanja = 0;
                                $totalShipping = 0;

                                $totalPoint = 0;
                                $totalPointPrice = 0;
                                $totalPromoPrice = 0;
                                @endphp
                                @foreach ($productList as $product)
                                    <tr>
                                        <td>
                                            <div class="d-inline-block mr-1">
                                                {{ $product->name }}
                                            </div>
                
                                        @if (!empty($product->preorder))
                                            <div class="notes bg-success d-inline-block">Group Buy</div>
                                        @endif
                                            
                                        @if (!empty($product->notes))
                                            <div class="notes bg-warning">{{ $product->notes }}</div>
                                        @endif
                                        </td>
                                        <td class="text-right">
                                            {{ 'Rp '.number_format($product->price,0,',','.') }}
                                        </td>
                                        <td class="text-right">{{ $product->unit }}</td>
                                        <td class="text-right">
                                            @php
                                            $totalBelanja += ($product->price * $product->unit);
                                            @endphp
                                            {{ 'Rp '.number_format(($product->price * $product->unit),0,',','.') }}
                                        </td>
                                    </tr>
                                    
                                @if (!empty($product->point))
                                    <tr>
                                        <td>MSP Point</td>
                                        <td class="text-right">
                                            {{ '- Rp '.number_format($product->point_price,0,',','.') }}
                                        </td>
                                        <td class="text-right">{{ $product->point }}</td>
                                        <td class="text-right">
                                            @php
                                            $totalPoint += $product->point;
                                            $totalPointPrice += ($product->point_price * $product->point);
                                            @endphp
                                            {{ '- Rp '.number_format($product->point_price * $product->point,0,',','.') }}
                                        </td>
                                    </tr>
                                @endif
                                @endforeach

                                @foreach ($shippingList as $shipping)
                                    <tr>
                                        <td>{{ $shipping->description }}</td>
                                        <td class="text-right">
                                            {{ 'Rp '.number_format($shipping->price,0,',','.') }}
                                        </td>
                                        <td class="text-right">1</td>
                                        <td class="text-right">
                                            @php
                                            $totalShipping += $shipping->price;
                                            @endphp
                                            {{ 'Rp '.number_format($shipping->price,0,',','.') }}
                                        </td>
                                    </tr>
                                @endforeach

                                @foreach ($promoList as $promo)
                                    <tr>
                                        <td>{{ $promo->type.' '.$promo->name }}</td>
                                        <td class="text-right">
                                            {{ '- Rp '.number_format($promo->price,0,',','.') }}
                                        </td>
                                        <td class="text-right">1</td>
                                        <td class="text-right">
                                            @php
                                            $totalPromoPrice += $promo->price;
                                            @endphp
                                            {{ '- Rp '.number_format($promo->price,0,',','.') }}
                                        </td>
                                    </tr>
                                @endforeach
                                    
                                    <tr>
                                        <td><b>Total Tagihan</b></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-right">
                                            {{ 'Rp '.number_format(($totalBelanja + $totalShipping - $totalPointPrice - $totalPromoPrice),0,',','.') }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="user-shipping">
                            <div class="title mb-2">Tujuan Pengiriman</div>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tr>
                                        <td style="min-width:150px;">Nama Alamat</td>
                                        <td>{{ $transactionProduct->transaction->address->address_name }}</td>
                                    </tr>
                                    <tr>
                                        <td>Nama Penerima</td>
                                        @if(!empty($transactionProduct->transaction->address->dropshipper_name))
                                        <td>{{ $transactionProduct->transaction->address->dropshipper_name }}</td>
                                        @else
                                        <td>{{ $transactionProduct->transaction->address->first_name.' '.$transactionProduct->transaction->address->last_name }}</td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <td>Nomor Telepon</td>
                                        @if(!empty($transactionProduct->transaction->address->dropshipper_phone))
                                        <td>{{ $transactionProduct->transaction->address->dropshipper_phone }}</td>
                                        @else
                                        <td>{{ $transactionProduct->transaction->address->phone }}</td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <td>Alamat</td>
                                        <td>{{ $transactionProduct->transaction->address->address }}</td>
                                    </tr>
                                    <tr>
                                        <td>Kecamatan</td>
                                        <td>{{ $transactionProduct->transaction->address->kecamatan->name }}</td>
                                    </tr>
                                    <tr>
                                        <td>Kota / Kabupaten</td>
                                        <td>{{ $transactionProduct->transaction->address->kabupaten->name }}</td>
                                    </tr>
                                    <tr>
                                        <td>Provinsi</td>
                                        <td>{{ $transactionProduct->transaction->address->provinsi->name }}</td>
                                    </tr>
                                    <tr>
                                        <td>Kode Post</td>
                                        <td>{{ $transactionProduct->transaction->address->postal_code }}</td>
                                    </tr>
                                    <tr>
                                        <td>Nomor Telpon</td>
                                        <td>{{ $transactionProduct->transaction->address->phone }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    
                    </div>
                </div>
                
            </div>

        </div>

    </div>
</section>

@endsection
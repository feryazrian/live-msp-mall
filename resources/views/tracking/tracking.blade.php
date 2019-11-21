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
                                $totalPoint = 0;
                                $totalPointPrice = 0;
                                $totalPromoPrice = 0;
                                $cancel = null;
                                $promo = $shipping->transaction->promo;
                                @endphp
                                @foreach ($productList as $product)
                                @php
                                $cancel = $product->cancel;
                                @endphp
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
                                            $totalPoint += $product->point;
                                            $totalPointPrice += ($product->point_price * $product->point);
                                            @endphp
                                            {{ 'Rp '.number_format(($product->price * $product->unit),0,',','.') }}
                                        </td>
                                    </tr>
                                @endforeach
                                    <tr>
                                        <td>{{ $shipping->description }}</td>
                                        <td class="text-right">-</td>
                                        <td class="text-right">-</td>
                                        <td class="text-right">
                                            {{ 'Rp '.number_format($shipping->price,0,',','.') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><b>Total Ongkos Kirim</b></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-right">
                                            {{ 'Rp '.number_format($shipping->price,0,',','.') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><b>Total Harga</b></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-right">
                                            {{ 'Rp '.number_format($totalBelanja,0,',','.') }}
                                        </td>
                                    </tr>

                                    @if ($totalPoint > 0)
                                    <tr>
                                        <td><b>Total penggunaan Point</b></td>
                                        <td class="text-right">
                                            {{ 'Rp '.number_format($product->point_price,0,',','.') }}
                                        </td>
                                        <td class="text-right">{{ $totalPoint }}</td>
                                        <td class="text-right">
                                            {{ '- Rp '.number_format($totalPointPrice,0,',','.') }}
                                        </td>
                                    </tr>
                                    @endif

                                    <!--
                                    @if (!empty($promo))
                                    @php
                                    //$totalPromoPrice = $promo->price;
                                    @endphp
                                    <tr>
                                        <td><b>Potongan Promo {{ config('app.name') }}</b></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-right">
                                            {{ '- Rp '.number_format($promo->price,0,',','.') }}
                                        </td>
                                    </tr>
                                    @endif
                                    -->
                                    
                                    <tr>
                                        <td><b>Total Tagihan</b></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-right">
                                            {{ 'Rp '.number_format(($totalBelanja + $shipping->price - $totalPointPrice - $totalPromoPrice),0,',','.') }}
                                        </td>
                                    </tr>

                                    @if ($access == 2)
                                    <tr>
                                        <td colspan="4">
                                            <a href="{{ route('transaction.invoice', ['id' => $transactionProduct->transaction_id]) }}">Lihat Tagihan Lengkap</a>
                                        </td>
                                    </tr>
                                    @endif
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
                                                    <a href="{{ route('user.detail', ['username' => $transactionProduct->transaction->user->username]) }}" class="btn user">
                                                        <img src="{{ asset('uploads/photos/'.$transactionProduct->transaction->user->photo) }}">
                                                    </a>
                                                </div>
                                                <div class="d-table-cell align-middle pr-3">
                                                    <div class="name">{{ $transactionProduct->transaction->user->name }}</div>
                                                    
                                                @if (!empty($transactionProduct->transaction->user->place_birth))
                                                    <div class="location">{{ $transactionProduct->transaction->user->kabupaten->name }}</div>
                                                @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="title">Penjual</div>
                                            
                                            <div class="user-card d-table">
                                                <div class="d-table-cell align-middle">
                                                    <a href="{{ route('user.detail', ['username' => $transactionProduct->user->username]) }}" class="btn user">
                                                        <img src="{{ asset('uploads/photos/'.$transactionProduct->user->photo) }}">
                                                    </a>
                                                </div>
                                                <div class="d-table-cell align-middle pr-3">
                                                    <div class="name">{{ $transactionProduct->user->name }}</div>
                                                    
                                                @if (!empty($transactionProduct->user->place_birth))
                                                    <div class="location">{{ $transactionProduct->user->kabupaten->name }}</div>
                                                @endif
                                                </div>
                                            </div>
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

                        <div class="tracking-status text-center">
                            <div class="title">Pembaruan Status Transaksi
                                <span>{{ $transactionProduct->updated_at->diffForHumans() }}</span>
                            </div>
                            @if (!empty($shippingTracking))
                            @if (!empty($shippingTracking->result->summary))
                            <div class="table-responsive">
                                <table class="table table-bordered text-left">
                                    <tr>
                                        <td class="font-weight-bold" style="min-width:180px;">Jasa Pengiriman</td>
                                        <td class="w-100">{{ strtoupper($shippingTracking->query->courier) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold">Nomor Resi</td>
                                        <td>{{ $shippingTracking->query->waybill }}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold">Nama Pengirim</td>
                                        <td>{{ $shippingTracking->result->summary->shipper_name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold">Nama Penerima</td>
                                        <td>{{ $shippingTracking->result->summary->receiver_name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold">Status</td>
                                        <td>{{ $shippingTracking->result->delivery_status->status }}</td>
                                    </tr>
                                </table>
                            </div>

                            @if (!empty($shippingTracking->result->manifest))
                            <table class="table table-striped table-responsive">
                                <thead>
                                    <tr>
                                        <td style="min-width:200px;">Waktu</td>
                                        <td class="w-100" style="min-width:160px;">Status Pengiriman</td>
                                        <td class="w-25">Keterangan</td>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($shippingTracking->result->manifest as $manifest)
                                    <tr>
                                        <td>{{ $manifest->manifest_date.' '.$manifest->manifest_time }}</td>
                                        <td>{{ $manifest->manifest_description }}</td>
                                        <td>{{ $manifest->city_name.' '.$manifest->manifest_code }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            @endif
                            @endif
                            @endif

                            @if ($status == '0')
                                @include('tracking.tracking-payment')
                            @endif

                            @if ($status == '1')
                                @include('tracking.tracking-approval')
                            @endif

                            @if ($status == '2')
                                @include('tracking.tracking-confirm')
                            @endif

                            @if ($status == '3')
                                @include('tracking.tracking-complain')
                            @endif

                            @if ($status == '4' || $status == '5')
                                @include('tracking.tracking-complete')
                            @endif

                            @if ($status >= '6')
                                @include('tracking.tracking-cancel')
                            @endif
                        </div>
                    
                    
                    </div>
                </div>
                
            </div>

        </div>

    </div>
</section>

@endsection
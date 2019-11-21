@extends('layouts.app')

@section('title'){{ str_replace('[TITLE]', $pageTitle, $seo_title) }}@endsection

@section('description'){{ str_replace('[TITLE]', $pageTitle, $seo_description) }}@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/digital/banner.css') }}">
@endsection

@section('content')
    <section class="page-section">
        <div class="container">
            <div class="page-title pt-3">{{ $pageTitle }} </div>
            <img src="{{asset("$banner->image_path")}}" class="banner-img">
            <div class="col-sm-12 my-4 px-0">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="col px-0 nav-item">
                        <a class="py-3 text-center nav-link active" data-toggle="tab" href="#desc" role="tab" aria-controls="desc" aria-selected="true">Tentang Promo</a>
                    </li>
                    <li class="col px-0 nav-item">
                        <a class="py-3 text-center nav-link" data-toggle="tab" href="#terms" role="tab" aria-controls="terms" aria-selected="false">Syarat & Ketentuan</a>
                    </li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane fade show active p-2" id="desc" role="tabpanel" aria-labelledby="nav-desc-tab">
                        {!! $banner->description !!}
                    </div>
                    <div class="tab-pane fade show p-2" id="terms" role="tabpanel" aria-labelledby="nav-terms-tab">
                        <ul>
                            @if (count($banner->promoppob) > 0)
                                <li>{!! $banner->promoppob[0]->term_condition !!}</li>
                            @else
                                <li>Diskon 7%</li>
                                <li>Promo diskon hanya berlaku untuk pembelian pulsa denom RP 300.000, Rp 500.000 dan Rp 1.000.000,- </li>
                                <li>Promo berlaku untuk pulsa operator : Telkomsel, XL, Indosat, Tri, dan Smartfren</li>
                                <li>Diskon berupa potongan langsung saat melakukan check out pembayaran</li>
                                <li>1 akun hanya bisa mendapatkan potongan langsung 1 kali selama periode promo</li>
                                <li>Kuota diskon terbatas dan akan diisi per hari</li>
                                <li>Apabila harga saat check out pembayaran tidak memotong, maka kuota habis</li>
                                <li>Hanya berlaku untuk pelanggan yang telah melakukan verifikasi nomor handphone</li>
                                <li>Promo ini tidak dapat digabungkan dengan promo lain.</li>
                                <li>Dengan membeli produk dalam promo ini, maka pelanggan dianggap mengerti dan menyetujui semua aturan yang berlaku.</li>
                            @endif
                            
                            
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
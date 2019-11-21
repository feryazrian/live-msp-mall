@extends('layouts.app')

@section('title'){{ str_replace('[TITLE]', $pageTitle, $seo_title) }}@endsection

@section('description'){{ str_replace('[TITLE]', $pageTitle, $seo_description) }}@endsection

@section('css')
    <!-- Link Swiper's CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.5.0/css/swiper.min.css">

    <!-- Link all Digital CSS -->    
    <link rel="stylesheet" href="{{ asset('assets/css/digital/banner.css') }}">
@endsection

@section('content')
    <section class="page-section pb-5">
        <div class="container py-4 pb-5">
            <div class="text-center loading-container"></div>
            <form action="{{ route('digital.inquiry') }}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="type" value="{{$type}}">
                <input type="hidden" name="type_ppob_id" value="{{$ppobType->id}}">
                <div id="ppob" class="digital-content">
                    <div class="d-table section-head w-100 bg-white py-3 px-4 border-bottom">
                        <div class="d-table-cell">Checkout</div>
                    </div>

                    @if (session('warning'))
                        <div class="alert alert-danger">
                            <button class="close fui-cross" data-dismiss="alert"></button>
                            {{ session('warning') }}
                        </div>
                    @endif
                    
                    @if (session('danger'))
                        <div class="alert alert-danger" style="background-color:red; color:white;">
                            <button class="close fui-cross" data-dismiss="alert"></button>
                            {{ session('danger') }}
                        </div>
                    @endif
                    
                    <div class="jumbotron py-4 m-0 bg-white">
                        @include('digital.ppob.transaction.checkout-content.payment-method')

                        <hr>

                        @switch($type)
                            @case('pulsa')
                                @include('digital.ppob.transaction.checkout-content.content-pulsa')
                                @break
                            @case('data')
                                @include('digital.ppob.transaction.checkout-content.content-data')
                                @break
                            @default
                                
                        @endswitch

                        @include('digital.ppob.transaction.checkout-content.content-calculate')

                        <hr>

                        @include('digital.ppob.transaction.checkout-content.total-payment')
                    </div>
                </div>
                <div class="container mt-3">
                    <div class="row">
                        {{-- <div class="col-md-6">
                            <p class="m-0">Total Pembayaran</p>
                            <p class="m-0"><b class="text-brand">Rp 11.000</b></p>
                        </div> --}}
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary btn-rounded px-5 pull-right" disabled>Bayar</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="text-center loading-container" ></div>
    </section>

    @include('digital.ppob.transaction.checkout-content.modal-promo-code')

@endsection

@section('scripts')
    <!-- Swiper JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.5.0/js/swiper.min.js"></script>
    <!-- Currency JS -->
    <script src="https://unpkg.com/currency.js@1.2.1/dist/currency.min.js"></script>

    <!-- Link all Digital Scripts -->
    <script src="{{ asset('assets/js/digital/index.js') }}"></script>
    <script src="{{ asset('assets/js/digital/checkout.js') }}"></script>
    <script src="{{ asset('assets/js/digital/swiper.js') }}"></script>
    <script src="{{ asset('assets/js/digital/pulsa.js') }}"></script>
    <script src="{{ asset('assets/js/digital/data.js') }}"></script>
@endsection
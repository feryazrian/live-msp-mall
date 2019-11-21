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

            <div class="col-md-12 page-content col-lg-9 pb-4">

                <div class="smarttab">
                    <!-- Tabs -->

                    <div class="scroll">
                        <ul class="nav nav-tabs setting bg-white">
                            <li><a href="{{ route('point.welcome') }}">Welcome Gift</a></li>
                            <li><a href="{{ route('point.share') }}">Share</a></li>
                            <li><a href="{{ route('point.referral') }}">Ajak Teman</a></li>
                            <li><a href="{{ route('point.game') }}" class="active">Checkin</a></li>
                        </ul>
                    </div>

                    <div class="page-list point game my-5 pb-5 text-center">

                    @if (empty($today))
                        <div class="card-spinner">
                            <div class="title">Checkin {{ config('app.name') }} setiap hari dan dapatkan <span class="text-brand">1 MSP</span> gratis!</div>
                            <div class="subtitle">Dan dapatkan tambahan MSP dengan checkin selama 30 hari berturut-turut!</div>

                        @if ($point_spinner == 1)
                            <div class="roulette-container">
                                <div class="roulette"></div>
                                <button class="roulette-spinner">
                                    <div class="pointer"></div>
                                </button>  
                            </div>

                            <div class="button">
                                <button type="submit" class="btn btn-rounded btn-primary btn-block btn-spinner">Spin Sekarang</button>
                            </div>

                        @else
                            <div class="button">
                                <button type="submit" class="btn btn-rounded btn-primary btn-block btn-checkin">Checkin Sekarang</button>
                            </div>
                        @endif
                        </div>
                    @else
                        <div class="notif">
                            <div class="icon mb-4">
                                <img src="{{ asset('images/icon_check.png') }}" width="100%" />
                            </div>
                            <div class="title pt-3 mb-3">Selamat!</div>
                            <div class="content">Anda telah berhasil mendapatkan <span class="text-brand">{{ $point }} MSP</span> hari ini! </div>
                        </div>
                    @endif

                        <div class="divider">RIWAYAT 30 HARI BERTURUT-TURUT</div>
                        
                        <div class="card-history">
                            
                        @for ($a=1; $a<=$repeat; $a++)
                            <div class="active">{{ $a }}</div>
                        @endfor

                        @for ($b=(1+$repeat); $b<=30; $b++)
                            <div>{{ $b }}</div>
                        @endfor

                        </div>

                    </div>

                </div>
                
            </div>

        </div>

    </div>
</section>

<script type="text/javascript">
    // Token
    var _token = $("meta[name=csrf-token]").attr("content");

@if ($point_spinner == 1)
    // Point
    var options = {
        prices: [
            {
                name: '1 - 50 MSP - Yellow',
                point: 50
            },
            {
                name: '2 - 5 MSP - Green',
                point: 5
            },
            {
                name: '3 - 5 MSP - Red',
                point: 5
            },
            {
                name: '4 - 10 MSP - Blue',
                point: 10
            },
            {
                name: '5 - 5 MSP - Yellow',
                point: 5
            },
            {
                name: '6 - 5 MSP - Green',
                point: 5
            },
            {
                name: '7 - 10 MSP - Red',
                point: 10
            },
            {
                name: '8 - 5 MSP - Blue',
                point: 5
            }
        ],
        duration: 1000
    };

    // Roulette
    var $r = $('.roulette').fortune(options);

    // Spinner
    var clickHandler = function() {
        $('.roulette-spinner').off('click');
        $('.btn-spinner').off('click');
    
        $r.spin().done(function(price) {
            var point = price.point;

            $.post('{{ route("point.game.store") }}', { _token: _token, point: point }, function(result) {
                $('.button').html('<button class="btn btn-rounded btn-outline-primary btn-block">Selamat! Anda telah berhasil mendapatkan '+result+' MSP hari ini!</button>');
            });
        });
    };

    $('.roulette-spinner').on('click', clickHandler);
    $('.btn-spinner').on('click', clickHandler);

@else
    $(".btn-checkin").on("click", function(){
        $.post('{{ route("point.game.store") }}', { _token: _token, point: 0 }, function(result) {
            $('.button').html('<button class="btn btn-rounded btn-outline-primary btn-block">Selamat! Anda telah berhasil mendapatkan '+result+' MSP hari ini!</button>');
        });
    });
@endif
</script>

@endsection
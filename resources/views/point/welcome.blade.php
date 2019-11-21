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
                            <li><a href="{{ route('point.welcome') }}" class="active">Welcome Gift</a></li>
                            <li><a href="{{ route('point.share') }}">Share</a></li>
                            <li><a href="{{ route('point.referral') }}">Ajak Teman</a></li>
                            <li><a href="{{ route('point.game') }}">Checkin</a></li>
                        </ul>
                    </div>

                    <div class="page-list point gift my-5 pb-5 text-center">
                        
                    @if (empty($check))
                        <div class="notif py-5 my-5">
                            <div class="content mt-5">Bergabung di {{ config('app.name') }} sekarang dan dapatkan <span class="text-brand">{{ $point }} MSP</span> gratis!</div>
                            <div class="mt-3 mb-5">
                                <form method="POST" action="{{ route('point.welcome.store') }}">
                                    {{ csrf_field() }}

                                    <button type="submit" class="btn btn-rounded btn-primary btn-block">Redeem Now</button>
                                </form>
                            </div>
                        </div>

                    @else
                        <div class="notif mt-4">
                            <div class="icon mb-4">
                                <img src="{{ asset('images/icon_check.png') }}" width="100%" />
                            </div>
                            <div class="title pt-3 mb-3">Selamat!</div>
                            <div class="content">Anda telah berhasil mendapatkan <span class="text-brand">30 MSP</span> gratis! </div>
                        </div>
                    @endif

                    </div>

                </div>
                
            </div>

        </div>

    </div>
</section>

@endsection
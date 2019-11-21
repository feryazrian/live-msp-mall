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
                            <li><a href="{{ route('point.referral') }}" class="active">Ajak Teman</a></li>
                            <li><a href="{{ route('point.game') }}">Checkin</a></li>
                        </ul>
                    </div>

                    <div class="page-list point referral my-5 pb-5 text-center">
                        
                        <div class="my-4 py-4">
                            <div class="content">Undang teman unduh {{ config('app.name') }} dan dapatkan <span class="text-brand">{{ $point }} MSP</span> gratis!</div>
                            <div class="qrcode"><img src="{{ $qrcode }}" /></div>
                            <div class="input">
                                <input type="text" class="form-control" value="{{ $url }}" readonly style="color:#333; background:#fff;" />
                            </div>
                        </div>

                    </div>

                </div>
                
            </div>

        </div>

    </div>
</section>

@endsection
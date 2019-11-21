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

                <div class="page-title">{{ $pageTitle }}</div>

                <div class="page-list my-5 pb-5 text-center">

                    <div class="point detail">
                        <div class="qrcode"><img src="{{ $qrcode }}" /></div>
                        <div class="name">{{ Auth::user()->name }}</div>
                    </div>
        
                </div>

            </div>

        </div>

    </div>
</section>

@endsection

@extends('layouts.app')

@section('title'){{ str_replace('[TITLE]', $pageTitle, $seo_title) }}@endsection

@section('description'){{ str_replace('[TITLE]', $pageTitle, $seo_description) }}@endsection

@section('css')
    <!-- Link Swiper's CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.5.0/css/swiper.min.css">

    <!-- Link all Digital CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/digital/index.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/digital/banner.css') }}">
@endsection

@section('content')
    <section class="page-section">
        <div class="container">
            @include('digital.banner.index')
            @include('digital.ppob.index')
            @include('digital.why')
            @include('digital.providers')
        </div>
    </section>
@endsection

@section('scripts')
    <!-- Swiper JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.5.0/js/swiper.min.js"></script>
    <!-- Currency JS -->
    <script src="https://unpkg.com/currency.js@1.2.1/dist/currency.min.js"></script>

    <!-- Link all Digital Scripts -->
    <script src="{{ asset('assets/js/digital/index.js') }}"></script>
    <script src="{{ asset('assets/js/digital/swiper.js') }}"></script>
    <script src="{{ asset('assets/js/digital/pulsa.js') }}"></script>
    <script src="{{ asset('assets/js/digital/data.js') }}"></script>
@endsection
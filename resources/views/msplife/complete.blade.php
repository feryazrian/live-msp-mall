@extends('layouts.app')

@section('title'){{ str_replace('[TITLE]', $pageTitle, $seo_title) }}@endsection

@section('description'){{ str_replace('[TITLE]', $pageTitle, $seo_description) }}@endsection

@section('content')

<section class="bg-white py-4">
    <div class="container">

        <div class="row">

            <div class="col-lg-3 d-none d-lg-block">
                <div class="sidebar">
                    @include('layouts.includes.sidenav-mobile')
                </div>
            </div>

            <div class="merchant join col-md-12 col-lg-9">

                <div class="notif text-center mb-5 pb-5">
                    <div class="icon mb-4 mt-4">
                        <img src="{{ asset('images/icon_check.png') }}" width="100%" />
                    </div>
                    <div class="title pt-3 mb-3">Selamat! <br/> Formulir Berhasil di Kirim</div>
                    <div class="content">Anda telah berhasil menjadi Member MSPLife.</div>
                </div>

            </div>

        </div>

    </div>
</section>

@endsection

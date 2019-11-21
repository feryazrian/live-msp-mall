@extends('layouts.app')

@section('title'){{ str_replace('[TITLE]', $pageTitle, $seo_title) }}@endsection

@section('description'){{ str_replace('[TITLE]', $pageTitle, $seo_description) }}@endsection

@section('css')
    <!-- OwlCarousel -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" />

    <style>
        .channel-img{
            width: 25px !important;
            height: 25px;
        }

        .title-wrapper{
            padding-top: 20px;
            margin: 0;
        }

        .title-wrapper b::after {
            display: block;
            content: '';
            width: 68px;
            height: 5px;
            border-radius: 6px;
            background: #ffbb00;
        }

        .owl-nav{
            /* float: right; */
            position: absolute;
            top: -60px;
            right: 0;
            margin: 0 !important;
        }

        .owl-prev, .owl-next{
            width: 40px;
            height: 40px;
            border-radius: 50% !important;
        }

        .owl-prev span, .owl-next span{
            font-size: 25px;
            font-weight: bold;
        }
    </style>
@endsection

@section('content')
    <section class="page-section">
        @if ($upcoming)
            <div class="container">
                <p class="title-wrapper"><b>Newest Live</b></p>
                <div class="row">
                    <div class="col-sm-12 col-md-6 my-3">
                        <div class="embed-responsive embed-responsive-16by9">
                            <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/{{ $upcoming->url }}?autoplay=1" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 my-3">
                        <h6 class="my-1">{{ $upcoming->episode }}</h6>
                        <div class="pl-3">
                            <span>Oleh : </span>
                            <img src="https://yt3.ggpht.com/a/AGF-l794aMJVPovIHTnxPsQmdqVKnrjZ6K7xz1hERg=s48-mo-c-c0xffffffff-rj-k-no" height="25" />
                            <span>MI Channel
                            </span>
                        </div>
                        <h6 class="my-1"><b>{{ $upcoming->title }}</b></h6>
                        <div class="pl-3">{{ strip_tags($upcoming->description) }}</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-12 my-4 @if($upcoming->start_time < date('Y-m-d')) d-none @endif">
                        <div class="embed-responsive embed-responsive-21by9">
                            <iframe width="150px" height="100px" src="https://www.youtube.com/live_chat?v={{ $upcoming->url }}&amp;embed_domain=mymspmall.id"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if ($history)
            <div class="container pb-5">
                <p class="title-wrapper"><b>Featured Playlists</b></p>
                <div class="row">
                    <div class="col-md-12 my-3">
                        <div class="owl-carousel owl-theme owl-loaded">
                            <div class="owl-stage-outer">
                                <div class="owl-stage">
                                    @foreach ($history as $item)
                                        <div class="owl-item">
                                            <div class="card">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="embed-responsive embed-responsive-16by9">
                                                            <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/{{ $item->url }}" allowfullscreen></iframe>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <b class="m-1">{{ $item->episode }}</b>
                                                            <div class="row m-1 pl-3 ">
                                                                <span>Oleh : </span>
                                                                &nbsp;
                                                                <img src="https://yt3.ggpht.com/a/AGF-l794aMJVPovIHTnxPsQmdqVKnrjZ6K7xz1hERg=s48-mo-c-c0xffffffff-rj-k-no" class="channel-img" />
                                                                &nbsp;
                                                                <span> MI Channel</span>
                                                            </div>
                                                            <b class="m-1">{{ $item->title }}</b>
                                                            <div class="pl-3">{{ strip_tags($item->description) }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </section>
@endsection

@section('scripts')
    <!-- OwlCarousel -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

    <script>
        $(document).ready(function($) {
            $(".owl-carousel").owlCarousel({
                autoplay:true,
                autoplayTimeout:25000,
                autoplayHoverPause:true,
                loop: false,
                margin: 10,
                responsiveClass: true,
                dots: false,
                nav: true,
                lazyLoad: true,
                responsive: {
                    0: {
                        items: 1,
                    },
                    768:{
                        items: 2,
                    },
                    1600: {
                        items: 3,
                    }
                }
            });
        });
    </script>
@endsection
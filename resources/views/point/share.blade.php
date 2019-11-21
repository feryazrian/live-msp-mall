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
                            <li><a href="{{ route('point.share') }}" class="active">Share</a></li>
                            <li><a href="{{ route('point.referral') }}">Ajak Teman</a></li>
                            <li><a href="{{ route('point.game') }}">Checkin</a></li>
                        </ul>
                    </div>

                    <div class="page-list point gift my-5 pb-5 text-center">
                        
                        <div class="notif py-5 my-5">
                            <div class="content mt-5">Share {{ config('app.name') }} ke media sosial dan dapatkan <span class="text-brand">{{ $point }} MSP</span> gratis!</div>

                            <div class="button mb-5">
                                <button class="btn btn-rounded point-share btn-facebook" data-provider="facebook">
                                    <i class="fab fa-facebook-f"></i>
                                </button>
                                <button class="btn btn-rounded point-share btn-whatsapp" data-provider="whatsapp">
                                    <i class="fab fa-whatsapp"></i>
                                </button>
                            </div>
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
    
    // Comment Delete
    $(document).on("click",".point-share",function(){
        var provider = $(this).attr('data-provider');

        $.post('{{ route("point.share.store") }}', { _token: _token, provider: provider }, function(result) {
            //window.location.href = result;
            window.open(result, '_blank');
        });
    });
</script>

@endsection
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
                <!--
                    <div class="navlist">
                    
                    @foreach ($lists as $item)
                        <a href="{{ route('page.detail', ['slug' => $item->slug]) }}">
                            <span class="icon-help-color"></span> {{ $item->name }}
                        </a>
                    @endforeach

                    </div>
                    <div class="navlist mt-2">
                        <a href="{{ $link_facebook }}">
                            <span class="fab fa-facebook"></span> Facebook
                        </a>
                        <a href="{{ $link_instagram }}">
                            <span class="fab fa-instagram"></span> Instagram
                        </a>
                    </div>
                -->
                </div>
            </div>

            <div class="col-md-12 page-content col-lg-9 py-4">

                <div class="page-title mb-4">{{ $page->name }}</div>

            @if (session('status'))
                <div class="alert alert-success">
                    <button class="close fui-cross" data-dismiss="alert"></button>
                    {{ session('status') }}
                </div>
            @endif
        
            @if (session('warning'))
                <div class="alert alert-danger">
                    <button class="close fui-cross" data-dismiss="alert"></button>
                    {{ session('warning') }}
                </div>
            @endif  
                    
                <div class="page-editor mb-5 pb-5">
                    <div>{!! $page->content !!}</div>
                    <div class="page-datetime mt-4">Terakhir Diperbaruhi <span>{{ $page->created_at->diffForHumans() }}</span></div>
                </div>

            </div>

        </div>

    </div>
</section>

@endsection

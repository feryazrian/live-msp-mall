@extends('layouts.shipping')

@section('title'){{ str_replace('[TITLE]', $pageTitle, $seo_title) }}@endsection

@section('description'){{ str_replace('[TITLE]', $pageTitle, $seo_description) }}@endsection

@section('content')

<section class="page-section">
    <div class="container">

        <div class="row">

            <div class="col-md-12 page-content col-lg-12 py-4">

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
                    
                <div class="mb-5 pb-5">
                    <div>{!! $page->content !!}</div>
                    <div class="page-datetime mt-4">Terakhir Diperbaruhi <span>{{ $page->created_at->diffForHumans() }}</span></div>
                </div>

            </div>

        </div>

    </div>
</section>

@endsection

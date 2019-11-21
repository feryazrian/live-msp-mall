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

            <div class="col-md-12 col-lg-9 py-4 mb-5">
                
                <div class="page-title mb-4">{{ $pageTitle }}</div>
                
                <div class="product-list with-sidebar">
                @foreach ($wishlists as $wishlist)
                @php
                    $item = $wishlist->product;
                @endphp

                    @include('layouts.card-product')
                @endforeach
                    
                <div class="text-center">
                    {{ $wishlists->links() }}
                </div>
                </div>

            </div>

        </div>

    </div>
</section>

@endsection

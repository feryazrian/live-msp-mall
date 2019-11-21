@extends('layouts.shipping')

@section('title'){{ str_replace('[TITLE]', $pageTitle, $seo_title) }}@endsection

@section('description'){{ str_replace('[TITLE]', $pageTitle, $seo_description) }}@endsection

@section('content')

<section class="page-section help">
    <div class="container">

        <div class="row">

            <div class="col-md-12 col-lg-12 py-4">
                
                <div class="page-title mb-4">{{ $pageTitle }}</div>

                <div class="page-list row mb-5 pb-5">

                @foreach ($lists as $item)
                    <a href="{{ route('shipping.page.detail', ['slug' => $item->slug]) }}" class="product-card">
                        <div class="content">
                            <div class="title">{{ $item->name }}</div>
                            <div class="location">{{ $item->created_at->diffForHumans() }}</div>
                        </div>
                    </a>
                @endforeach
        
                </div>

            </div>

        </div>

    </div>
</section>

@endsection

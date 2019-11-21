@extends('layouts.app')

@section('title'){{ str_replace('[TITLE]', $pageTitle, $seo_title) }}@endsection

@section('description'){{ str_replace('[TITLE]', $pageTitle, $seo_description) }}@endsection

@section('content')

<section class="search-product pb-5 bg-white">
    <div class="container">

        <div class="d-table search-head w-100 py-3">
            <div class="d-table-cell align-top"><span>{{ $pageTitle }}</span></div>
        </div>

        <div class="d-table search-main w-100">

            <div class="d-table-cell align-top product-list pb-5">
               
            @if ($lists->isNotEmpty())
                @foreach ($lists as $list)
                @php
                    $item = $list->product;
                @endphp
                    @include('layouts.card-product')
                @endforeach

                <div class="text-center">
                {{ $lists->links() }}
                </div>
            @endif

            </div>

        </div>

    </div>
</section>

@endsection

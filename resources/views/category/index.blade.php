@extends('layouts.app')

@section('title'){{ str_replace('[TITLE]', $pageTitle, $seo_title) }}@endsection

@section('description'){{ str_replace('[TITLE]', $pageTitle, $seo_description) }}@endsection

@section('content')

<section class="bg-brand py-1" style="background-color: whitesmoke;">
    <div class="container d-table">

        <div class="align-top category-icon-list two mb-5">
            @foreach ($categories as $item)
                <a href="{{ route('category.detail', ['slug' => $item->slug]) }}">
                    <img src="{{ asset('uploads/categories/'.$item->icon) }}">
                    {{ $item->name }}
                </a>
            @endforeach
            <a href="{{ route('digital') }}">
                <img src="{{ asset('images/bill-topup.png') }}">
                Topup & Tagihan
            </a>
        </div>

    </div>
</section>

@endsection

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

            <div class="col-md-12 col-lg-9 page-content py-4">

                <div class="page-title mb-4">{{ $pageTitle }}</div>
                
                <div class="transaction-lines mb-5 pb-5">

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
                
                @foreach ($coupons as $item)
                    <a class="transaction-line" href="#">
                        <div class="content d-table w-100">
                            <div class="d-table-cell text-left">
                                <div class="title">{{ '#'.$item->id }}</div>
                                <div>{{ $item->created_at }}</div>
                            </div>
                            <div class="d-table-cell text-right">
                                <div class="price">{{ 'Rp '.number_format($item->total,0,',','.') }}</div>
                                <div>Transaksi <span>{{ $item->transaction_id  }}</span></div>
                            </div>
                        </div>
                    </a>
                @endforeach
                
                </div>
                
            </div>

        </div>

    </div>
</section>

@endsection
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
                
                    <div class="button mt-3 px-3">
                        <a href="{{ route('coupon.create') }}" class="btn btn-rounded btn-primary btn-block m-1 px-4">Tukar Luck Draw</a>
                    </div>

                    <hr>
                
                @foreach ($coupons as $item)
                    <a class="transaction-line" href="#">
                        <div class="content d-table w-100">
                            <div class="d-table-cell text-left">
                                <div class="title">{{ '#'.$item->id }}</div>
                                <div>{{ $item->created_at }}</div>
                            </div>
                            <div class="d-table-cell text-right">
                                <div class="price">{{ $item->price }} MSP</div>
                                <div>Tukar <span>{{ $item->total }} MSP untuk {{ $item->coupon }} Kupon</span></div>
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
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

            <div class="col-md-12 page-content col-lg-9 py-4">

                <div class="page-title mb-4">{{ $pageTitle }}</div>

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

                <div class="alert alert-success">MSP Point Anda saat ini <b class="text-red">{{ $point }}</b></div>

                <div class="mb-5 pb-5">
                    <form method="post" action="{{ route('coupon.store') }}">
                        {{ csrf_field() }}

                        <div class="form-group {{ $errors->has('coupon') ? ' has-error' : '' }}">
                            <select name="coupon" class="form-control select select-smart select-secondary select-block" id="data-point">
                                <option value="">Pilih Jumlah Kupon</option>
                                
                            @for ($x = 1; $x <= 100; $x++)
                                <option value="{{ $x }}">{{ $x*$price }} MSP untuk {{ $x }} Kupon</option>
                            @endfor

                            </select>
                        
                        @if ($errors->has('coupon'))
                            <small id="coupon" class="form-text text-danger">
                                {{ str_replace('coupon', 'Jumlah Kupon', $errors->first('coupon')) }}
                            </small>
                        @endif
                        </div>

                        <button type="submit" class="btn btn-rounded btn-primary btn-block">Tukar Sekarang</button>
                    </form>

                </div>

            </div>

        </div>

    </div>
</section>

@endsection

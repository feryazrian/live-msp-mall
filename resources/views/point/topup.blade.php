@extends('layouts.app')

@section('title'){{ str_replace('[TITLE]', $pageTitle, $seo_title) }}@endsection

@section('description'){{ str_replace('[TITLE]', $pageTitle, $seo_description) }}@endsection

@section('content')

@if (env('MIDTRANS_PRODUCTION') == true)
<script type="text/javascript"
        src="https://app.midtrans.com/snap/snap.js"
        data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
@else
<script type="text/javascript"
        src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
@endif

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
                    <form method="post" action="#" id="payment-form" class="d-none">
                        {{ csrf_field() }}

                        <input type="hidden" name="result_type" id="result-type">
                        <input type="hidden" name="result_data" id="result-data">
                    </form>

                    <div class="form-group {{ $errors->has('point') ? ' has-error' : '' }}">
                        <select name="point" class="form-control select select-smart select-secondary select-block" id="data-point">
                            <option value="">Pilih Produk</option>
                            
                        @foreach ($lists as $item)
                            <option value="{{ $item->id }}" @if(old('point') == $item->id) selected="selected" @endif>{{ $item->name }}</option>
                        @endforeach

                        </select>
                    
                    @if ($errors->has('point'))
                        <small id="point" class="form-text text-danger">
                            {{ str_replace('point', 'Posisi Iklan', $errors->first('point')) }}
                        </small>
                    @endif
                    </div>

                    <button type="button" id="pay-button" data-transaction="{{ config('app.point_code') }}" class="btn btn-rounded btn-primary btn-block">Beli Sekarang</button>

                </div>

            </div>

        </div>

    </div>
</section>

<script>
    $('#pay-button').click(function (event) {
        event.preventDefault();

        var _token = $("meta[name=csrf-token]").attr("content");
        var transaction = $(this).attr('data-transaction');
        var point = $('#data-point').val();

        $.ajax({

            url: '{{ route("snap.token") }}',
            cache: false,
            type: 'POST',
            data: { _token: _token, transaction: transaction, point: point },

            success: function(data) {

                var resultType = document.getElementById('result-type');
                var resultData = document.getElementById('result-data');

                function changeResult(type,data){
                    $("#result-type").val(type);
                    $("#result-data").val(JSON.stringify(data));
                }

                snap.pay(data, {
                    onSuccess: function(result){
                        console.log(result);
                        changeResult('success', result);
                        $("#payment-form").attr('action', '{{ route("payment.success") }}').submit();
                    },
                    onPending: function(result){
                        console.log(result);
                        changeResult('pending', result);
                        $("#payment-form").attr('action', '{{ route("payment.pending") }}').submit();
                    },
                    onError: function(result){
                        console.log(result);
                        changeResult('error', result);
                        $("#payment-form").attr('action', '{{ route("payment.error") }}').submit();
                    }
                });
            }
        });
    });
</script>

@endsection

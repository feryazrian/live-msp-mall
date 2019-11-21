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

            <div class="col-md-12 page-content merchant join col-lg-9 pb-4">

                <div class="smarttab">
                    <!-- Tabs -->

                    <div class="scroll mb-0">
                        <ul class="nav nav-tabs merchant bg-white">
                            <li><a href="{{ route('merchant.store.edit') }}">Toko</a></li>
                            <li><a href="{{ route('merchant.account.edit') }}">Akun</a></li>
                            <li><a href="{{ route('merchant.finance.edit') }}">Administrasi</a></li>
                            <li><a href="{{ route('merchant.shipping.edit') }}" class="active">Pengiriman</a></li>
                        </ul>
                    </div>

                    <div class="head mb-4">{{ $pageSubTitle }}</div>

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

                    <form class="mb-5 pb-5" method="POST" action="{{ route('merchant.shipping.update') }}" enctype="multipart/form-data">
                        
                        {{ csrf_field() }}

                        <div class="row mb-1">
                            <div class="col-sm-6 mb-1">
                                <div class="logo-pos"></div>
                            </div>
                            <div class="col-sm-6 mb-1">
                                <div class="form-group {{ $errors->has('shipping_pos') ? ' has-error' : '' }}">
                                    <select name="shipping_pos" class="form-control select select-smart select-secondary select-block" required>
                                        <option value="">Pilih Status</option>
                                        <option value="1" @if ($merchant->shipping_pos == 1) selected @endif>Aktif</option>
                                        <option value="0" @if ($merchant->shipping_pos == 0) selected @endif>Tidak Aktif</option>
                                    </select>
                                
                                @if ($errors->has('shipping_pos'))
                                    <small id="shipping_pos" class="form-text text-danger">
                                        {{ str_replace('shipping_pos', 'Status Pengiriman POS', $errors->first('shipping_pos')) }}
                                    </small>
                                @endif
                                </div>
                            </div>
                        </div>

                        <div class="row mb-1">
                            <div class="col-sm-6 mb-1">
                                <div class="logo-jne"></div>
                            </div>
                            <div class="col-sm-6 mb-1">
                                <div class="form-group {{ $errors->has('shipping_jne') ? ' has-error' : '' }}">
                                    <select name="shipping_jne" class="form-control select select-smart select-secondary select-block" required>
                                        <option value="">Pilih Status</option>
                                        <option value="1" @if ($merchant->shipping_jne == 1) selected @endif>Aktif</option>
                                        <option value="0" @if ($merchant->shipping_jne == 0) selected @endif>Tidak Aktif</option>
                                    </select>
                                
                                @if ($errors->has('shipping_jne'))
                                    <small id="shipping_jne" class="form-text text-danger">
                                        {{ str_replace('shipping_jne', 'Status Pengiriman JNE', $errors->first('shipping_jne')) }}
                                    </small>
                                @endif
                                </div>
                            </div>
                        </div>

                        <div class="row mb-1">
                            <div class="col-sm-6 mb-1">
                                <div class="logo-tiki"></div>
                            </div>
                            <div class="col-sm-6 mb-1">
                                <div class="form-group {{ $errors->has('shipping_tiki') ? ' has-error' : '' }}">
                                    <select name="shipping_tiki" class="form-control select select-smart select-secondary select-block" required>
                                        <option value="">Pilih Status</option>
                                        <option value="1" @if ($merchant->shipping_tiki == 1) selected @endif>Aktif</option>
                                        <option value="0" @if ($merchant->shipping_tiki == 0) selected @endif>Tidak Aktif</option>
                                    </select>
                                
                                @if ($errors->has('shipping_tiki'))
                                    <small id="shipping_tiki" class="form-text text-danger">
                                        {{ str_replace('shipping_tiki', 'Status Pengiriman TIKI', $errors->first('shipping_tiki')) }}
                                    </small>
                                @endif
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-rounded btn-primary btn-block">Simpan Perubahan</button>

                    </form>

                </div>
                
            </div>

        </div>

    </div>
</section>

@endsection
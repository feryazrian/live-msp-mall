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

            <div class="page-content merchant join col-md-12 col-lg-9 py-4">

                <div class="step text-center">
                    <img src="{{ asset('images/merchant_join_step3.png') }}" width="100%" />
                </div>

                <div class="head my-4">{{ $pageSubTitle }}</div>

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

                <form class="mb-5 pb-5" method="POST" action="{{ route('merchant.three') }}" enctype="multipart/form-data">
                    
                    {{ csrf_field() }}

                    <div class="form-group mb-2 {{ $errors->has('bank_name') ? ' has-error' : '' }}">
                        <input type="text" name="bank_name" class="form-control" id="bank_name" aria-describedby="bank_name" placeholder="Nama Bank" required value="{{ old('bank_name') }}">
                    
                    @if ($errors->has('bank_name'))
                        <small id="bank_name" class="form-text text-danger">
                            {{ $errors->first('bank_name') }}
                        </small>
                    @endif
                    </div>

                    <div class="form-group mb-2 {{ $errors->has('bank_branch') ? ' has-error' : '' }}">
                        <input type="text" name="bank_branch" class="form-control" id="bank_branch" aria-describedby="bank_branch" placeholder="Cabang Bank" required value="{{ old('bank_branch') }}">
                    
                    @if ($errors->has('bank_branch'))
                        <small id="bank_branch" class="form-text text-danger">
                            {{ $errors->first('bank_branch') }}
                        </small>
                    @endif
                    </div>

                    <div class="form-group mb-2 {{ $errors->has('account_number') ? ' has-error' : '' }}">
                        <input type="text" name="account_number" class="numeric form-control" id="account_number" aria-describedby="account_number" placeholder="Nomor Rekening" required value="{{ old('account_number') }}">
                    
                    @if ($errors->has('account_number'))
                        <small id="account_number" class="form-text text-danger">
                            {{ $errors->first('account_number') }}
                        </small>
                    @endif
                    </div>

                    <div class="form-group mb-4 {{ $errors->has('account_name') ? ' has-error' : '' }}">
                        <input type="text" name="account_name" class="form-control" id="account_name" aria-describedby="account_name" placeholder="Nama Rekening" required value="{{ old('account_name') }}">
                    
                    @if ($errors->has('account_name'))
                        <small id="account_name" class="form-text text-danger">
                            {{ $errors->first('account_name') }}
                        </small>
                    @endif
                    </div>

                    <div class="form-group mb-2 {{ $errors->has('npwp_number') ? ' has-error' : '' }}">
                        <input type="text" name="npwp_number" class="form-control" id="npwp_number" aria-describedby="npwp_number" placeholder="Nomor NPWP" value="{{ old('npwp_number') }}" required>
                    
                    @if ($errors->has('npwp_number'))
                        <small id="npwp_number" class="form-text text-danger">
                            {{ $errors->first('npwp_number') }}
                        </small>
                    @endif
                    </div>

                    <div class="form-group mb-2 {{ $errors->has('npwp_name') ? ' has-error' : '' }}">
                        <input type="text" name="npwp_name" class="form-control" id="npwp_name" aria-describedby="npwp_name" placeholder="Nama NPWP" required value="{{ old('npwp_name') }}">
                    
                    @if ($errors->has('npwp_name'))
                        <small id="npwp_name" class="form-text text-danger">
                            {{ $errors->first('npwp_name') }}
                        </small>
                    @endif
                    </div>

                    <div class="form-group mb-2 {{ $errors->has('npwp_address') ? ' has-error' : '' }}">
                        <input type="text" name="npwp_address" class="form-control" id="npwp_address" aria-describedby="npwp_address" placeholder="Alamat NPWP" required value="{{ old('npwp_address') }}">
                    
                    @if ($errors->has('npwp_address'))
                        <small id="npwp_address" class="form-text text-danger">
                            {{ $errors->first('npwp_address') }}
                        </small>
                    @endif
                    </div>

                    <div class="form-group mb-4 {{ $errors->has('npwp_photo') ? ' has-error' : '' }}">
                        <div class="fileinput fileinput-new" data-provides="fileinput">
                            <div class="input-group">
                                <div class="form-control uneditable-input" data-trigger="fileinput">
                                    <span class="fui-clip fileinput-exists">Upload Foto NPWP</span>
                                    <span class="fileinput-filename">Upload Foto NPWP</span>
                                </div>
                                <span class="input-group-btn btn-file">
                                    <span class="btn btn-default fileinput-new" data-role="select-file">Select file</span>
                                    <span class="btn btn-default fileinput-exists" data-role="change">
                                        <span class="fui-gear"></span>  Change
                                    </span>
                                    <input type="file" name="npwp_photo" placeholder="Upload Foto NPWP" required />
                                    <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">
                                        <span class="fui-trash"></span>  Remove
                                    </a>
                                </span>
                            </div>
                        </div>
                
                    @if ($errors->has('npwp_photo'))
                        <small id="npwp_photo" class="form-text text-danger">
                            {{ $errors->first('npwp_photo') }}
                        </small>
                    @endif
                    </div>

                    <button type="submit" class="btn btn-rounded btn-primary btn-block">Kirim Sekarang</button>
                    <a href="{{ route('merchant.store') }}" class="btn btn-rounded btn-grey-outline btn-block">Kembali</a>

                </form>

            </div>

        </div>

    </div>
</section>

@endsection

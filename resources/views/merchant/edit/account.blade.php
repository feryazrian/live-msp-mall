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
                            <li><a href="{{ route('merchant.account.edit') }}" class="active">Akun</a></li>
                            <li><a href="{{ route('merchant.finance.edit') }}">Administrasi</a></li>
                            <li><a href="{{ route('merchant.shipping.edit') }}">Pengiriman</a></li>
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

                @if (!empty($check))
                @switch($check->status)
                    @case(2)
                    <div class="alert alert-danger">
                        <b>Permintaan Perubahan Ditolak</b>
    
                    @if (!empty($check->status_content))
                        <div>{{ $check->status_content }}</div>
                    @endif
                    </div>
                        @break
    
                    @default
                    <div class="alert alert-warning">
                        <b>Permintaan Perubahan Dalam Proses</b>
    
                    @if (!empty($check->status_content))
                        <div>{{ $check->status_content }}</div>
                    @endif
                    </div>
                @endswitch
                @endif

                    <form class="mb-5 pb-5" method="POST" action="{{ route('merchant.account.update') }}" enctype="multipart/form-data">
                        
                        {{ csrf_field() }}

                        <div class="form-group mb-2 {{ $errors->has('identity_name') ? ' has-error' : '' }}">
                            <input type="text" name="identity_name" class="form-control" id="identity_name" aria-describedby="identity_name" placeholder="Nama Pemilik (Sesuai KTP)" required value="{{ Auth::user()->identity_name }}">
                        
                        @if ($errors->has('identity_name'))
                            <small id="identity_name" class="form-text text-danger">
                                {{ $errors->first('identity_name') }}
                            </small>
                        @endif
                        </div>

                        <div class="form-group mb-2 {{ $errors->has('phone') ? ' has-error' : '' }}">
                            <input type="text" name="phone" class="numeric form-control" id="phone" aria-describedby="phone" placeholder="Nomor Telepon" required value="{{ Auth::user()->phone }}">
                        
                        @if ($errors->has('phone'))
                            <small id="phone" class="form-text text-danger">
                                {{ $errors->first('phone') }}
                            </small>
                        @endif
                        </div>

                        <div class="form-group mb-2 {{ $errors->has('place_birth') ? ' has-error' : '' }}">
                            <select name="place_birth" class="form-control select select-smart select-secondary select-block text-left m-0">
                                <option value="">Tempat Lahir</option>
                                
                            @php $match=''; @endphp
                            @foreach ($places as $kabupaten)
                            @if ($match != $kabupaten->provinsi->name)
                                <optgroup label="{{ $kabupaten->provinsi->name }}">
                            @endif
                                    <option value="{{ $kabupaten->id }}" @if(Auth::user()->place_birth == $kabupaten->id) selected="selected" @endif>{{ $kabupaten->name }}</option>
                            @if ($match != $kabupaten->provinsi->name)
                                </optgroup>
                            @endif
                            @php $match = $kabupaten->provinsi->name; @endphp
                            @endforeach
                            
                            </select>
                        
                        @if ($errors->has('place_birth'))
                            <small id="place_birth" class="form-text text-danger">
                                {{ str_replace('place_birth', 'Tempat Lahir', $errors->first('place_birth')) }}
                            </small>
                        @endif
                        </div>

                        <div class="form-group mb-2 {{ $errors->has('date_birth') ? ' has-error' : '' }}">
                            <input type="text" name="date_birth" class="datepicker-01 form-control" id="date_birth" aria-describedby="date_birth" placeholder="Tanggal Lahir" value="{{ Auth::user()->date_birth }}" required>
                        
                            @if ($errors->has('date_birth'))
                                <small id="date_birth" class="form-text text-danger">
                                    {{ $errors->first('date_birth') }}
                                </small>
                            @endif
                        </div>

                        <div class="form-group mb-2 {{ $errors->has('identity_number') ? ' has-error' : '' }}">
                            <input type="text" name="identity_number" class="form-control" id="identity_number" aria-describedby="identity_number" placeholder="Nomor KTP / Paspor" value="{{ Auth::user()->identity_number }}" required>
                        
                        @if ($errors->has('identity_number'))
                            <small id="identity_number" class="form-text text-danger">
                                {{ $errors->first('identity_number') }}
                            </small>
                        @endif
                        </div>

                        <div class="form-group mb-4 {{ $errors->has('identity_photo') ? ' has-error' : '' }}">
                            <!--<div class="mb-2" style="max-width: 400px; margin: auto;">
                                <img src="{{ asset('uploads/identities/'.Auth::user()->identity_photo) }}" width="100%" />
                            </div>-->
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <div class="input-group">
                                    <div class="form-control uneditable-input" data-trigger="fileinput">
                                        <span class="fui-clip fileinput-exists">Upload Foto KTP / Paspor</span>
                                        <span class="fileinput-filename">Upload Foto KTP / Paspor</span>
                                    </div>
                                    <span class="input-group-btn btn-file">
                                        <span class="btn btn-default fileinput-new" data-role="select-file">Select file</span>
                                        <span class="btn btn-default fileinput-exists" data-role="change">
                                            <span class="fui-gear"></span>  Change
                                        </span>
                                        <input type="file" name="identity_photo" placeholder="Upload Foto KTP / Paspor" required />
                                        <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">
                                            <span class="fui-trash"></span>  Remove
                                        </a>
                                    </span>
                                </div>
                            </div>
                    
                        @if ($errors->has('identity_photo'))
                            <small id="identity_photo" class="form-text text-danger">
                                {{ $errors->first('identity_photo') }}
                            </small>
                        @endif
                        </div>

                        <button type="submit" class="btn btn-rounded btn-primary btn-block">Kirim Permintaan Perubahan</button>

                    </form>

                </div>
                
            </div>

        </div>

    </div>
</section>

@endsection
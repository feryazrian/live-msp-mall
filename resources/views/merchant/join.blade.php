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

            @if (!empty($merchant))
                @if ($merchant->status == 2)
                <div class="alert alert-danger">
                    Maaf, Permohonan Menjadi Merchant anda di Tolak. Harap lakukan Permohonan Ulang!
                </div>
                @endif
            @endif

                <div class="step text-center">
                    <img src="{{ asset('images/merchant_join_step1.png') }}" width="100%" />
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

                <form class="mb-5 pb-5" method="POST" action="{{ route('merchant.one') }}" enctype="multipart/form-data">
                    
                    {{ csrf_field() }}

                    <div class="form-group mb-2 {{ $errors->has('name') ? ' has-error' : '' }}">
                        <input type="text" name="name" class="form-control" id="name" aria-describedby="name" placeholder="Nama Pemilik (Sesuai KTP)" required value="{{ old('name') }}">
                    
                    @if ($errors->has('name'))
                        <small id="name" class="form-text text-danger">
                            {{ $errors->first('name') }}
                        </small>
                    @endif
                    </div>

                    <div class="form-group mb-2 {{ $errors->has('phone') ? ' has-error' : '' }}">
                        <input type="text" name="phone" class="numeric form-control" id="phone" aria-describedby="phone" placeholder="Nomor Telepon" required value="{{ old('phone') }}">
                    
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
                                <option value="{{ $kabupaten->id }}" @if(old('place_birth') == $kabupaten->id) selected="selected" @endif>{{ $kabupaten->name }}</option>
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
                        <input type="text" name="date_birth" class="datepicker-01 form-control" id="date_birth" aria-describedby="date_birth" placeholder="Tanggal Lahir" value="{{ old('date_birth') }}" required>
                    
                        @if ($errors->has('date_birth'))
                            <small id="date_birth" class="form-text text-danger">
                                {{ $errors->first('date_birth') }}
                            </small>
                        @endif
                    </div>

                    <div class="form-group mb-2 {{ $errors->has('identity_number') ? ' has-error' : '' }}">
                        <input type="text" name="identity_number" class="form-control" id="identity_number" aria-describedby="identity_number" placeholder="Nomor KTP / Paspor" value="{{ old('identity_number') }}" required>
                    
                    @if ($errors->has('identity_number'))
                        <small id="identity_number" class="form-text text-danger">
                            {{ $errors->first('identity_number') }}
                        </small>
                    @endif
                    </div>

                    <div class="form-group mb-4 {{ $errors->has('identity_photo') ? ' has-error' : '' }}">
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

                    <button type="submit" class="btn btn-rounded btn-primary btn-block">Kirim Sekarang</button>

                </form>

            </div>

        </div>

    </div>
</section>

@endsection

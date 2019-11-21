@extends('layouts.app')

@section('title'){{ str_replace('[TITLE]', $pageTitle, $seo_title) }}@endsection

@section('description'){{ str_replace('[TITLE]', $pageTitle, $seo_description) }}@endsection

@section('content')

<section class="page-section address-list">
    <div class="container">

        <div class="row">

            <div class="col-lg-3 d-none d-lg-block py-4">
                <div class="sidebar">
                    @include('layouts.includes.sidenav-mobile')
                </div>
            </div>

            <div class="col-md-12 page-content col-lg-9 pb-4">

                <div class="smarttab">
                    <!-- Tabs -->

                    <div class="scroll">
                        <ul class="nav nav-tabs setting bg-white">
                            <li><a href="{{ route('setting') }}">Data Diri</a></li>
                            <li><a href="{{ route('setting.password') }}">Password</a></li>
                            <li><a href="{{ route('setting.address') }}" class="active">Daftar Alamat</a></li>
                        </ul>
                    </div>

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

                    <form class="mb-5 pb-5" method="POST" action="{{ route('setting.address.store') }}">
                        
                        {{ csrf_field() }}

                        <div class="form-group mb-4 {{ $errors->has('address_name') ? ' has-error' : '' }}">
                            <input type="text" name="address_name" class="form-control" id="address_name" aria-describedby="address_name" placeholder="Nama Alamat (Kantor, Kontrakan, Kosan)" required value="{{ old('address_name') }}">
                        
                        @if ($errors->has('address_name'))
                            <small id="address_name" class="form-text text-danger">
                                {{ str_replace('address_name', 'Nama Alamat', $errors->first('address_name')) }}
                            </small>
                        @endif
                        </div>

                        <div class="form-group mb-2 {{ $errors->has('first_name') ? ' has-error' : '' }}">
                            <input type="text" name="first_name" class="form-control" id="first_name" aria-describedby="first_name" placeholder="Nama Depan Penerima" required value="{{ old('first_name') }}">
                        
                        @if ($errors->has('first_name'))
                            <small id="first_name" class="form-text text-danger">
                                {{ str_replace('first_name', 'Nama Depan Penerima', $errors->first('first_name')) }}
                            </small>
                        @endif
                        </div>

                        <div class="form-group mb-2 {{ $errors->has('last_name') ? ' has-error' : '' }}">
                            <input type="text" name="last_name" class="form-control" id="last_name" aria-describedby="last_name" placeholder="Nama Belakang Penerima" required value="{{ old('last_name') }}">
                        
                        @if ($errors->has('last_name'))
                            <small id="last_name" class="form-text text-danger">
                                {{ str_replace('last_name', 'Nama Belakang Penerima', $errors->first('last_name')) }}
                            </small>
                        @endif
                        </div>

                        <div class="form-group mb-4 {{ $errors->has('phone') ? ' has-error' : '' }}">
                            <input type="text" name="phone" class="numeric form-control" id="phone" aria-describedby="phone" placeholder="Nomor Telepon" required value="{{ old('phone') }}">
                        
                        @if ($errors->has('phone'))
                            <small id="phone" class="form-text text-danger">
                                {{ $errors->first('phone') }}
                            </small>
                        @endif
                        </div>

                        <div class="form-group mb-2 {{ $errors->has('address') ? ' has-error' : '' }}">
                            <textarea name="address" class="form-control" id="address" aria-describedby="address" placeholder="Alamat" required rows="5">{{ old('address') }}</textarea>
                        
                        @if ($errors->has('address'))
                            <small id="address" class="form-text text-danger">
                                {{ $errors->first('address') }}
                            </small>
                        @endif
                        </div>

                        <div class="form-group mb-2 {{ $errors->has('provinsi_id') ? ' has-error' : '' }}">
                            <select id="provinsi" name="provinsi_id" class="form-control select select-smart select-secondary select-block">
                                <option value="">Provinsi</option>
                                
                            @foreach ($dataProvinsi as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach

                            </select>
                        
                        @if ($errors->has('provinsi_id'))
                            <small id="provinsi_id" class="form-text text-danger">
                                {{ str_replace('provinsi_id', 'Provinsi', $errors->first('provinsi_id')) }}
                            </small>
                        @endif
                        </div>

                        <div class="form-group mb-2 {{ $errors->has('kabupaten_id') ? ' has-error' : '' }}">
                            <select id="kabupaten" name="kabupaten_id" class="form-control select select-smart select-secondary select-block">
                                <option value="">Kota / Kabupaten</option>
                            </select>
                        
                        @if ($errors->has('kabupaten_id'))
                            <small id="kabupaten_id" class="form-text text-danger">
                                {{ str_replace('kabupaten_id', 'Kota / Kabupaten', $errors->first('kabupaten_id')) }}
                            </small>
                        @endif
                        </div>

                        <div class="form-group mb-2 {{ $errors->has('kecamatan_id') ? ' has-error' : '' }}">
                            <select id="kecamatan" name="kecamatan_id" class="form-control select select-smart select-secondary select-block">
                                <option value="">Kecamatan</option>
                            </select>
                        
                        @if ($errors->has('kecamatan_id'))
                            <small id="kecamatan_id" class="form-text text-danger">
                                {{ str_replace('kecamatan_id', 'Kecamatan', $errors->first('kecamatan_id')) }}
                            </small>
                        @endif
                        </div>

                        <div class="form-group mb-2 {{ $errors->has('desa_id') ? ' has-error' : '' }}">
                            <select id="desa" name="desa_id" class="form-control select select-smart select-secondary select-block">
                                <option value="">Kelurahan / Desa</option>
                            </select>
                        
                        @if ($errors->has('desa_id'))
                            <small id="desa_id" class="form-text text-danger">
                                {{ str_replace('desa_id', 'Kelurahan / Desa', $errors->first('desa_id')) }}
                            </small>
                        @endif
                        </div>

                        <div class="form-group mb-4 {{ $errors->has('postal_code') ? ' has-error' : '' }}">
                            <input type="text" name="postal_code" class="form-control numeric" id="postal_code" aria-describedby="postal_code" placeholder="Kode Pos" required value="{{ old('postal_code') }}">
                        
                        @if ($errors->has('postal_code'))
                            <small id="postal_code" class="form-text text-danger">
                                {{ $errors->first('postal_code') }}
                            </small>
                        @endif
                        </div>

                        <button type="submit" class="btn btn-rounded btn-primary btn-block">Tambahkan Alamat</button>

                    </form>

                </div>
                
            </div>

        </div>

    </div>
</section>

@endsection
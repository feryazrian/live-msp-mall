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
                    <img src="{{ asset('images/merchant_join_step2.png') }}" width="100%" />
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

                <form class="mb-5 pb-5" method="POST" action="{{ route('merchant.two') }}">
                    
                    {{ csrf_field() }}

                    <div class="form-group mb-2 {{ $errors->has('type_id') ? ' has-error' : '' }}">
                        <select name="type_id" class="form-control select select-smart select-secondary select-type select-block">
                            <option value="">Tipe Penjual</option>
                            
                        @foreach ($types as $item)
                            <option value="{{ $item->id }}" @if(old('type_id') == $item->id) selected="selected" @endif>{{ $item->name }}</option>
                        @endforeach

                        </select>
                    
                    @if ($errors->has('type_id'))
                        <small id="type_id" class="form-text text-danger">
                            {{ str_replace('type_id', 'Tipe Penjual', $errors->first('type_id')) }}
                        </small>
                    @endif
                    </div>
                        
                    <div class="type-info mt-3 mb-4"></div>

                    <div class="form-group mb-2 {{ $errors->has('name') ? ' has-error' : '' }}">
                        <input type="text" name="name" class="form-control" id="name" aria-describedby="name" placeholder="Nama Toko" required value="{{ old('name') }}">
                    
                    @if ($errors->has('name'))
                        <small id="name" class="form-text text-danger">
                            {{ $errors->first('name') }}
                        </small>
                    @endif
                    </div>

                    <div class="form-group mb-2 {{ $errors->has('category_id') ? ' has-error' : '' }}">
                        <select name="category_id" class="form-control select select-smart select-secondary select-block">
                            <option value="">Kategori Utama</option>
                            
                        @foreach ($categories as $item)
                            <option value="{{ $item->id }}" @if(old('category_id') == $item->id) selected="selected" @endif>{{ $item->name }}</option>
                        @endforeach

                        </select>
                    
                    @if ($errors->has('category_id'))
                        <small id="category_id" class="form-text text-danger">
                            {{ str_replace('category_id', 'Kategori Utama', $errors->first('category_id')) }}
                        </small>
                    @endif
                    </div>

                    <div class="form-group mb-4 {{ $errors->has('additional_id') ? ' has-error' : '' }}">
                        <select name="additional_id" class="form-control select select-smart select-secondary select-block">
                            <option value="">Kategori Tambahan</option>
                            
                        @foreach ($categories as $item)
                            <option value="{{ $item->id }}" @if(old('additional_id') == $item->id) selected="selected" @endif>{{ $item->name }}</option>
                        @endforeach

                        </select>
                    
                    @if ($errors->has('additional_id'))
                        <small id="additional_id" class="form-text text-danger">
                            {{ str_replace('additional_id', 'Kategori Tambahan', $errors->first('additional_id')) }}
                        </small>
                    @endif
                    </div>

                    <div class="form-group mb-2 {{ $errors->has('address') ? ' has-error' : '' }}">
                        <textarea name="address" class="form-control" id="address" aria-describedby="address" placeholder="Alamat Toko" required rows="5">{{ old('address') }}</textarea>
                    
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
                        <input type="text" name="postal_code" class="form-control" id="postal_code" aria-describedby="postal_code" placeholder="Kode Pos" required value="{{ old('postal_code') }}">
                    
                    @if ($errors->has('postal_code'))
                        <small id="postal_code" class="form-text text-danger">
                            {{ $errors->first('postal_code') }}
                        </small>
                    @endif
                    </div>

                    <div class="form-group mb-2 {{ $errors->has('referral_name') ? ' has-error' : '' }}">
                        <input type="text" name="referral_name" class="form-control" id="referral_name" aria-describedby="referral_name" placeholder="Nama Referensi" value="{{ old('referral_name') }}">
                    
                    @if ($errors->has('referral_name'))
                        <small id="referral_name" class="form-text text-danger">
                            {{ $errors->first('referral_name') }}
                        </small>
                    @endif
                    </div>

                    <div class="form-group mb-4 {{ $errors->has('checkbox') ? ' has-error' : '' }}">
                        <label class="checkbox" for="checkbox1">
                            <input type="checkbox" data-toggle="checkbox" name="checkbox" id="checkbox1" required>
                            Dengan ini saya menyetujui Perjanjian MSP Mall
                        </label>
                    </div>

                    <button type="submit" class="btn btn-rounded btn-primary btn-block">Kirim Sekarang</button>
                    <a href="{{ route('merchant.join') }}" class="btn btn-rounded btn-grey-outline btn-block">Kembali</a>

                </form>

            </div>

        </div>

    </div>
</section>

<script type="text/javascript">
    // Token
    var _token = $("meta[name=csrf-token]").attr("content");

    // Select Type
    $(".select-type").on("change", function(){
        var type = $(this).val();

        $.post('{{ route("merchant.type") }}', { _token:_token, type:type }, function(result) { 
            $(".type-info").html(result);
        });

        return true;
    });
</script>

@endsection

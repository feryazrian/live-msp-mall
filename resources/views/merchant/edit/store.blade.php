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
                            <li><a href="{{ route('merchant.store.edit') }}" class="active">Toko</a></li>
                            <li><a href="{{ route('merchant.account.edit') }}">Akun</a></li>
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

                    <form class="mb-5 pb-5" method="POST" action="{{ route('merchant.store.update') }}">
                    
                        {{ csrf_field() }}
    
                        <div class="form-group mb-2 {{ $errors->has('type_id') ? ' has-error' : '' }}">
                            <select name="type_id" class="form-control select select-smart select-secondary select-type select-block">
                                <option value="">Tipe Penjual</option>
                                
                            @foreach ($types as $item)
                                <option value="{{ $item->id }}" @if($merchant->type_id == $item->id) selected="selected" @endif>{{ $item->name }}</option>
                            @endforeach
    
                            </select>
                        
                        @if ($errors->has('type_id'))
                            <small id="type_id" class="form-text text-danger">
                                {{ str_replace('type_id', 'Tipe Penjual', $errors->first('type_id')) }}
                            </small>
                        @endif
                        </div>

                        <div class="type-info mt-3 mb-4">{{ $merchant->type->content }}</div>
    
                        <div class="form-group mb-2 {{ $errors->has('name') ? ' has-error' : '' }}">
                            <input type="text" name="name" class="form-control" id="name" aria-describedby="name" placeholder="Nama Toko" required value="{{ $merchant->name }}">
                        
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
                                <option value="{{ $item->id }}" @if($merchant->category_id == $item->id) selected="selected" @endif>{{ $item->name }}</option>
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
                                <option value="{{ $item->id }}" @if($merchant->additional_id == $item->id) selected="selected" @endif>{{ $item->name }}</option>
                            @endforeach
    
                            </select>
                        
                        @if ($errors->has('additional_id'))
                            <small id="additional_id" class="form-text text-danger">
                                {{ str_replace('additional_id', 'Kategori Tambahan', $errors->first('additional_id')) }}
                            </small>
                        @endif
                        </div>
    
                        <div class="form-group mb-2 {{ $errors->has('address') ? ' has-error' : '' }}">
                            <textarea name="address" class="form-control" id="address" aria-describedby="address" placeholder="Alamat Toko" required rows="5">{{ $merchant->address->address }}</textarea>
                        
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
                                <option value="{{ $item->id }}" @if ($item->id == $merchant->address->provinsi_id) selected @endif>{{ $item->name }}</option>
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
                                <option value="{{ $merchant->address->kabupaten_id }}">{{ $merchant->address->kabupaten->name }}</option>
                            </select>
                        
                        @if ($errors->has('kabupaten_id'))
                            <small id="kabupaten_id" class="form-text text-danger">
                                {{ str_replace('kabupaten_id', 'Kota / Kabupaten', $errors->first('kabupaten_id')) }}
                            </small>
                        @endif
                        </div>
    
                        <div class="form-group mb-2 {{ $errors->has('kecamatan_id') ? ' has-error' : '' }}">
                            <select id="kecamatan" name="kecamatan_id" class="form-control select select-smart select-secondary select-block">
                                <option value="{{ $merchant->address->kecamatan_id }}">{{ $merchant->address->kecamatan->name }}</option>
                            </select>
                        
                        @if ($errors->has('kecamatan_id'))
                            <small id="kecamatan_id" class="form-text text-danger">
                                {{ str_replace('kecamatan_id', 'Kecamatan', $errors->first('kecamatan_id')) }}
                            </small>
                        @endif
                        </div>
    
                        <div class="form-group mb-2 {{ $errors->has('desa_id') ? ' has-error' : '' }}">
                            <select id="desa" name="desa_id" class="form-control select select-smart select-secondary select-block">
                                <option value="{{ $merchant->address->desa_id }}">{{ $merchant->address->desa->name }}</option>
                            </select>
                        
                        @if ($errors->has('desa_id'))
                            <small id="desa_id" class="form-text text-danger">
                                {{ str_replace('desa_id', 'Kelurahan / Desa', $errors->first('desa_id')) }}
                            </small>
                        @endif
                        </div>
    
                        <div class="form-group mb-4 {{ $errors->has('postal_code') ? ' has-error' : '' }}">
                            <input type="text" name="postal_code" class="form-control" id="postal_code" aria-describedby="postal_code" placeholder="Kode Pos" required value="{{ $merchant->address->postal_code }}">
                        
                        @if ($errors->has('postal_code'))
                            <small id="postal_code" class="form-text text-danger">
                                {{ $errors->first('postal_code') }}
                            </small>
                        @endif
                        </div>

                        <div class="form-group mb-4 {{ $errors->has('referral_name') ? ' has-error' : '' }}">
                            <input type="text" name="referral_name" class="form-control" id="referral_name" aria-describedby="referral_name" placeholder="Nama Referensi" value="{{ $merchant->referral_name }}" style="color:#333;" readonly>
                        
                        @if ($errors->has('referral_name'))
                            <small id="referral_name" class="form-text text-danger">
                                {{ $errors->first('referral_name') }}
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
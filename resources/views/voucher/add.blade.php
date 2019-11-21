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

                <form class="mb-5 pb-5" method="POST" action="{{ route('voucher.store') }}" enctype="multipart/form-data">
                    
                    {{ csrf_field() }}

                    <div class="form-group mb-2 {{ $errors->has('name') ? ' has-error' : '' }}">
                        <input type="text" required value="{{ old('name') }}" class="form-control" name="name" placeholder="Nama Produk" />
                        
                    @if ($errors->has('name'))
                        <small id="name" class="form-text text-danger">
                            {{ $errors->first('name') }}
                        </small>
                    @endif
                    </div>

                    <div class="dropzone" id="upload-photo">
                        <div class="fallback">
                            <input name="file" type="file" multiple required />
                        </div>
                    </div>

                    <div class="form-group mt-2 mb-2 {{ $errors->has('stock') ? ' has-error' : '' }}">
                        <input type="number" required value="{{ old('stock') }}" class="numeric form-control" name="stock" placeholder="Stok Produk (Buah)" />
                
                    @if ($errors->has('stock'))
                        <small id="stock" class="form-text text-danger">
                            {{ $errors->first('stock') }}
                        </small>
                    @endif
                    </div>

                    <div class="form-group mb-2 {{ $errors->has('price') ? ' has-error' : '' }}">
                        <input type="number" required value="{{ old('price') }}" class="numeric form-control" name="price" placeholder="Harga Satuan (Rp)" />
            
                    @if ($errors->has('price'))
                        <small id="price" class="form-text text-danger">
                            {{ $errors->first('price') }}
                        </small>
                    @endif
                    </div>

                    <div class="form-group mb-2 {{ $errors->has('discount') ? ' has-error' : '' }}">
                        <input type="number" value="{{ old('discount') }}" class="numeric form-control" name="discount" placeholder="Harga Diskon (Optional)" />
            
                    @if ($errors->has('discount'))
                        <small id="discount" class="form-text text-danger">
                            {{ $errors->first('discount') }}
                        </small>
                    @endif
                    </div>

                    <div class="form-group mb-2 {{ $errors->has('max_amount_per_days') ? ' has-error' : '' }}">
                        <input type="number" value="{{ old('max_amount_per_days') }}" class="numeric form-control" name="max_amount_per_days" placeholder="Jumlah Maksimal per User sehari" />
            
                    @if ($errors->has('max_amount_per_days'))
                        <small id="max_amount_per_days" class="form-text text-danger">
                            {{ $errors->first('max_amount_per_days') }}
                        </small>
                    @endif
                    </div>

                    <div class="form-group mb-4 {{ $errors->has('description') ? ' has-error' : '' }}">
                        <textarea placeholder="Deskripsi Produk" class="form-control" name="description" rows="5">{{ old('description') }}</textarea>
        
                    @if ($errors->has('description'))
                        <small id="description" class="form-text text-danger">
                            {{ $errors->first('description') }}
                        </small>
                    @endif
                    </div>

                    <div class="form-group mb-4 {{ $errors->has('voucher_expired') ? ' has-error' : '' }}">
                        <input type="text" value="{{ old('voucher_expired') }}" class="form-control datetime" data-field="datetime" readonly required name="voucher_expired" placeholder="Batas Waktu Klaim E-Voucher" />

                        <div class="datetimepicker"></div>
                
                        @if ($errors->has('voucher_expired'))
                            <small id="voucher_expired" class="form-text text-danger">
                                {{ $errors->first('voucher_expired') }}
                            </small>
                        @endif
                    </div>

                    <button type="submit" class="btn btn-rounded btn-primary btn-block">Jual Produk Sekarang</button>

                </form>

            </div>

        </div>

    </div>
</section>

<link type="text/css" rel="stylesheet" href="{{ asset('css/dropzone.min.css') }}" />
<script type="text/javascript" src="{{ asset('scripts/dropzone.min.js') }}"></script>
<script>
    var file_up_names = [];
    var n = 0;
    Dropzone.options.uploadPhoto = {
        url: '{{ route("product.photo.add") }}',
        paramName: 'file',
        maxFilesize: 2, // MB
        maxFiles: 5,
        parallelUploads: 1,
        addRemoveLinks: true,
        dictRemoveFile: 'Hapus',
        dictDefaultMessage: 'Seret Foto Produk anda kesini untuk meng-unggah, atau klik untuk memilih satu persatu<br/><div style="font-size:12px;margin-top:10px;">(Resolusi foto minimal 400 x 400px)</div>',
        headers: {
            'x-csrf-token': document.querySelectorAll('input[name=_token]')[0].getAttributeNode('value').value,
        },
        acceptedFiles: 'image/*',
        init: function() {
            this.on('success', function( file, resp ) {
                file_up_names[n] = {"serverFileName" : resp, "fileName" : file.name,"fileId" : n };
                n++;
            });

            this.on('thumbnail', function(file) {
                if (file.accepted !== false) {
                    if ( file.width < 400 || file.height < 400 ) {
                        file.rejectDimensions();
                    }
                    else {
                        file.acceptDimensions();
                    }
                }
            });

            this.on('removedfile', function(file) {
                for(var i=0; i<=file_up_names.length; ++i) {
                    if (file_up_names[i]) {
                        if(file_up_names[i].fileName == file.name) {
                            $.post('{{ route("product.photo.delete") }}', { file_name:file_up_names[i].serverFileName, '_token': document.querySelectorAll('input[name=_token]')[0].value }, function(data,status) { });
                        }
                    }
                }
            });
        },
        accept: function(file, done) {
            file.acceptDimensions = done;
            file.rejectDimensions = function() {
                done('Resolusi foto minimal 400 x 400px')
            };
        }
    };
</script>

@endsection

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

            @switch($product->status)
                @case(2)
                <div class="alert alert-danger">
                    <b>{{ $product->action->name.' Ditolak' }}</b>

                @if (!empty($product->action_content))
                    <div>{{ $product->action_content }}</div>
                @endif
                </div>
                    @break

                @case(0)
                <div class="alert alert-warning">
                    <b>{{ $product->action->name.' Dalam Proses' }}</b>

                @if (!empty($product->action_content))
                    <div>{{ $product->action_content }}</div>
                @endif
                </div>
            @endswitch

                <div class="dropzone dz-clickable dz-started my-2" id="upload-photo">
                    <div class="dz-default dz-message">
                        <span>
                            Seret Foto Produk anda kesini untuk meng-unggah, atau klik untuk memilih satu persatu<br>
                            <div style="font-size:12px;margin-top:10px;">(Resolusi foto minimal 400 x 400px)</div>
                        </span>
                    </div>

                    @foreach ($productPhoto as $photo)
                    <div class="dz-preview dz-image-preview dz-processing dz-success dz-complete">
                        <div class="dz-image">
                            <img data-dz-thumbnail="" alt="{{ $photo->photo }}" src="{{ asset('uploads/products/small-'.$photo->photo) }}" />
                        </div>
                        <div class="dz-details">
                            <div class="dz-filename">
                                <span data-dz-name="">{{ $photo->photo }}</span>
                            </div>
                        </div>
                        <div class="dz-progress">
                            <span class="dz-upload" data-dz-uploadprogress="" style="width: 100%;"></span>
                        </div>
                        <div class="dz-error-message"><span data-dz-errormessage=""></span></div>
                        <div class="dz-success-mark">
                            <svg width="54px" height="54px" viewBox="0 0 54 54" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns">      <title>Check</title>      <defs></defs>      <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage">        <path d="M23.5,31.8431458 L17.5852419,25.9283877 C16.0248253,24.3679711 13.4910294,24.366835 11.9289322,25.9289322 C10.3700136,27.4878508 10.3665912,30.0234455 11.9283877,31.5852419 L20.4147581,40.0716123 C20.5133999,40.1702541 20.6159315,40.2626649 20.7218615,40.3488435 C22.2835669,41.8725651 24.794234,41.8626202 26.3461564,40.3106978 L43.3106978,23.3461564 C44.8771021,21.7797521 44.8758057,19.2483887 43.3137085,17.6862915 C41.7547899,16.1273729 39.2176035,16.1255422 37.6538436,17.6893022 L23.5,31.8431458 Z M27,53 C41.3594035,53 53,41.3594035 53,27 C53,12.6405965 41.3594035,1 27,1 C12.6405965,1 1,12.6405965 1,27 C1,41.3594035 12.6405965,53 27,53 Z" id="Oval-2" stroke-opacity="0.198794158" stroke="#747474" fill-opacity="0.816519475" fill="#FFFFFF" sketch:type="MSShapeGroup"></path>      </g>    </svg>
                        </div>
                        <div class="dz-error-mark">
                            <svg width="54px" height="54px" viewBox="0 0 54 54" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns">      <title>Error</title>      <defs></defs>      <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage">        <g id="Check-+-Oval-2" sketch:type="MSLayerGroup" stroke="#747474" stroke-opacity="0.198794158" fill="#FFFFFF" fill-opacity="0.816519475">          <path d="M32.6568542,29 L38.3106978,23.3461564 C39.8771021,21.7797521 39.8758057,19.2483887 38.3137085,17.6862915 C36.7547899,16.1273729 34.2176035,16.1255422 32.6538436,17.6893022 L27,23.3431458 L21.3461564,17.6893022 C19.7823965,16.1255422 17.2452101,16.1273729 15.6862915,17.6862915 C14.1241943,19.2483887 14.1228979,21.7797521 15.6893022,23.3461564 L21.3431458,29 L15.6893022,34.6538436 C14.1228979,36.2202479 14.1241943,38.7516113 15.6862915,40.3137085 C17.2452101,41.8726271 19.7823965,41.8744578 21.3461564,40.3106978 L27,34.6568542 L32.6538436,40.3106978 C34.2176035,41.8744578 36.7547899,41.8726271 38.3137085,40.3137085 C39.8758057,38.7516113 39.8771021,36.2202479 38.3106978,34.6538436 L32.6568542,29 Z M27,53 C41.3594035,53 53,41.3594035 53,27 C53,12.6405965 41.3594035,1 27,1 C12.6405965,1 1,12.6405965 1,27 C1,41.3594035 12.6405965,53 27,53 Z" id="Oval-2" sketch:type="MSShapeGroup"></path>        </g>      </g>    </svg>
                        </div>
                        <form method="post" role="form" action="{{ route('product.photo.edit.delete') }}">
                            {{ csrf_field() }}
                            <input type="hidden" name="product" value="{{ $product->id }}" />
                            <input type="hidden" name="file_name" value="{{ $photo->photo }}" />
                            <button type="submit" class="dz-remove" href="javascript:undefined;" data-dz-remove="">Hapus</button>
                        </form>
                    </div>
                    @endforeach
                </div>

                <div class="mb-5 pb-5">
                    <form method="post" role="form" action="{{ route('voucher.update') }}" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <input type="hidden" name="product" value="{{ $product->id }}" />

                        <div class="form-group mb-2 {{ $errors->has('name') ? ' has-error' : '' }}">
                            <input type="text" required value="{{ $product->name }}" class="form-control" name="name" placeholder="Nama Produk" />
                    
                            @if ($errors->has('name'))
                                <small id="name" class="form-text text-danger">
                                    {{ $errors->first('name') }}
                                </small>
                            @endif
                        </div>

                        <div class="form-group mb-2 {{ $errors->has('stock') ? ' has-error' : '' }}">
                            <input type="number" name="stock" required value="{{ $product->stock }}" class="numeric form-control" placeholder="Stok Produk (Buah)" />
        
                        @if ($errors->has('stock'))
                            <small id="stock" class="form-text text-danger">
                                {{ $errors->first('stock') }}
                            </small>
                        @endif
                        </div>

                        <div class="form-group mb-2 {{ $errors->has('price') ? ' has-error' : '' }}">
                            <input type="number" required value="{{ $product->price }}" class="numeric form-control" name="price" placeholder="Harga Satuan (Rp)" />

                        @if ($errors->has('price'))
                            <small id="price" class="form-text text-danger">
                                {{ $errors->first('price') }}
                            </small>
                        @endif
                        </div>

                        <div class="form-group mb-2 {{ $errors->has('discount') ? ' has-error' : '' }}">
                            <input type="number" value="{{ $product->discount }}" class="numeric form-control" name="discount" placeholder="Harga Diskon (Optional)" />
                
                        @if ($errors->has('discount'))
                            <small id="discount" class="form-text text-danger">
                                {{ $errors->first('discount') }}
                            </small>
                        @endif
                        </div>

                        <div class="form-group mb-2 {{ $errors->has('max_amount_per_days') ? ' has-error' : '' }}">
                            <input type="number" value="{{ $product->max_amount_per_days }}" class="numeric form-control" name="max_amount_per_days" placeholder="Jumlah Maksimal per User sehari" />
                
                        @if ($errors->has('max_amount_per_days'))
                            <small id="max_amount_per_days" class="form-text text-danger">
                                {{ $errors->first('max_amount_per_days') }}
                            </small>
                        @endif
                        </div>

                        <div class="form-group mb-4 {{ $errors->has('description') ? ' has-error' : '' }}">
                            <textarea placeholder="Deskripsi Produk" class="form-control" name="description" rows="5">{{ $product->description }}</textarea>

                        @if ($errors->has('description'))
                            <small id="description" class="form-text text-danger">
                                {{ $errors->first('description') }}
                            </small>
                        @endif
                        </div>

                        <div class="form-group mb-4 {{ $errors->has('voucher_expired') ? ' has-error' : '' }}">
                            <input type="text" value="{{ $product->voucher_expired }}" class="form-control datetime" data-field="datetime" readonly required name="voucher_expired" placeholder="Batas Waktu Klaim E-Voucher" />
    
                            <div class="datetimepicker"></div>
                    
                            @if ($errors->has('voucher_expired'))
                                <small id="voucher_expired" class="form-text text-danger">
                                    {{ $errors->first('voucher_expired') }}
                                </small>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-rounded btn-primary btn-block">Simpan Perubahan</button>

                    </form>

                    <hr class="my-4" />

                    <div class="form-group">
                        <form method="post" role="form" action="{{ route('product.delete') }}">
                            {{ csrf_field() }}

                            <input type="hidden" name="product" value="{{ $product->id }}" />

                            <button type="submit" class="btn btn-rounded btn-outline-primary btn-block">Hapus Produk</button>
                        </form>
                    </div>
                </div>

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
        url: '{{ route("product.photo.edit.add") }}',
        paramName: 'file',
        maxFilesize: 2, // MB
        maxFiles: {{ (5 - $productPhoto->count()) }},
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
                            $.post('{{ route("product.photo.edit.delete") }}', { file_name:file_up_names[i].serverFileName, '_token': document.querySelectorAll('input[name=_token]')[0].value }, function(data,status) { });
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

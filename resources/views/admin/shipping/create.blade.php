@extends('layouts.admin')

@section('content')

    <div class="ks-column ks-page">
        <div class="ks-page-header">
            <section class="ks-title">
                <h3>Tambah {{ $pageTitle }}</h3>
            </section>
        </div>

        <div class="ks-page-content">
            <div class="ks-page-content-body">
                <div class="ks-nav-body-wrapper">
                    <div class="container-fluid">

				    @if (session('status'))
				    	<div class="alert alert-success ks-solid-light ks-active-border mb-2" role="alert">
				            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
				                <span aria-hidden="true" class="la la-close"></span>
				            </button>
				            {{ session('status') }}
				        </div>
				    @endif

				    @if (session('warning'))
				        <div class="alert alert-danger ks-solid-light ks-active-border mb-2" role="alert">
				            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
				                <span aria-hidden="true" class="la la-close"></span>
				            </button>
				            {{ session('warning') }}
				        </div>
				    @endif
				    
                        <div class="row">

                        	<div class="col-lg-12">
	                			<div class="card panel">
	                                <div class="card-block">

					                    <form method="post" action="{{ route('admin.'.$page.'.store') }}" enctype="multipart/form-data">
					              
					                        {{ csrf_field() }}

					                        <div class="form-group{{ $errors->has('shipper_name') ? ' has-danger' : '' }}">
					                            <label>Nama Pengirim</label>
					                            <input type="text"
					                            		name="shipper_name" 
					                            		value="{{ $shipper_name }}"
														class="form-control"
														readonly
				                                    	data-validation-error-msg="Nama Pengirim harus di isi."
					                                	placeholder="Nama Pengirim">

											@if ($errors->has('shipper_name'))
												<small class="form-text text-danger">{{ $errors->first('shipper_name') }}</small>
											@endif
					                        </div>

					                        <div class="form-group{{ $errors->has('shipper_address') ? ' has-danger' : '' }}">
					                            <label>Alamat Pengirim</label>
					                            <input type="text"
					                            		name="shipper_address" 
					                            		value="{{ $shipper_address }}"
														class="form-control"
														readonly
				                                    	data-validation-error-msg="Alamat Pengirim harus di isi."
					                                	placeholder="Alamat Pengirim">

											@if ($errors->has('shipper_address'))
												<small class="form-text text-danger">{{ $errors->first('shipper_address') }}</small>
											@endif
					                        </div>

					                        <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
					                            <label>Nama Penerima</label>
					                            <input type="text"
					                            		name="name" 
					                            		value="{{ old('name') }}"
					                                	class="form-control"
				                                    	data-validation="required"
				                                    	data-validation-error-msg="Nama Penerima harus di isi."
					                                	placeholder="Nama Penerima">

											@if ($errors->has('name'))
												<small class="form-text text-danger">{{ $errors->first('name') }}</small>
											@endif
					                        </div>

											<div class="form-group{{ $errors->has('address') ? ' has-danger' : '' }}">
												<label>Alamat</label>
												<textarea name="address" 
														class="form-control"
														id="address"
														placeholder="Alamat"
														data-validation="required"
														data-validation-error-msg="Alamat harus di isi."
														rows="5">{{ old('address') }}</textarea>
											
											@if ($errors->has('address'))
												<small id="address" class="form-text text-danger">
													{{ $errors->first('address') }}
												</small>
											@endif
											</div>
					
											<div class="form-group{{ $errors->has('provinsi_id') ? ' has-danger' : '' }}">
												<label>Provinsi</label>
												<select id="provinsi"
														name="provinsi_id"
														data-validation="required"
														data-validation-error-msg="Provinsi harus di isi."
														class="form-control ks-select-placeholder-single">
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
					
											<div class="form-group{{ $errors->has('kabupaten_id') ? ' has-danger' : '' }}">
												<label>Kota / Kabupaten</label>
												<select id="kabupaten"
													name="kabupaten_id"
													data-validation="required"
													data-validation-error-msg="Kabupaten harus di isi."
													class="form-control ks-select-placeholder-single">
													<option value="">Kota / Kabupaten</option>
												</select>
											
											@if ($errors->has('kabupaten_id'))
												<small id="kabupaten_id" class="form-text text-danger">
													{{ str_replace('kabupaten_id', 'Kota / Kabupaten', $errors->first('kabupaten_id')) }}
												</small>
											@endif
											</div>
					
											<div class="form-group{{ $errors->has('kecamatan_id') ? ' has-danger' : '' }}">
												<label>Kecamatan</label>
												<select id="kecamatan"
													name="kecamatan_id"
													data-validation="required"
													data-validation-error-msg="Kecamatan harus di isi."
													class="form-control ks-select-placeholder-single">
													<option value="">Kecamatan</option>
												</select>
											
											@if ($errors->has('kecamatan_id'))
												<small id="kecamatan_id" class="form-text text-danger">
													{{ str_replace('kecamatan_id', 'Kecamatan', $errors->first('kecamatan_id')) }}
												</small>
											@endif
											</div>
					
											<div class="form-group{{ $errors->has('postal_code') ? ' has-danger' : '' }}">
												<label>Kode Pos</label>
												<input type="text"
														name="postal_code"
														class="form-control"
														id="postal_code"
														placeholder="Kode Pos"
				                                    	data-validation="required"
				                                    	data-validation-error-msg="Kode Pos harus di isi."
														value="{{ old('postal_code') }}">
											
											@if ($errors->has('postal_code'))
												<small id="postal_code" class="form-text text-danger">
													{{ $errors->first('postal_code') }}
												</small>
											@endif
											</div>
					
											<div class="form-group{{ $errors->has('transaction') ? ' has-danger' : '' }}">
												<label>Nilai Transaksi</label>
												<input type="text"
														name="transaction"
														class="form-control"
														id="transaction"
														placeholder="Nilai Transaksi"
				                                    	data-validation="required"
				                                    	data-validation-error-msg="Nilai Transaksi harus di isi."
														value="{{ old('transaction') }}">
											
											@if ($errors->has('transaction'))
												<small id="transaction" class="form-text text-danger">
													{{ $errors->first('transaction') }}
												</small>
											@endif
											</div>

					                        <div class="form-group">
							                    <button type="submit" class="btn btn-success btn-block ks-split">
								                    <span class="la la-check ks-icon"></span>
								                    <span class="ks-text"><strong>Tambah</strong> {{ $pageTitle }}</span>
							                    </button>
					                        </div>
					                    </form>

	                                </div>
	                            </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
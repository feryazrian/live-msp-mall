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

					                        <div class="form-group{{ $errors->has('id') ? ' has-danger' : '' }}">
					                            <label>Nomor Resi</label>
					                            <input type="text"
					                            		name="id" 
					                            		value="{{ $item->id }}"
					                                	class="form-control"
														data-validation="required"
														readonly
				                                    	data-validation-error-msg="Nomor Resi harus di isi."
					                                	placeholder="Nomor Resi">

					                            @if ($errors->has('id'))
					                            	<small class="form-text text-danger">{{ $errors->first('id') }}</small>
					                            @endif
					                        </div>

					                        <div class="form-group{{ $errors->has('description') ? ' has-danger' : '' }}">
					                            <label>Deskripsi</label>
					                            <input type="text"
					                            		name="description" 
					                            		value="{{ old('description') }}"
					                                	class="form-control"
				                                    	data-validation="required"
				                                    	data-validation-error-msg="Deskripsi harus di isi."
					                                	placeholder="Deskripsi">

					                            @if ($errors->has('description'))
					                            	<small class="form-text text-danger">{{ $errors->first('description') }}</small>
					                            @endif
					                        </div>

											<div class="form-group{{ $errors->has('kabupaten_id') ? ' has-danger' : '' }}">
												<label>Kota / Kabupaten</label>
												<select class="form-control ks-select-placeholder-single"
														name="kabupaten_id" 
														data-validation="required"
														data-validation-error-msg="Kota / Kabupaten harus di isi.">
													<option value="">Kota / Kabupaten Tujuan</option>
													
													@php $match=''; @endphp
													@foreach ($places as $kabupaten)
													@if ($match != $kabupaten->provinsi->name)
														<optgroup label="{{ $kabupaten->provinsi->name }}">
													@endif
															<option value="{{ $kabupaten->id }}">{{ $kabupaten->name }}</option>
													@if ($match != $kabupaten->provinsi->name)
														</optgroup>
													@endif
													@php $match = $kabupaten->provinsi->name; @endphp
													@endforeach
												</select>
											
											@if ($errors->has('kabupaten_id'))
												<small id="kabupaten_id" class="form-text text-danger">
													{{ str_replace('kabupaten_id', 'Kota / Kabupaten', $errors->first('kabupaten_id')) }}
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
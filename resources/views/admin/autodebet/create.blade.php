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
											<input type="hidden" name="result_type" id="result-type">
											<input type="hidden" name="result_data" id="result-data">

											<div class="form-group{{ $errors->has('user_id') ? ' has-danger' : '' }}">
												<label>Pilih user :</label><br>
												<select class="form-control ks-select-placeholder-single" name="user_id" >
													@foreach ($users as $item)
														<option name="users[]" value="{{$item->id}}">{{$item->username}} [{{$item->email}}]</option>
													<br/>
													@endforeach
												</select>

												<br>
											
												@if ($errors->has('user_id'))
													<small id="user_id" class="form-text text-danger">
														{{ $errors->first('user_id') }}
													</small>
												@endif
											</div>

											<div class="form-group{{ $errors->has('jumlah') ? ' has-danger' : '' }}">
												<label>Jumlah</label>
											
												<input type="text"
														id="data-jumlah"
					                            		name="jumlah" 
					                            		value="{{ old('jumlah') }}"
					                                	class="form-control"
														data-validation="required|number"
				                                    	data-validation-error-msg="Jumlah harus di isi."
					                                	placeholder="jumlah">

					                            @if ($errors->has('jumlah'))
					                            	<small class="form-text text-danger">{{ $errors->first('jumlah') }}</small>
					                            @endif
											</div>

											{{-- <div class="form-group{{ $errors->has('tgl_auto_debet') ? ' has-danger' : '' }}">
												<label>Tanggal Auto Debet</label>
												<input type="text"
														name="tgl_auto_debet"
					                            		value="{{ old('tgl_auto_debet') }}"
														class="form-control calendar"
														data-validation="required"
														data-validation-error-msg="tanggal auto debet harus di isi."
														data-enable-time="true"
														data-time_24hr="true"
														data-enable-seconds="true"
														placeholder="Tanggal Auto Debet">

												@if ($errors->has('tgl_auto_debet'))
													<small class="form-text text-danger">{{ $errors->first('tgl_auto_debet') }}</small>
												@endif
											</div> --}}

											{{-- <div class="form-group{{ $errors->has('keterangan') ? ' has-danger' : '' }}">
												<label>Keterangan</label>
												<textarea name="keterangan"
														required="required" 
														id="ks-summernote-editor-default"
														>{{ old('keterangan') }}</textarea>

												@if ($errors->has('keterangan'))
													<small class="form-text text-danger">{{ $errors->first('keterangan') }}</small>
												@endif
											</div> --}}

											
											<div class="form-group{{ $errors->has('status') ? ' has-danger' : '' }}">
												<label>Status</label>
												<select class="form-control ks-select-placeholder-single" name="status" >
													<option value="0" selected>Unapproved</option>
													<option value="1">Approved</option>
												</select>

												@if ($errors->has('status'))
													<small class="form-text text-danger">{{ $errors->first('status') }}</small>
												@endif
											</div>

				                       		<div class="form-group">
							                    <button type="submit" id="btnSubmit" class="btn btn-success btn-block ks-split"  data-transaction="{{ config('app.balance_code') }}">
								                    <span class="la la-check ks-icon"></span>
								                    <span class="ks-text"><strong>Tambah</strong> {{ $pageTitle }}</span>
							                    </button>
											</div>

										</form>
										<script type="text/javascript" src="/public/assets/js/ppob.js"></script>

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
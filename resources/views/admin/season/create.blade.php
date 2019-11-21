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

					                        <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
					                            <label>Nama</label>
					                            <input type="text"
					                            		name="name" 
					                            		value="{{ old('name') }}"
					                                	class="form-control"
				                                    	data-validation="required"
				                                    	data-validation-error-msg="Nama harus di isi."
					                                	placeholder="Nama">

					                            @if ($errors->has('name'))
					                            	<small class="form-text text-danger">{{ $errors->first('name') }}</small>
					                            @endif
					                        </div>

					                        <div class="form-group{{ $errors->has('background') ? ' has-danger' : '' }}">
					                            <label>Background <small class="ml-2">Tinggi 520px & Lebar 1680px</small></label>
				                                <div id="previmage3"></div>

				                                <div class="custom-file">
				                                    <label class="btn btn-outline-success ks-btn-file">
				                                    	<span class="la la-cloud-upload ks-icon"></span>
				                                        <span class="ks-text">Pilih Background</span>
				                                        <input type="file"
				                                        		name="background"
						                                    	data-validation="required"
						                                    	data-validation-error-msg="Cover harus di isi."
				                                        		id="choseimage3"
				                                        		class="custom-file-input">
				                                    </label>

				                                    <span class="custom-file-control"></span>
				                                </div>

					                            @if ($errors->has('background'))
						                            <small class="form-text text-danger">{{ $errors->first('background') }}</small>
					                            @endif
											</div>
											
											<div class="form-group{{ $errors->has('expired') ? ' has-danger' : '' }}">
												<label>Batas Waktu</label>
												<input type="text"
														name="expired"
					                            		value="{{ old('expired') }}"
														class="form-control calendar"
														data-validation="required"
														data-validation-error-msg="Batas Waktu harus di isi."
														data-enable-time="true"
														data-time_24hr="true"
														data-enable-seconds="true"
														placeholder="Batas Waktu">

												@if ($errors->has('expired'))
													<small class="form-text text-danger">{{ $errors->first('expired') }}</small>
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
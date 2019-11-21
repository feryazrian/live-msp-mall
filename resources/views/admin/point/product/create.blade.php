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
											
					                        <div class="form-group{{ $errors->has('price') ? ' has-danger' : '' }}">
					                            <label>Harga Point</label>
					                            <input type="text"
					                            		name="price" 
					                            		value="{{ old('price') }}"
					                                	class="form-control"
				                                    	data-validation="required|number"
				                                    	data-validation-error-msg="Harus di isi angka."
					                                	placeholder="Harga Point">

					                            @if ($errors->has('price'))
					                            	<small class="form-text text-danger">{{ $errors->first('price') }}</small>
					                            @endif
											</div>
											
					                        <div class="form-group{{ $errors->has('point') ? ' has-danger' : '' }}">
					                            <label>Jumlah Point</label>
					                            <input type="text"
					                            		name="point" 
					                            		value="{{ old('point') }}"
					                                	class="form-control"
				                                    	data-validation="required|number"
				                                    	data-validation-error-msg="Harus di isi angka."
					                                	placeholder="Jumlah Point">

					                            @if ($errors->has('point'))
					                            	<small class="form-text text-danger">{{ $errors->first('point') }}</small>
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
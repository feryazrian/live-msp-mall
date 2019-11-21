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

					                        <div class="form-group{{ $errors->has('position') ? ' has-danger' : '' }}">
					                            <label>Position</label>
				                                <select class="form-control ks-select-placeholder-single"
				                                		name="position" 
				                                    	data-validation="required"
				                                    	data-validation-error-msg="Position harus di isi.">
				                                    <option></option>
				                                @foreach ($positions as $position)
			                                        <option value="{{ $position->id }}">{{ $position->name.' ('.$position->resolution.')' }}</option>
			                                    @endforeach
				                                </select>

					                            @if ($errors->has('position'))
					                            	<small class="form-text text-danger">{{ $errors->first('position') }}</small>
					                            @endif
					                        </div>

					                        <div class="form-group{{ $errors->has('photo') ? ' has-danger' : '' }}">
					                            <label>Foto</label>
				                                <div id="previmage"></div>

				                                <div class="custom-file">
				                                    <label class="btn btn-outline-success ks-btn-file">
				                                    	<span class="la la-cloud-upload ks-icon"></span>
				                                        <span class="ks-text">Pilih Foto</span>
				                                        <input type="file"
				                                        		name="photo"
						                                    	data-validation="required"
						                                    	data-validation-error-msg="Foto harus di isi."
				                                        		id="choseimage"
				                                        		class="custom-file-input">
				                                    </label>

				                                    <span class="custom-file-control"></span>
				                                </div>

					                            @if ($errors->has('photo'))
						                            <small class="form-text text-danger">{{ $errors->first('photo') }}</small>
					                            @endif
					                        </div>

					                        <div class="form-group{{ $errors->has('url') ? ' has-danger' : '' }}">
					                            <label>URL</label>
					                            <input type="text"
					                            		name="url" 
					                            		value="{{ old('url') }}"
					                                	class="form-control"
				                                    	data-validation="required"
				                                    	data-validation-error-msg="URL harus di isi."
					                                	placeholder="URL">

					                            @if ($errors->has('url'))
					                            	<small class="form-text text-danger">{{ $errors->first('url') }}</small>
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
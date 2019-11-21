@extends('layouts.admin')

@section('content')

    <div class="ks-column ks-page">
        <div class="ks-page-header">
            <section class="ks-title">
                <h3>Ubah {{ $pageTitle }}</h3>
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

					                    <form method="post" action="{{ route('admin.'.$page.'.update') }}" enctype="multipart/form-data">
					              
					                        {{ csrf_field() }}

	                    					<input type="hidden" name="id" value="{{ $item->id }}">

					                        <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
					                            <label>Nama</label>
					                            <input type="text"
					                            		name="name" 
					                            		value="{{ $item->name }}"
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
					                            <div>
					                                <select class="form-control ks-select-placeholder-single"
					                                		name="position" 
					                                    	data-validation="required"
					                                    	data-validation-error-msg="Position harus di isi.">
					                                    <option></option>
					                                @foreach ($positions as $position)
				                                        <option value="{{ $position->id }}" @if($position->id == $item->position_id) selected="selected" @endif >{{ $position->name.' ('.$position->resolution.')' }}</option>
				                                    @endforeach
					                                </select>
					                            </div>

					                            @if ($errors->has('position'))
					                            	<small class="form-text text-danger">{{ $errors->first('position') }}</small>
					                            @endif
					                        </div>

					                        <div class="form-group{{ $errors->has('photo') ? ' has-danger' : '' }}">
					                            <label>Foto</label>
				                                <div id="previmage"></div>

				                                @if (!empty($item->photo))
				                                <div><img src="{{ asset('uploads/'.$directory.'/'.$item->photo) }}" width="100%" style="max-width: 300px;" class="mb-3" id="currentimage"></div>
				                                @endif

				                                <div class="custom-file">
				                                    <label class="btn btn-outline-success ks-btn-file">
				                                    	<span class="la la-cloud-upload ks-icon"></span>
				                                        <span class="ks-text">Pilih Foto</span>
				                                        <input type="file"
				                                        		name="photo"
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
					                            		value="{{ $item->url }}"
					                                	class="form-control"
				                                    	data-validation="required"
				                                    	data-validation-error-msg="URL harus di isi."
					                                	placeholder="URL">

					                            @if ($errors->has('url'))
					                            	<small class="form-text text-danger">{{ $errors->first('url') }}</small>
					                            @endif
					                        </div>

					                        <div class="row my-0 py-2">
					                        	<div class="col-md-6">
							                        <div class="form-group">
							                            <label>Diterbitkan</label>
							                            <div class="text-dark">
															<span class="mr-2">{{ $item->created_at }}</span>
															<small>{{ $item->created_at->diffForHumans() }}</small>
							                            </div>
							                        </div>
					                        	</div>
					                        	<div class="col-md-6">
							                        <div class="form-group">
							                            <label>Diperbarui</label>
							                            <div class="text-dark">
															<span class="mr-2">{{ $item->updated_at }}</span>
															<small>{{ $item->updated_at->diffForHumans() }}</small>
							                            </div>
							                        </div>
					                        	</div>
					                        </div>

					                        <div class="row my-0">
					                        	<div class="col-md-6">
						                            <label>Author</label>
						                            <div>
										                <a href="#" class="ks-user text-dark">
		                                                    <div class="ks-avatar d-inline-block align-top mr-2">
		                                                        <img src="{{ asset('/uploads/photos/'.$item->user->photo) }}" width="40" height="40" class="ks-avatar rounded-circle">
		                                                    </div>
	                                                        <div class="ks-body d-inline-block">
	                                                            <div class="ks-name">{{ $item->user->name }}</div>
	                                                            <small class="ks-text">{{ '@'.$item->user->username }}</small>
	                                                        </div>
	                                                    </a>
						                            </div>
					                        	</div>
					                        	<div class="col-md-6 align-bottom">
							                        <div class="mt-3 pt-2">
									                    <button class="btn btn-success btn-block ks-split">
										                    <span class="la la-check ks-icon"></span>
										                    <span class="ks-text">Simpan Perubahan</span>
									                    </button>
							                        </div>
					                        	</div>
					                        </div>

					                    </form>

					                    <hr>
				                    	
					                    <form method="post" action="{{ route('admin.'.$page.'.delete') }}">
					                        {{ csrf_field() }}
					                        <input type="hidden" name="id" value="{{ $item->id }}">

						                    <button type="submit" class="btn btn-outline-danger btn-block ks-split">
							                    <span class="la la-trash ks-icon"></span>
							                    <span class="ks-text"><strong>Hapus</strong> {{ $pageTitle }}</span>
						                    </button>
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
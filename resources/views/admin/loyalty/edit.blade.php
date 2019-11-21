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
											
					                        <div class="form-group{{ $errors->has('point') ? ' has-danger' : '' }}">
					                            <label>Point Minimal</label>
					                            <input type="text"
					                            		name="point" 
					                            		value="{{ $item->point }}"
					                                	class="form-control"
				                                    	data-validation="required|number"
				                                    	data-validation-error-msg="Harus di isi angka."
					                                	placeholder="Point Minimal">

					                            @if ($errors->has('point'))
					                            	<small class="form-text text-danger">{{ $errors->first('point') }}</small>
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
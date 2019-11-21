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

					                        <div class="form-group mb-1">
												<label>Informasi Penarikan</label>
											</div>
											
					                        <div class="form-group">
												<small>Jumlah Pencairan Saldo</small>
												<div>{{ 'Rp '.number_format($item->balance,0,',','.') }}</div>
											</div>
											
					                        <div class="form-group">
												<small>Nama Bank Tujuan</small>
												<div>{{ $item->bank_name }}</div>
											</div>
											
					                        <div class="form-group">
												<small>Atas Nama</small>
												<div>{{ $item->bank_holder }}</div>
											</div>
											
					                        <div class="form-group">
												<small>Nomor Rekening</small>
												<div>{{ $item->bank_number }}</div>
											</div>

											<hr>
											
					                        <div class="form-group{{ $errors->has('status') ? ' has-danger' : '' }}">
					                            <label>Status</label>
					                            <div>
					                                <select class="form-control ks-select-placeholder-single"
					                                		name="status" 
					                                    	data-validation="required"
					                                    	data-validation-error-msg="Status harus di isi.">
					                                    <option value="0" @if($item->status == 0) selected="selected" @endif>Menunggu</option>
														<option value="1" @if($item->status == 1) selected="selected" @endif >Disetujui</option>
														<option value="2" @if($item->status == 2) selected="selected" @endif >Ditolak</option>
					                                </select>
					                            </div>

					                            @if ($errors->has('position'))
					                            	<small class="form-text text-danger">{{ $errors->first('position') }}</small>
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
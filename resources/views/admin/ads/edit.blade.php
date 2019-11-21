@extends('layouts.admin')

@section('content')

    <div class="ks-column ks-page">
        <div class="ks-page-header">
            <section class="ks-title">
                <h3>Detail {{ $pageTitle }}</h3>
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

					                    <form method="post" action="#" enctype="multipart/form-data">
					              
					                        {{ csrf_field() }}

	                    					<input type="hidden" name="id" value="{{ $item->id }}">

					                        <div class="form-group mb-1">
												<label>Informasi Pengirim</label>
											</div>
											
					                        <div class="form-group">
												<small>Nama Lengkap</small>
												<div>{{ $item->name }}</div>
											</div>
											
					                        <div class="form-group">
												<small>Alamat E-mail</small>
												<div>{{ $item->email }}</div>
											</div>
											
					                        <div class="form-group">
												<small>Nomor Telepon</small>
												<div>{{ $item->phone }}</div>
											</div>
											
					                        <div class="form-group">
												<small>Posisi Iklan</small>
												<div>{{ $item->position->name }}</div>
											</div>
											
					                        <div class="form-group">
												<small>Informasi Produk yang akan di Iklankan</small>
												<div>{{ $item->content }}</div>
											</div>

											<hr>

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
												<div class="col-md-6 align-bottom">
													<div class="mt-3 pt-2">
														<a href="{{ 'mailto:'.$item->email }}" class="btn btn-success btn-block ks-split">
															<span class="la la-envelope ks-icon"></span>
										                    <div class="ks-text pt-2">Email Pengirim</div>
														</a>
													</div>
												</div>
					                        	<div class="col-md-6 align-bottom">
							                        <div class="mt-3 pt-2">
									                    <a href="{{ 'tel:'.$item->phone }}" class="btn btn-success btn-block ks-split">
										                    <span class="la la-phone ks-icon"></span>
										                    <div class="ks-text pt-2">Telpon Pengirim</div>
														</a>
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
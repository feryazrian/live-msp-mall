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
											<div class="row m-0">
												<div class="col-md-6">
													<div class="form-group{{ $errors->has('id') ? ' has-danger' : '' }}">
														<label>ID</label>
														<input type="text"
																name="id" 
																value="{{ $item->id }}"
																readonly
																class="form-control"
																>
														@if ($errors->has('id'))
															<small class="form-text text-danger">{{ $errors->first('id') }}</small>
														@endif
													</div>
												</div>

												<div class="col-md-6">
													<div class="form-group{{ $errors->has('product') ? ' has-danger' : '' }}">
														<label>Produk</label>
														<input type="text"
																name="product" 
																value="{{ $pricelistdetail->pulsa_op. " " .$pricelistdetail->pulsa_nominal. " ".$pricelistdetail->masaaktif."hari" }}"
																readonly
																class="form-control"
																>
														@if ($errors->has('product'))
															<small class="form-text text-danger">{{ $errors->first('product') }}</small>
														@endif
													</div>
												</div>
											</div>

											<div class="row m-0">
												<div class="col-md-6">
													<div class="form-group{{ $errors->has('ref_id') ? ' has-danger' : '' }}">
														<label>Reff ID</label>
														<input type="text"
																name="ref_id" 
																value="{{ $item->ref_id }}"
																readonly
																class="form-control"
																>
														@if ($errors->has('ref_id'))
															<small class="form-text text-danger">{{ $errors->first('ref_id') }}</small>
														@endif
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label>Nomor Customer</label>
														<input type="text"
																value="{{ $item->cust_number }}"
																readonly
																class="form-control">
													</div>
												</div>
												
											</div>

											<div class="row m-0">
												<div class="col-md-6">
													<div class="form-group">
														<label>Username</label>
														<input type="text"
																value="{{ $users->username }}"
																readonly
																class="form-control">
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label>Email</label>
														<input type="text"
																value="{{ $users->email }}"
																readonly
																class="form-control">
													</div>
												</div>
											</div>

											<div class="row m-0">
												<div class="col-md-6">
													<div class="form-group">
														<label>Tipe</label>
														<input type="text"
																value="{{ $ppob_types->name }}"
																readonly
																class="form-control">
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group{{ $errors->has('price') ? ' has-danger' : '' }}">
														<label>Price</label>
														<input type="text"
																name="price" 
																value="{{ $item->price }}"
																readonly
																class="form-control"
																>
														@if ($errors->has('price'))
															<small class="form-text text-danger">{{ $errors->first('price') }}</small>
														@endif
													</div>
												</div>
											</div>

											<div class="row m-0">
												<div class="col-md-6">
													<div class="form-group">
														<label>Order ID</label>
														<input type="text"
																value="{{ $transaction_payment->order_id }}"
																readonly
																class="form-control">
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label>Tipe Pembayaran</label>
														<input type="text"
																value="{{ $transaction_payment->payment_type }}"
																readonly
																class="form-control">
													</div>
												</div>
											</div>
	
											<div class="row m-0">
												<div class="col-md-6">
													<div class="form-group">
														<label>Waktu Transaksi</label>
														<input type="text"
																value="{{ $transaction_payment->transaction_time }}"
																readonly
																class="form-control">
													</div>
													
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label>Status</label>
														<select class="form-control ks-select-placeholder-single" name="status" >
															<option value="0" @if($item->status === 0) selected="selected" @endif>On Process</option>
															<option value="1" @if($item->status === 1) selected="selected" @endif>Approved</option>
															<option value="2" @if($item->status === 2) selected="selected" @endif>Failed</option>
														</select>
													</div>
												</div>
											</div>
										
											<div class="row m-0">
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
					                        	<div class="col-md-12 align-bottom">
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
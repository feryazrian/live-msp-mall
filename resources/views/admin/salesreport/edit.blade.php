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
										<label>ID Transaksi : {{$data["data_transaksi"]->id}}</label>
								
										{{-- {{$data["data_transaksi"]}}
										{{$data["data_merchant"]}}
										{{$data["data_transaction_product"]}} --}}

										<div class="row m-0">
											<div class="col-md-6">
												<div class="form-group">
													{{-- <label>{{ $data["data_transaksi"] }}</label> --}}
													<label>Customer</label>
													<input type="text"
															value="{{ $data["data_transaksi"]->cust_name . " [" . $data["data_transaksi"]->cust_username. "] [". $data["data_transaksi"]->cust_email . "]"   }}"
															readonly
															class="form-control">
												</div>
											</div>
										</div>
									
										<div class="detail">
											<div class="product-info table-responsive">
												<table class="table table-bordered">
													<thead style="text-align:center">
														<tr>
															<td style="min-width:130px;">Nama Merchant</td>
															<td style="min-width:130px;">Nama Produk</td>
															<td style="min-width:130px;">Harga</td>
															<td style="min-width:80px;">Jumlah</td>
															<td style="min-width:130px;">Nominal</td>
															<td style="min-width:130px;">Status</td>

														</tr>
													</thead>
													<tbody>
														@foreach ($data["data_transaction_product"] as $product)
															<tr>
															<td style="min-width:130px;" >
																	{{$product->merchant_name}} 
																</td>
																<td style="min-width:130px;">
																	{{$product->name}}
																	@if (!empty($product->notes))
																	<p class="notes bg-warning">{{ $product->notes }}</p>
																	@endif
																</td>
																<td class="text-right" style="min-width:130px;">Rp. {{ number_format($product->price,0,',','.') }}</td>
																<td style="text-align:center;min-width:80px;"> {{number_format($product->unit,0,',','.') }}</td>
																<td class="text-right" style="min-width:130px;">Rp. {{number_format($product->price * $product->unit ,0,',','.') }}</td>
																<td style="min-width:130px;text-align:center">
																	@if ($product->status == '0')
																		<span class="badge badge-warning">PENDING</span>
																	@endif
						
																	@if ($product->status == '1')
																		<span class="badge badge-info">Pembayaran Diterima</span>
																	@endif
						
																	@if ($product->status == '2')
																		<span class="badge badge-secondary">Konfirmasi Produk Diterima</span>
																	@endif
						
																	@if ($product->status== '3')
																		<span class="badge badge-dark">Ada Komplain</span>
																	@endif
						
																	@if ($product->status == '4')
																		<span class="badge badge-primary">Selesai Ulasan</span>
																	@endif
						
																	@if ($product->status == '5')
																		<span class="badge badge-success">Selesai</span>
																	@endif
						
																	@if ($product->status == '6' )
																		<span class="badge badge-danger">Digagalkan Merchant</span>
																	@endif
						
																	@if ($product->status > '6' )
																		<span class="badge badge-danger">Digagalkan Sistem</span>
																	@endif
																	<br>{{$product->cancel}}
																</td>
															</tr>
														@endforeach
														
														<tr>
															<td style="min-width:130px;">{{$data["data_transaksi"]->shipping_description}}</td>
															<td class="text-right" style="min-width:130px;"> - </td>
															<td class="text-right" style="min-width:130px;"> - </td>

															<td class="text-right" style="min-width:80px;"> - </td>
															<td class="text-right" style="min-width:130px;">Rp. {{ number_format($data["data_transaksi"]->shipping_price,0,',','.') }}</td>
															<td class="text-right" style="min-width:130px;"> </td>

														</tr>
														@if($data["data_transaksi"]->promo_type)
															<tr>
																<td style="min-width:130px;">{{$data["data_transaksi"]->promo_type. ' '. $data["data_transaksi"]->promo_name . ' ['.$data["data_transaksi"]->promo_code.']'}}</td>
																<td class="text-right" style="min-width:130px;"> - </td>
																<td class="text-right" style="min-width:130px;"> - </td>
																<td class="text-right" style="min-width:130px;"> - </td>
																<td class="text-right" style="min-width:130px;">- Rp. {{ number_format($data["data_transaksi"]->promo_price,0,',','.') }}</td>

																<td class="text-right" style="min-width:80px;"> - </td>
															</tr>
														@endif
														<tr>
															<td style="min-width:130px;">Total Tagihan</td>
															<td class="text-right" style="min-width:130px;"> - </td>
															<td class="text-right" style="min-width:130px;"> - </td>
															<td class="text-right" style="min-width:80px;"> - </td>
															<td class="text-right" style="min-width:130px;">Rp. {{number_format($item->total,0,',','.') }}</td>
															<td class="text-right" style="min-width:80px;"> </td>

														</tr>
														
														
													</tbody>
												</table>
											</div>
										</div>
										<label>Tujuan Pengiriman</label>

										<div class="detail">
											<div class="product-info table-responsive">
												<table class="table table-bordered">
													<thead>
														<tr>
															<td style="min-width:80px;">Nama Alamat</td>
															<td class="text-left" style="min-width:80px;">{{$data["data_transaksi"]->address_name}}</td>
														</tr>
													</thead>
													<tbody>
														<tr>
															<td style="min-width:80px;">Nama Penerima</td>
															<td class="text-left" style="min-width:80px;">{{$data["data_transaksi"]->first_name. ' '.$data["data_transaksi"]->last_name}}</td>
														</tr>
														<tr>
															<td style="min-width:80px;">Nomor Telepon</td>
															<td class="text-left" style="min-width:80px;">{{$data["data_transaksi"]->phone}}</td>
														</tr>
														<tr>
															<td style="min-width:80px;">Alamat</td>
															<td class="text-left" style="min-width:80px;">{{$data["data_transaksi"]->address}}</td>
														</tr>
														<tr>
															<td style="min-width:80px;">Kecamatan</td>
															<td class="text-left" style="min-width:80px;">{{$data["data_transaksi"]->nama_kecamatan}}</td>
														</tr>
														<tr>
															<td style="min-width:80px;">Kota/Kabupaten</td>
															<td class="text-left" style="min-width:80px;">{{$data["data_transaksi"]->nama_kabupaten}}</td>
														</tr>
														<tr>
															<td style="min-width:80px;">Provinsi</td>
															<td class="text-left" style="min-width:80px;">{{$data["data_transaksi"]->nama_provinsi}}</td>
														</tr>
														<tr>
															<td style="min-width:80px;">Kode Pos</td>
															<td class="text-left" style="min-width:80px;">{{$data["data_transaksi"]->postal_code}}</td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>

										<div>
											@if ($data["data_transaksi"]->transaction_status)
												STATUS : 
												@if($data["data_transaksi"]->transaction_status == "settlement" || $data["data_transaksi"]->transaction_status == "capture")
													<span class="badge badge-success">{{$data["data_transaksi"]->transaction_status}}</span>
												@elseif($data["data_transaksi"]->transaction_status == "cancel" || $data["data_transaksi"]->transaction_status == "expire")
													<span class="badge badge-danger">{{$data["data_transaksi"]->transaction_status}}</span>
												@else
													<span class="badge badge-warning">{{$data["data_transaksi"]->transaction_status}}</span>
												@endif
											@endif

										</div>
										<div>
											@if ($data["data_transaksi"]->transaction_time)
												WAKTU TRANSAKSI  : {{$data["data_transaksi"]->transaction_time}}
											@endif
										</div>
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
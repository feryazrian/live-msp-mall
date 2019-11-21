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
										<label>ID Transaksi : {{$data["id"]}} </label>

										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label>Pembeli</label>
													<input type="text"
														value="{{ $data["voucherTransaction"]->user_name . " [" . $data["voucherTransaction"]->user_username. "] [". $data["voucherTransaction"]->user_email . "]"   }}"
														readonly
														class="form-control">
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label>Penjual</label>
													<input type="text"
														value="{{ $data["buyer"]->buyer_name . " [" . $data["buyer"]->buyer_username. "] [". $data["buyer"]->buyer_email . "]"   }}"
														readonly
														class="form-control">
												</div>
											</div>
										</div>

										<div class="detail">
											<div class="product-info table-responsive">
												<table class="table table-bordered">
													<thead>
														<tr>
															<td style="min-width:130px;">Nama Produk</td>
															<td class="text-right" style="min-width:130px;">Harga</td>
															<td class="text-right" style="min-width:80px;">Jumlah</td>
															<td class="text-right" style="min-width:130px;">Nominal</td>
														</tr>
													</thead>
											
													<tbody>
														<tr>
															<td>
																<div class="d-inline-block mr-1">
																	{{ $data["voucher_name"] }}
																</div>
															</td>
															<td class="text-right">
																{{ $data["voucher_price"] / $data["voucher_unit"]  }}
															</td>
															<td class="text-right">{{ $data["voucher_unit"] }}</td>
															<td class="text-right">
																{{ $data["voucher_price"]  }}
															</td>
														</tr>
														<tr>
															<td><b>Total Tagihan</b></td>
															<td></td>
															<td></td>
															<td class="text-right">
																{{ $data["voucher_price"]  }}
															</td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>
					
									
	
										@if ($data["voucher_status"] == 1)
										<div class="product-info table-responsive mt-4">
											<table class="table table-bordered">
												<thead>
													<tr>
														<td style="min-width:130px;">Kode E-Voucher</td>
														<td class="text-center" style="min-width:130px;">Status</td>
														<td class="text-center" style="min-width:130px;">Waktu Klaim</td>
													</tr>
												</thead>
												<tbody>
												@foreach ($data["vouchers"] as $voucher)
													<tr>
														<td>
															<div class="d-inline-block mr-1">
																{{ $voucher["code"] }}
															</div>
														</td>
														<td class="text-center">{!! $voucher["status"] !!}</td>
														<td class="text-center">{{ $voucher["timestamp"] }}</td>
													</tr>
												@endforeach
												</tbody>
											</table>
										</div>
										@endif

										<label>Voucher Status :
											@if ($data["voucher_status"] == 0)
												<span class="badge badge-warning"> Menunggu Pembayaran </span>
											@elseif($data["voucher_status"] == 1)
												<span class="badge badge-success"> Transaksi Sukses</span>
											@elseif($data["voucher_status"] == 7)
												<span class="badge badge-danger"> Transaksi dibatalkan sistem </span>
											@else
												<span class="badge badge-danger"> </span>
											@endif
										</label><br>

										<label>Voucher Expired : {{$data["voucher_expired"]}} </label>
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
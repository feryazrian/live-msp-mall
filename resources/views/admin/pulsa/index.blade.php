@extends('layouts.admin')

@section('content')

    <div class="ks-column ks-page">
        <div class="ks-page-header">
            <section class="ks-title">
				<h3>{{ $pageTitle }}</h3>
			</section>
        </div>

        <div class="ks-page-content">
            <div class="ks-page-content-body ks-content-nav">
                <div class="ks-nav-body">
                    <div class="ks-nav-body-wrapper">
                        <div class="container-fluid">
							<div class="ks-widget-payment-simple-amount-item col-lg-12 col-md-12">
								<h1>Total Transaksi Keseluruhan Pulsa</h1>
							</div>
							<div class="row">
								<div class="col-lg-3 col-md-6">
									<div class="card ks-widget-payment-simple-amount-item ks-green">
										<div class="payment-simple-amount-item-icon-block">
											<span class="ks-icon-combo-chart ks-icon"></span>
										</div>
	
										<div class="payment-simple-amount-item-body">
											<div class="payment-simple-amount-item-amount">
												<span class="ks-amount"> {{ $monswallet }} ( Rp. {{ number_format($sum_monswallet, 2) }} )</span>
											</div>
											<div class="payment-simple-amount-item-description">
												Monswallet Berhasil
											</div>
										</div>
									</div>
								</div>
								<div class="col-lg-3 col-md-6">
									<div class="card ks-widget-payment-simple-amount-item ks-pink">
										<div class="payment-simple-amount-item-icon-block">
											<span class="ks-icon-combo-chart ks-icon"></span>
										</div>
	
										<div class="payment-simple-amount-item-body">
											<div class="payment-simple-amount-item-amount">
												<span class="ks-amount">{{ $monswallet_failed }}  ( Rp. {{ number_format($sum_monswallet_failed, 2) }} )</span>
											</div>
											<div class="payment-simple-amount-item-description">
												Monswallet Gagal
											</div>
										</div>
									</div>
								</div>
								<div class="col-lg-3 col-md-6">
									<div class="card ks-widget-payment-simple-amount-item ks-green">
										<div class="payment-simple-amount-item-icon-block">
											<span class="ks-icon-combo-chart ks-icon"></span>
										</div>
	
	
										<div class="payment-simple-amount-item-body">
											<div class="payment-simple-amount-item-amount">
												<span class="ks-amount"> {{ $lifepoint }}  ( Rp. {{ number_format($sum_lifepoint, 2) }} )</span>
											</div>
											<div class="payment-simple-amount-item-description">
												LifePoint Berhasil
											</div>
										</div>
									</div>
								</div>
								<div class="col-lg-3 col-md-6">
									<div class="card ks-widget-payment-simple-amount-item ks-pink">
										<div class="payment-simple-amount-item-icon-block">
											<span class="ks-icon-combo-chart ks-icon"></span>
										</div>
	
										<div class="payment-simple-amount-item-body">
											<div class="payment-simple-amount-item-amount">
												<span class="ks-amount">{{ $lifepoint_failed }} ( Rp. {{ number_format($sum_lifepoint_failed, 2) }} )</span>
											</div>
											<div class="payment-simple-amount-item-description">
												LifePoint Gagal
											</div>
										</div>
									</div>
								</div>
							</div>
							
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
						<br />
							<div class="row input-daterange">
								<div class="col-md-1">
								</div>
								<div class="col-md-4">
									<input type="text" name="from_date" id="from_date" class="form-control calendar" placeholder="From Date" readonly />
								</div>
								<div class="col-md-4">
									<input type="text" name="to_date" id="to_date" class="form-control calendar" placeholder="To Date" readonly />
								</div>
								<div class="col-md-3">
									<button type="button" name="filter" id="filter" class="btn btn-primary">Filter</button>
									<button type="button" name="refresh" id="refresh" class="btn btn-default">Refresh</button>
								</div>
							</div>
						<br />
                            <table id="ks-datatable" class="table responsive table-striped table-bordered" cellspacing="0" width="100%">
		                        <thead>
									<tr>
										{{-- <th>ID</th> --}}
										<th>Produk</th>
										<th>Informasi User</th>
										<th>Tipe Pembayaran</th>
										<th>Nomor Customer</th>
										<th>Harga</th>
										<th>Status</th>
										<th>Tgl Transaksi</th>
										<th width="10%"></th>
									</tr>
		                        </thead>
		                        <tfoot>
									<tr>
										{{-- <th>ID</th> --}}
										<th>Produk</th>
										<th>Informasi User</th>
										<th>Tipe Pembayaran</th>
										<th>Nomor Customer</th>
										<th>Harga</th>
										<th>Status</th>
										<th>Tgl Transaksi</th>
										<th width="10%"></th>
									</tr>
		                        </tfoot>
		                    </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
	    
	<!-- BEGIN DATATABLE SCRIPTS -->
	<script type="application/javascript">
	(function ($) {
	    $(document).ready(function() {
			load_data();
			function load_data(from_date = '', to_date = ''){
				var table = $('#ks-datatable').DataTable({
					lengthChange: true,
					processing: true,
					// buttons: [
					// 	'excelHtml5',
					// 	'pdfHtml5',
					// 	'csvHtml5',
					// 	'colvis'
					// ],
					ajax: {
						url:"{{ route('admin.'.$page.'.data') }}",
						data:{from_date:from_date, to_date:to_date}
					},
					order: [[ 7, "desc" ]],
					initComplete: function () {
						$('.dataTables_wrapper select').select2({
							minimumResultsForSearch: Infinity
						});
					},
					aoColumns: [
						// { "mData": "id" },
						{ "mData": "product"},
						{ "mData": "users" },
						{ "mData": "payment_type" },
						{ "mData": "cust_number" },
						{ "mData": "price" },
						{ "mData": "status" },
						{ "mData": "created_at" },
						{ "mData": "action" }
					]
				});
				table.buttons().container().appendTo( '#ks-datatable_wrapper .col-md-6:eq(0)' );
			}

			$('#filter').click(function(){
				var from_date = $('#from_date').val();
				var to_date = $('#to_date').val();
				if(from_date >  to_date)
				{
					alert('From date harus lebih kecil atau sama dengan To date');	
				}
				else if(from_date != '' &&  to_date != '')
				{
					$('#ks-datatable').DataTable().destroy();
					load_data(from_date, to_date);
				}
				else
				{
					alert('Both Date is required');
				}
			});

			$('#refresh').click(function(){
				$('#from_date').val('');
				$('#to_date').val('');
				$('#ks-datatable').DataTable().destroy();
				load_data();
			});
	    });
	})(jQuery);
	</script>
	<!-- END DATATABLE SCRIPTS -->

@endsection
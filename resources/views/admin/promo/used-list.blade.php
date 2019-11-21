@extends('layouts.admin')

@section('content')

<div class="ks-column ks-page">
	<div class="ks-page-header">
		<section class="ks-title">
			<h3>{{ $pageTitle }} <b>{{ $promo->code }}</b></h3>
		</section>
	</div>

	<div class="ks-page-content">
		<div class="ks-page-content-body ks-content-nav">
			<div class="ks-nav-body">
				<div class="ks-nav-body-wrapper">
					<div class="jumbotron py-4">
						<div class="row">
							<div class="col-md-6">
								<div class="row">
									<div class="col-md-4">
										<p>Nama Promo</p>
										<p>Tipe Promo</p>
										<p>Tipe Diskon</p>
										<p>Minimum Transaksi</p>
										<p>Maximum Diskon</p>
									</div>
									<div class="col-md-8">
										<p>: {{$promo->name}}</p>
										<p>: {{$promo->type->name}}</p>
										<p>: {{$promo->discount_type_id == 1 ? 'Cashback' : 'Diskon'}}</p>
										<p>: {{$promo->transaction_min}}</p>
										<p>: {{$promo->discount_max ? $promo->discount_max : $promo->discount_price}}</p>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="row">
									<div class="col-md-4">
										<p>Expired</p>
										<p>Kuota Harian</p>
										<p>Kuota Harian User</p>
										<p>Kuota Total User</p>
										<p>Kuota Total</p>
									</div>
									<div class="col-md-8">
										<p>: {{$promo->expired}}</p>
										<p>: {{$promo->quota}}</p>
										<p>: {{$promo->quota_user_day}}</p>
										<p>: {{$promo->quota_user_total}}</p>
										<p>: {{$promo->total_quota}}</p>
									</div>
								</div>
							</div>
						</div>
						<div id="chart" class="c3" style="max-height: 280px; position: relative;"></div>
					</div>
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

						<table id="ks-datatable" class="table responsive table-striped table-bordered" cellspacing="0"
							width="100%">
							<thead>
								<tr>
									<th>Order ID</th>
									<th>Customer</th>
									<th>Payment Type</th>
									<th>Payment Date</th>
									<th>Gross Amount</th>
									<th>Discount</th>
									<th>Total</th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th>Order ID</th>
									<th>Customer</th>
									<th>Payment Type</th>
									<th>Payment Date</th>
									<th>Gross Amount</th>
									<th>Discount</th>
									<th>Total</th>
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
	        var table = $('#ks-datatable').DataTable({
	            lengthChange: true,
	            /*buttons: [
	                'excelHtml5',
	                'pdfHtml5',
	                'csvHtml5',
	                'colvis'
	            ],*/
	            ajax: "{{ route('admin.'.$page.'.used-list-data', $id) }}",
	            order: [[ 0, "desc" ]],
	            initComplete: function () {
	                $('.dataTables_wrapper select').select2({
	                    minimumResultsForSearch: Infinity
	                });
	            },
	            aoColumns: [
					{ "mData": "id" },
	            	{ "mData": "customer" },
					{ "mData": "payment_type" },
					{ "mData": "payment_date" },
	            	{ "mData": "gross_amount" },
	            	{ "mData": "discount" },
	            	{ "mData": "total" }
	            ]
	        });
			table.buttons().container().appendTo( '#ks-datatable_wrapper .col-md-6:eq(0)' );
	    });
	})(jQuery);
</script>
<!-- END DATATABLE SCRIPTS -->

@endsection

@section('script')
	<script>
		$(document).ready(function (params) {
			var total = {!! json_encode($dataPayment) !!};
			var chart = c3.generate({
				data: {
					columns: total
					,
					type : 'donut',
					// onclick: function (d, i) { console.log("onclick", d, i); },
					// onmouseover: function (d, i) { console.log("onmouseover", d, i); },
					// onmouseout: function (d, i) { console.log("onmouseout", d, i); }
				},
				donut: {
					title: "Payment Method",
				},
				tooltip: {
					format: {
						value: function (value, ratio, id, index) { return value; }
					}
				}
			});
		})
	</script>
@endsection
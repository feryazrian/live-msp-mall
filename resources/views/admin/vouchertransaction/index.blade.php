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
								<h1>Voucher Transaction Report</h1>
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
								<div class="col-md-3">
									<input type="text" name="from_date" id="from_date" class="form-control calendar" placeholder="From Date" readonly />
								</div>
								<div class="col-md-3">
									<input type="text" name="to_date" id="to_date" class="form-control calendar" placeholder="To Date" readonly />
								</div>
								<div class="col-md-2">
									<select name="status" id="status"  class="form-control" >
										<option value="all">all</option>
										<option value="0">Menunggu Pembayaran</option>
										<option value="1">Transaksi Sukses</option>
										<option value="7">Transaksi dibatalkan sistem</option>

									</select>
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
										<th>ID</th>
										<th>Customer</th>
										<th>Voucher Name</th>
										<th>Voucher Unit</th>
										<th>Voucher Price</th>
										<th>Voucher Expired</th>
										<th>Voucher Status</th>
										<th>Waktu Transaksi</th>
										<th width="10%"></th>
									</tr>
		                        </thead>
		                        <tfoot>
									<tr>
										<th>ID</th>
										<th>Customer</th>
										<th>Voucher Name</th>
										<th>Voucher Unit</th>
										<th>Voucher Price</th>
										<th>Voucher Expired</th>
										<th>Voucher Status</th>
										<th>Waktu Transaksi</th>
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
			function load_data(from_date = '', to_date = '',status=''){
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
						data:{from_date:from_date, to_date:to_date,status : status}
					},
					order: [[ 0, "desc" ]],
					initComplete: function () {
						$('.dataTables_wrapper select').select2({
							minimumResultsForSearch: Infinity
						});
					},
					aoColumns: [
						{ "mData": "id" },
						{ "mData": "customer" },
						{ "mData": "voucher_name" },
						{ "mData": "voucher_unit" },
						{ "mData": "voucher_price" },
						{ "mData": "voucher_expired" },
						{ "mData": "voucher_status" },
						{ "mData": "updated_at" },
						{ "mData": "action" }
					]
				});
				table.buttons().container().appendTo( '#ks-datatable_wrapper .col-md-6:eq(0)' );
			}

			$('#filter').click(function(){
				var from_date = $('#from_date').val();
				var to_date = $('#to_date').val();
				var status = $('#status').val();

				// var date1 = new Date(from_date);
				// var date2 = new Date(to_date);
				// var diffDays = date2.getDate() - date1.getDate(); 
				if(from_date >  to_date)
				{
					alert('From date harus lebih kecil atau sama dengan To date');	
				}
				
				else if((from_date != '' &&  to_date != '') || status != '')
				{
					// alert(status);
					$('#ks-datatable').DataTable().destroy();
					load_data(from_date, to_date,status);

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
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
								<h1>List Coupon </h1>
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

                            <table id="ks-datatable" class="table responsive table-striped table-bordered" cellspacing="0" width="100%">
		                        <thead>
									<tr>
										<th>No</th>
										<th>User id</th>

										<th>Customer</th>
										<th>Code</th>
										{{-- <th width="10%"></th> --}}
									</tr>
		                        </thead>
		                        <tfoot>
									<tr>
										<th>No</th>
										<th>User id</th>

										<th>Customer</th>
										<th>Code</th>
										{{-- <th width="10%"></th> --}}
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
					order: [[ 0, "desc" ]],
					initComplete: function () {
						$('.dataTables_wrapper select').select2({
							minimumResultsForSearch: Infinity
						});
					},
					aoColumns: [
						{ "mData": "no" },
						{ "mData": "id" },
						{ "mData": "customer" },
						{ "mData": "code" },		
						// { "mData": "action" }
					]
				});
				table.buttons().container().appendTo( '#ks-datatable_wrapper .col-md-6:eq(0)' );
			}
	    });
	})(jQuery);
	</script>
	<!-- END DATATABLE SCRIPTS -->

@endsection
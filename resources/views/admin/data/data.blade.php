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

									<th>ID</th>
		                            <th>Product</th>
		                            <th>Ref ID</th>
		                            <th>Cust Number</th>
		                            <th>Tr Code</th>
		                            <th>Tr ID</th>
		                            <th>Price</th>
		                            <th>Status</th>
		                            <th>Created At</th>
		                            <th>Updated At</th>
									<th>Reff ID</th>
									<th>Serial Number</th>
		                            <th>Balance</th>
									<th>r balance</th>
									<th>pin</th>


		                            {{-- <th width="20%">Diperbarui</th> --}}
		                            <th width="10%"></th>
		                        </tr>
		                        </thead>
		                        <tfoot>
		                        <tr>
		                            <th>Nama</th>
		                            <th>Format</th>
		                            <th>Diperbarui</th>
		                            <th></th>
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
	            ajax: "{{ route('admin.'.$page.'.data') }}",
	            order: [[ 2, "desc" ]],
	            initComplete: function () {
	                $('.dataTables_wrapper select').select2({
	                    minimumResultsForSearch: Infinity
	                });
	            },
	            aoColumns: [
					//    {"sTitle": "Role No", "mData": "id"},
	            	{ "mData": "id" },
					// { "mData": "user_id" },
	            	// { "mData": "type_id" },
	            	// { "mData": "operator_id" },
	            	// { "mData": "plan_id" },
	            	// { "mData": "payment_id" },
	            	// { "mData": "transaction_id" },
	            	{ "mData": "product" },
	            	{ "mData": "ref_id" },
	            	{ "mData": "cust_number" },
	            	{ "mData": "tr_code" },
	            	{ "mData": "tr_id" },
	            	{ "mData": "price" },
	            	{ "mData": "status" },
	            	{ "mData": "created_at" },
	            	{ "mData": "updated_at" },
	            	{ "mData": "reff_id" },
	            	{ "mData": "serial_number" },
	            	{ "mData": "balance" },
	            	{ "mData": "r_balance" },
	            	{ "mData": "pin" },
	            	{ "mData": "action" }
	            ]
	        });

	        table.buttons().container().appendTo( '#ks-datatable_wrapper .col-md-6:eq(0)' );
	    });
	})(jQuery);
	</script>
	<!-- END DATATABLE SCRIPTS -->

@endsection
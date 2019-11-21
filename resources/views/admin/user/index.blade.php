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
		                            <th>Nama</th>
		                            <th>Email</th>
									<th>Status</th>
									<th>Mons Wallet</th>
									<th>Life Point</th>
		                            <th width="10%">Diterbitkan</th>
		                            <th width="10%">Aksi</th>
		                        </tr>
		                        </thead>
		                        <tfoot>
		                        <tr>
									<th>ID</th>
									<th>Nama</th>
									<th>Email</th>
									<th>Status</th>
									<th>Mons Wallet</th>
									<th>Life Point</th>
		                            <th>Diterbitkan</th>
		                            <th>Aksi</th>
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
				processing: true,
				serverSide: true,
	            ajax: {
					type: "GET",
					url: "{{ route('admin.'.$page.'.data') }}",
					headers: {
						'_token': $("meta[name=csrf-token]").attr("content")
					}
				},
	            order: [[ 6, "desc" ]],
	            initComplete: function () {
	                $('.dataTables_wrapper select').select2({
	                    minimumResultsForSearch: Infinity
	                });
	            },
	            columns: [
	            	{ "data": "id" },
	            	{ "data": "name" },
	            	{ "data": "email" },
	            	{ "data": "activated" },
	            	{ "data": "mons_wallet" },
	            	{ "data": "life_point" },
	            	{ "data": "created_at" },
	            	{ "data": "action" }
	            ]
	        });

	        table.buttons().container().appendTo( '#ks-datatable_wrapper .col-md-6:eq(0)' );
	    });
	})(jQuery);
	</script>
	<!-- END DATATABLE SCRIPTS -->

@endsection
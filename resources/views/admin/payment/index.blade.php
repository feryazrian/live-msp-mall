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
							<div class="row mb-3">
								<div class="col-sm-4">
									<a href="{{ route('admin.'.$page.'.create') }}">
										<button class="btn btn-success ks-split btn-block mb-2">
											<span class="la la-archive ks-icon"></span>
											<span class="ks-text">Tambah Metode Pembayaran</span>
										</button>
									</a>
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
					    
                            <table id="ks-datatable" class="table responsive table-striped table-bordered" cellspacing="0" width="100%">
		                        <thead>
                                    <tr>
										<th>ID</th>
                                        <th>Title</th>
                                        <th>Slug</th>
                                        <th>Logo</th>
                                        <th width="10%">Type</th>
                                        <th width="10%">Status</th>
                                        <th width="10%">Diperbarui</th>
                                        <th width="10%"></th>
                                    </tr>
		                        </thead>
		                        <tfoot>
                                    <tr>
										<th>ID</th>
										<th>Title</th>
										<th>Slug</th>
										<th>Logo</th>
										<th>Type</th>
										<th>Status</th>
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
	            order: [[ 6, "desc" ]],
	            initComplete: function () {
	                $('.dataTables_wrapper select').select2({
	                    minimumResultsForSearch: Infinity
	                });
	            },
	            aoColumns: [
					{ "mData": "id" },
	            	{ "mData": "title" },
	            	{ "mData": "slug" },
	            	{ "mData": "logo" },
	            	{ "mData": "type" },
	            	{ "mData": "status" },
	            	{ "mData": "updated" },
	            	{ "mData": "action" }
	            ]
	        });

	        table.buttons().container().appendTo( '#ks-datatable_wrapper .col-md-6:eq(0)' );
	    });
	})(jQuery);
	</script>
	<!-- END DATATABLE SCRIPTS -->

@endsection
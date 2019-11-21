@extends('layouts.admin')

@section('content')

    <div class="ks-column ks-page">
        <div class="ks-page-header">
            <section class="ks-title">
				<h3>{{ $pageTitle }}</h3>
				<div>
					<a href="{{ route('admin.'.$page.'.create') }}">
						<button class="btn btn-success">
							<span class="la la-plus ks-icon"></span>
							<span class="ks-text">Tambah {{ $pageTitle }}</span>
						</button>
					</a>
					{{-- <button class="btn btn-success" data-toggle="modal" data-target="#set-tanggal-autodebet">
						<span class="la la-plus ks-icon"></span>
						<span class="ks-text">Set Tanggal {{ $pageTitle }}</span>
					</button> --}}
					<button class="btn btn-success" data-toggle="modal" data-target="#autodebet-all">
						<span class="la la-plus ks-icon"></span>
						<span class="ks-text">{{ $pageTitle }} All</span>
					</button>
				</div>
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
                                    <th>Informasi User</th>
		                            <th>Jumlah</th>
                                    <th>Tgl. Auto Debet</th>
		                            {{-- <th>Keterangan</th> --}}
		                            <th>Status</th>
		                            {{-- <th>Tgl. Dibuat</th> --}}
		                            <th>Tgl. Diperbarui</th>
		                            <th width="10%"></th>
		                        </tr>
		                        </thead>
		                        <tfoot>
									<tr>
                                        <th>ID</th>
                                        <th>Informasi User</th>
                                        <th>Jumlah</th>
                                        <th>Tgl. Auto Debet</th>
                                        {{-- <th>Keterangan</th> --}}
                                        <th>Status</th>
                                        {{-- <th>Tgl. Dibuat</th> --}}
                                        <th>Tgl. Diperbarui</th>
                                        <th width="10%"></th>
									</tr>
		                        </tfoot>
		                    </table>

                        </div>
                    </div>
                </div>
            </div>
		</div>
		
		<div id="set-tanggal-autodebet" class="modal fade" role="dialog">
			<div class="modal-dialog">
			  <!-- Modal content-->
			  <div class="modal-content">
				<div class="modal-header">
				  <h4 class="modal-title">Update Tanggal Auto Debet</h4>

				</div>
				<div class="modal-body">
				  <form method="post" action="{{ route('admin.'.$page.'.update_auto_debet') }}" enctype="multipart/form-data">              
				  {{ csrf_field() }}
				  {{-- {!! Form::open(array('route' => ['usersdata.update_joined_date', $user->id], 'method' => 'POST', 'role' => 'form', 'class' => 'needs-validation')) !!} --}}
				  <div class="row">
					<div class="form-group{{ $errors->has('tgl_auto_debet') ? ' has-danger' : '' }}">
						<label>Tanggal Auto Debet</label>
						<input type="text"
								name="tgl_auto_debet"
								value="{{ old('tgl_auto_debet') }}"
								class="form-control calendar"
								data-validation="required"
								data-validation-error-msg="tanggal auto debet harus di isi."
								data-enable-time="true"
								data-time_24hr="true"
								data-enable-seconds="true"
								placeholder="Tanggal Auto Debet">

						@if ($errors->has('tgl_auto_debet'))
							<small class="form-text text-danger">{{ $errors->first('tgl_auto_debet') }}</small>
						@endif
					</div> 
					
				  </div>
					  <div class="col-md-6 col-sm-12">
						  <button type="submit" class="btn btn-primary">Update</button>
						  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

						{{-- {!! Form::submit('Update', ['class' => 'btn btn-primary']) !!} --}}
					  </div>
					  {{-- {!! Form::close() !!} --}}
					</form>
				</div>
				
			  </div>
			</div>
		</div>


		<div id="autodebet-all" class="modal fade" role="dialog">
			<div class="modal-dialog">
			  <!-- Modal content-->
			  <div class="modal-content">
				<div class="modal-header">
				  <h4 class="modal-title center">Auto Debet All</h4>

				</div>
				<div class="modal-body">
				  <form method="post" action="{{ route('admin.'.$page.'.autodebet_all') }}" enctype="multipart/form-data">              
				  {{ csrf_field() }}
						<label>Are you sure want to autodebet all user in autodebet table ?</label>
					
					  <div class="col-md-6 col-sm-12">
						  <button type="submit" class="btn btn-primary">Yes</button>
						  <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
					  </div>
					</form>
				</div>
				  <div class="modal-footer">
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
	            order: [[ 1, "desc" ]],
	            initComplete: function () {
	                $('.dataTables_wrapper select').select2({
	                    minimumResultsForSearch: Infinity
	                });
	            },
	            aoColumns: [
	            	{ "mData": "id" },
                    { "mData": "users" },
                    { "mData": "jumlah" },
	            	{ "mData": "tgl_auto_debet" },
	            	// { "mData": "keterangan" },
	            	{ "mData": "status" },
	            	// { "mData": "created_at" },
	            	{ "mData": "updated_at" },
	            	{ "mData": "action" }
	            ]
	        });

	        table.buttons().container().appendTo( '#ks-datatable_wrapper .col-md-6:eq(0)' );
	    });
	})(jQuery);
	</script>
	<!-- END DATATABLE SCRIPTS -->

@endsection
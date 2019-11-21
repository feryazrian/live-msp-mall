@extends('layouts.admin')

@section('content')

    <div class="ks-column ks-page">
        <div class="ks-page-header">
            <section class="ks-title">
                <h3>Ubah {{ $pageTitle }}</h3>
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

					                    <form method="post" action="{{ route('admin.'.$page.'.update') }}" enctype="multipart/form-data">
					              
					                        {{ csrf_field() }}

					                        <div class="form-group{{ $errors->has('id') ? ' has-danger' : '' }}">
					                            <label>Nomor Resi</label>
					                            <input type="text"
					                            		name="id" 
					                            		value="{{ $item->id }}"
					                                	class="form-control"
														data-validation="required"
														readonly
				                                    	data-validation-error-msg="Nomor Resi harus di isi."
					                                	placeholder="Nomor Resi">

					                            @if ($errors->has('id'))
					                            	<small class="form-text text-danger">{{ $errors->first('id') }}</small>
					                            @endif
					                        </div>

					                        <div class="form-group{{ $errors->has('courier_id') ? ' has-danger' : '' }}">
					                            <label>Kurir</label>
					                            <input type="text"
					                            		name="courier_id" 
					                            		value="{{ $item->courier->code.' - '.$item->courier->name }}"
														class="form-control"
														readonly
				                                    	data-validation-error-msg="Kurir harus di isi."
					                                	placeholder="Kurir">

											@if ($errors->has('courier_id'))
												<small class="form-text text-danger">{{ $errors->first('courier_id') }}</small>
											@endif
					                        </div>

					                        <div class="form-group{{ $errors->has('shipper_name') ? ' has-danger' : '' }}">
					                            <label>Nama Pengirim</label>
					                            <input type="text"
					                            		name="shipper_name" 
					                            		value="{{ $item->shipper_name }}"
														class="form-control"
														readonly
				                                    	data-validation-error-msg="Nama Pengirim harus di isi."
					                                	placeholder="Nama Pengirim">

											@if ($errors->has('shipper_name'))
												<small class="form-text text-danger">{{ $errors->first('shipper_name') }}</small>
											@endif
					                        </div>

					                        <div class="form-group{{ $errors->has('shipper_address') ? ' has-danger' : '' }}">
					                            <label>Alamat Pengirim</label>
					                            <input type="text"
					                            		name="shipper_address" 
					                            		value="{{ $item->shipper_address }}"
														class="form-control"
														readonly
				                                    	data-validation-error-msg="Alamat Pengirim harus di isi."
					                                	placeholder="Alamat Pengirim">

											@if ($errors->has('shipper_address'))
												<small class="form-text text-danger">{{ $errors->first('shipper_address') }}</small>
											@endif
					                        </div>

					                        <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
												<label>Nama Penerima</label>
												<input type="text"
														name="name" 
														value="{{ $item->name }}"
														class="form-control"
														data-validation="required"
														readonly
														data-validation-error-msg="Nama Penerima harus di isi."
														placeholder="Nama Penerima">

											@if ($errors->has('name'))
												<small class="form-text text-danger">{{ $errors->first('name') }}</small>
											@endif
											</div>

											<div class="form-group{{ $errors->has('address') ? ' has-danger' : '' }}">
												<label>Alamat Penerima</label>
					                            <input type="text"
					                            		name="address" 
					                            		value="{{ $item->address.', '.$item->kecamatan->name.', '.$item->kabupaten->name.', '.$item->provinsi->name.', Indonesia, '.$item->postal_code }}"
														class="form-control"
														readonly
				                                    	data-validation-error-msg="Alamat Penerima harus di isi."
														placeholder="Alamat Penerima">
											
											@if ($errors->has('address'))
												<small id="address" class="form-text text-danger">
													{{ $errors->first('address') }}
												</small>
											@endif
											</div>
					
					
											<div class="form-group{{ $errors->has('transaction') ? ' has-danger' : '' }}">
												<label>Nilai Transaksi</label>
												<input type="text"
														name="transaction"
														class="form-control"
														id="transaction"
														placeholder="Nilai Transaksi"
														data-validation="required"
														readonly
														data-validation-error-msg="Nilai Transaksi harus di isi."
														value="{{ $item->transaction }}">
											
											@if ($errors->has('transaction'))
												<small id="transaction" class="form-text text-danger">
													{{ $errors->first('transaction') }}
												</small>
											@endif
											</div>
											
					                        <div class="form-group">
												<small>Jarak Pengiriman</small>
												<div>{{ $item->distance.' km' }}</div>
											</div>
											
					                        <div class="form-group">
												<small>Ongkos Kirim (Rp)</small>
												<div>{{ 'Rp '.number_format($item->price,0,',','.') }}</div>
											</div>
	
					                        <div class="form-group{{ $errors->has('status') ? ' has-danger' : '' }}">
					                            <label>Status</label>
					                            <div>
					                                <select class="form-control ks-select-placeholder-single"
					                                		name="status_id" 
					                                    	data-validation="required"
					                                    	data-validation-error-msg="Status harus di isi.">
					                                    <option value="0">Tidak Tampil</option>
					                                @foreach ($status as $statusitem)
				                                        <option value="{{ $statusitem->id }}" @if($statusitem->id == $item->status_id) selected="selected" @endif >{{ $statusitem->name }}</option>
				                                    @endforeach
					                                </select>
					                            </div>

					                            @if ($errors->has('footer'))
					                            	<small class="form-text text-danger">{{ $errors->first('footer') }}</small>
					                            @endif
					                        </div>

					                        <div class="row my-0 py-2">
					                        	<div class="col-md-6">
							                        <div class="form-group">
							                            <label>Diterbitkan</label>
							                            <div class="text-dark">
															<span class="mr-2">{{ $item->created_at }}</span>
															<small>{{ $item->created_at->diffForHumans() }}</small>
							                            </div>
							                        </div>
					                        	</div>
					                        	<div class="col-md-6">
							                        <div class="form-group">
							                            <label>Diperbarui</label>
							                            <div class="text-dark">
															<span class="mr-2">{{ $item->updated_at }}</span>
															<small>{{ $item->updated_at->diffForHumans() }}</small>
							                            </div>
							                        </div>
					                        	</div>
					                        </div>

					                        <div class="row my-0">
					                        	<div class="col-md-6">
						                            <label>Author</label>
						                            <div>
										                <a href="#" class="ks-user text-dark">
		                                                    <div class="ks-avatar d-inline-block align-top mr-2">
		                                                        <img src="{{ asset('/uploads/photos/'.$item->user->photo) }}" width="40" height="40" class="ks-avatar rounded-circle">
		                                                    </div>
	                                                        <div class="ks-body d-inline-block">
	                                                            <div class="ks-name">{{ $item->user->name }}</div>
	                                                            <small class="ks-text">{{ '@'.$item->user->username }}</small>
	                                                        </div>
	                                                    </a>
						                            </div>
					                        	</div>
					                        	<div class="col-md-6 align-bottom">
							                        <div class="mt-3 pt-2">
									                    <button class="btn btn-success btn-block ks-split">
										                    <span class="la la-check ks-icon"></span>
										                    <span class="ks-text">Simpan Perubahan</span>
									                    </button>
							                        </div>
					                        	</div>
					                        </div>

					                    </form>

					                    <hr>
				                    	
					                    <form method="post" action="{{ route('admin.'.$page.'.delete') }}">
					                        {{ csrf_field() }}
					                        <input type="hidden" name="id" value="{{ $item->id }}">

						                    <button type="submit" class="btn btn-outline-danger btn-block ks-split">
							                    <span class="la la-trash ks-icon"></span>
							                    <span class="ks-text"><strong>Hapus</strong> {{ $pageTitle }}</span>
						                    </button>
					                    </form>

	                                </div>
	                            </div>
                            </div>
						</div>
						
						<section class="ks-title mt-4 mb-3">
							<div class="d-inline-block">
								<h3>{{ $pageSubTitle }}</h3>
							</div>
							<a href="{{ route('admin.'.$subpage.'.create', ['id' => $item->id]) }}" class="pull-right">
								<button class="btn btn-success ks-split">
									<span class="la la-plus ks-icon"></span>
									<span class="ks-text">Tambah {{ $pageSubTitle }}</span>
								</button>
							</a>
						</section>
						
						<table id="ks-datatable" class="table responsive table-striped table-bordered" cellspacing="0" width="100%">
							<thead>
							<tr>
								<th>Kode</th>
								<th>Deskripsi</th>
								<th>Lokasi</th>
								<th width="20%">Diterbitkan</th>
								<th width="20%">Diperbarui</th>
								<th width="10%"></th>
							</tr>
							</thead>
							<tfoot>
							<tr>
								<th>Kode</th>
								<th>Deskripsi</th>
								<th>Lokasi</th>
								<th>Diterbitkan</th>
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
	            ajax: "{{ route('admin.'.$subpage.'.data', ['id' => $item->id]) }}",
	            order: [[ 3, "asc" ]],
	            initComplete: function () {
	                $('.dataTables_wrapper select').select2({
	                    minimumResultsForSearch: Infinity
	                });
	            },
	            aoColumns: [
	            	{ "mData": "code" },
	            	{ "mData": "description" },
	            	{ "mData": "city" },
	            	{ "mData": "created" },
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
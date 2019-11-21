@extends('layouts.shipping')

@section('title'){{ str_replace('[TITLE]', $pageTitle, $seo_title) }}@endsection

@section('description'){{ str_replace('[TITLE]', $pageTitle, $seo_description) }}@endsection

@section('content')

<section class="page-section bg-grey-light">
    <div class="container">
        <div class="row justify-content-md-center py-4">
            <div class="col col-12 col-sm-12 col-md-10">
                <div class="page-content bg-white p-4">
                    <div class="page-title mb-4">{{ $pageTitle }}</div>
                    
                @if (empty($item))
                    <div>
                        <div class="notfound">Nomor Resi Tidak Ditemukan</div>
                    </div>
                @endif

                @if (!empty($item))
                    <div>
                        <table class="table table-responsive">
                            <tr>
                                <td>
                                    <small class="text-grey">Nomor Resi</small>
                                    <div>{{ $item->id }}</div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <small class="text-grey">Kurir</small>
                                    <div>{{ $item->courier->code.' - '.$item->courier->name }}</div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <small class="text-grey">Nama Pengirim</small>
                                    <div>{{ $item->shipper_name }}</div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <small class="text-grey">Alamat Pengirim</small>
                                    <div>{{ $item->shipper_address }}</div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <small class="text-grey">Nama Penerima</small>
                                    <div>{{ $item->name }}</div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <small class="text-grey">Alamat Penerima</small>
                                    <div>{{ $item->address.', '.$item->kecamatan->name.', '.$item->kabupaten->name.', '.$item->provinsi->name.', Indonesia, '.$item->postal_code }}</div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <small class="text-grey">Nilai Transaksi</small>
                                    <div>{{ 'Rp '.number_format($item->transaction,0,',','.') }}</div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <small class="text-grey">Jarak Pengiriman</small>
                                    <div>{{ $item->distance.' km' }}</div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <small class="text-grey">Ongkos Kirim</small>
                                    <div class="text-price">{{ 'Rp '.number_format($item->price,0,',','.') }}</div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <small class="text-grey">Status Pengiriman</small>
                                    <div>{{ $item->status->name }}</div>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="page-title mb-4">Manifest</div>
                        
                    <div>
                        <table class="table table-responsive w-100">
                            <thead>
                                <tr>
                                    <td>Kode</td>
                                    <td>Deskripsi</td>
                                    <td>Lokasi</td>
                                    <td>Waktu</td>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($item->manifest as $manifest)
                                <tr>
                                    <td>{{ $manifest->id }}</td>
                                    <td>{{ $manifest->description }}</td>
                                    <td>{{ $manifest->kabupaten->name }}</td>
                                    <td>{{ $manifest->created_at }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

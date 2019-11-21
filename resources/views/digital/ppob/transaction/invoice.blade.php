<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{$pageTitle}}</title>
    <!-- Loading Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <!-- Loading Flat UI Pro -->
    <link href="{{ asset('css/flat-ui-pro.css') }}" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
</head>
<body>
    
    <div class="container">
        <div class="row m-5 p-5">
            <div class="col-md-8">
                <div class="row">
                    <img src="{{ asset('uploads/options/logo_color.png') }}" height="55px">
                    <div style="width: 1px;height: 55px;background-color: #fcbf00; margin:0 10px;"></div>
                    <div class="navbar-brand font-weight-bolder text-primary">Your Shopping Partner</div>
                </div>
            </div>
            <div class="col-md-4">
                <p class="font-weight-bolder float-right"><a href="#" onclick="window.print()" class="text-dark">Cetak <span class="fa fa-print text-primary"></span></a></p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <h6 class="text-primary">Invoice {{ $operator->type_name }}</h6>
            </div>
        </div>
        <div class="row ml-5">
            <div class="col-md-4 col-offset-md-2"><b class="m-0">Nomor Invoice</b></div>
            <div class="col-md-6"><b class="m-0">{{ $data->reff_id }}</b></div>
        </div>
        <div class="row ml-5">
            <div class="col-md-4 col-offset-md-2"><b class="m-0">Tanggal Pembelian</b></div>
            <div class="col-md-6"><b class="m-0">{{ $orderDate }}</b></div>
        </div>

        <div class="table-responsive my-5">
            <table class="table table-striped">
                <tbody>
                    <tr class="table-secondary">
                        <th>Produk</th>
                    <th>{{ $operator->type_name }} {{ $operator->opr_name }}</th>
                    </tr>
                    <tr>
                        <th>Nomor</th>
                        <th>{{ $data->cust_number }}</th>
                    </tr>
                    <tr class="table-secondary">
                        <th>Nominal</th>
                        <th>{{ $product->pulsa_nominal }}</th>
                    </tr>
                    <tr>
                        <th>Harga</th>
                        <th>Rp {{ number_format($data->price,0,',','.') }}</th>
                    </tr>
                    @if ($promo)
                        <tr class="table-secondary">
                            <th>Diskon</th>
                            <th>-Rp {{ number_format($promo->price,0,',','.') }}</th>
                        </tr>
                    @endif
                    <tr>
                        <th style="border-bottom:none;"></th>
                        <th style="border-bottom:none;">
                            <div class="row">
                                <div class="col-md-6">Total Dibayar</div>
                                <div class="col-md-6"><b class="float-right text-primary">Rp {{ number_format($data->total,0,',','.') }}</b></div>
                            </div>
                        </th>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    
    {{-- JS Bootstrap --}}
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

    <script src="{{ asset('scripts/flat-ui-pro.min.js') }}"></script>
</body>
</html>
@if ($access == 1)
<!-- seller -->
<div class="caption">
    <div><b>Harap segera melakukan konfirmasi pengiriman produk!!</b></div>
    <div>Transaksi akan dibatalkan dalam <b>maksimal 2x24 jam</b> yaitu sampai <b>{{ $transactionProduct->updated_at->addDay(2) }}</b></div>
</div>

<div class="button">
    <button type="button" class="btn btn-rounded btn-primary mr-2" data-toggle="modal" data-target="#approveModal">Konfirmasi Pengiriman</button>
    <button type="button" class="btn btn-rounded btn-danger" data-toggle="modal" data-target="#cancelModal">Batalkan Pesanan</button>
</div>

<div class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Konfirmasi Pengiriman</h4>
                <button type="button" class="close fui-cross" data-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">

            <form method="post" role="form" action="{{ route('transaction.approve') }}">

                {{ csrf_field() }}

                <input type="hidden" name="transaction" value="{{ $transactionProduct->id }}" />

                <div class="form-group{{ $errors->has('service') ? ' has-error' : '' }}">
                    <select name="service" required class="form-control select select-smart text-left select-secondary">
                        <option value="" selected="selected">Pilih Metode Pengiriman</option>
                        <option value="MSE">MSP Express</option>
                        <option value="POS">POS Indonesia</option>
                        <option value="JNE">JNE</option>
                        <option value="TIKI">TIKI</option>
                        <option value="PANDU">Pandu</option>
                        <option value="WAHANA">Wahana</option>
                        <option value="JNT">J&T Express</option>
                        <option value="SICEPAT">SiCepat</option>
                    </select>
            
                @if ($errors->has('service'))
                    <small id="service" class="form-text text-danger">
                        {{ $errors->first('service') }}
                    </small>
                @endif
                </div>

                <div class="form-group{{ $errors->has('code') ? ' has-error' : '' }}">
                    <input type="text" required class="form-control" name="code" placeholder="Nomor Resi" />
            
                @if ($errors->has('code'))
                    <small id="code" class="form-text text-danger">
                        {{ $errors->first('code') }}
                    </small>
                @endif
                </div>

                <button type="submit" class="btn btn-rounded btn-primary btn-block">Konfirmasi Pengiriman</button>
            </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Batalkan Pesanan</h4>
                <button type="button" class="close fui-cross" data-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">

            <div class="mb-3">Apakah anda yakin ingin membatalkan pesanan ini ?</div>

            <form method="post" role="form" action="{{ route('transaction.cancel') }}">
                {{ csrf_field() }}

                <input type="hidden" name="transaction" value="{{ $transactionProduct->id }}" />

                <div class="form-group{{ $errors->has('cancel') ? ' has-error' : '' }}">
                    <textarea placeholder="Ketikkan Alasan Pembatalan Pesanan pada formulir berikut..." class="form-control" name="cancel" rows="3" required></textarea>
                
                @if ($errors->has('cancel'))
                    <small id="cancel" class="form-text text-danger">
                        {{ $errors->first('cancel') }}
                    </small>
                @endif
                </div>

                <button type="submit" class="btn btn-rounded btn-danger btn-block">Batalkan Pesanan</button>
            </form>
            </div>
        </div>
    </div>
</div>
@endif

@if ($access == 2)
<!-- buyer -->
<div class="caption">
    <div><b>Transaksi sedang dalam proses!!</b></div>
    <div>Sedang menunggu penjual melakukan konfirmasi stok dan pengiriman produk <b>maksimal 2x24 jam</b> yaitu sampai <b>{{ $transactionProduct->updated_at->addDay(2) }}</b></div>
</div>
@endif

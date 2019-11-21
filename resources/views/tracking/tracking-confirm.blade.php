@if ($access == 1)
<!-- seller -->
<div class="caption">
    <div><b>Menunggu Konfirmasi Pembeli!!</b></div>
    <div>Menunggu konfirmasi penerimaan produk dan ulasan dari pembeli.</div>
</div>
@endif

@if ($access == 2)
<!-- buyer -->
<button type="button" class="btn btn-rounded btn-primary" data-toggle="modal" data-target="#confirmModal">Konfirmasi Produk Diterima</button>

<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Konfirmasi Produk Diterima</h4>
                <button type="button" class="close fui-cross" data-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">

            <form method="post" role="form" action="{{ route('transaction.confirm') }}">

                {{ csrf_field() }}

                <input type="hidden" name="transaction" value="{{ $transactionProduct->id }}" />

                <div class="form-group{{ $errors->has('rating') ? ' has-error' : '' }}">
                    <select name="rating" required class="form-control select select-smart text-left select-secondary">
                        <option value="" selected="selected">Tentukan Rating untuk Penjual</option>
                        <option value="1">1 Bintang</option>
                        <option value="2">2 Bintang</option>
                        <option value="3">3 Bintang</option>
                        <option value="4">4 Bintang</option>
                        <option value="5">5 Bintang</option>
                    </select>
        
                @if ($errors->has('rating'))
                    <small id="rating" class="form-text text-danger">
                        {{ $errors->first('rating') }}
                    </small>
                @endif
                </div>

                <div class="form-group{{ $errors->has('review') ? ' has-error' : '' }}">
                    <textarea required placeholder="Ketikkan Ulasan Anda mengenai produk dan layanan dari penjual..." class="form-control" name="review" rows="3"></textarea>
        
                @if ($errors->has('review'))
                    <small id="review" class="form-text text-danger">
                        {{ $errors->first('review') }}
                    </small>
                @endif
                </div>

                <button type="submit" class="btn btn-rounded btn-primary btn-block">Konfirmasi Produk Diterima</button>
            </form>
            </div>
        </div>
    </div>
</div>
@endif

@if ($access == 1)
<!-- seller -->
@if ($status == '4')
<button type="button" class="btn btn-rounded btn-primary" data-toggle="modal" data-target="#completeModal">Konfirmasi Transaksi Selesai</button>

<div class="modal fade" id="completeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Konfirmasi Transaksi Selesai</h4>
                <button type="button" class="close fui-cross" data-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">

            <form method="post" role="form" action="{{ route('transaction.complete') }}">

                {{ csrf_field() }}

                <input type="hidden" name="transaction" value="{{ $transactionProduct->id }}" />

                <div class="form-group{{ $errors->has('rating') ? ' has-error' : '' }}">
                    <select name="rating" required class="form-control select text-left select-smart select-secondary">
                        <option value="" selected="selected">Tentukan Rating untuk Pembeli</option>
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
                    <textarea required placeholder="Ketikkan Ulasan Anda mengenai pembeli..." class="form-control" name="review" rows="3"></textarea>
    
                @if ($errors->has('review'))
                    <small id="review" class="form-text text-danger">
                        {{ $errors->first('review') }}
                    </small>
                @endif
                </div>

                <button type="submit" class="btn btn-rounded btn-primary btn-block">Konfirmasi Transaksi Selesai</button>
            </form>
            </div>
        </div>
    </div>
</div>
@else
<div class="caption">
    <div><b>Transaksi Selesai</b></div>
    <div>Terimakasih telah berjualan di {{ config('app.name') }}</div>
</div>
@endif
@endif

@if ($access == 2)
<div class="caption">
    <div><b>Transaksi Selesai</b></div>
    <div>Terimakasih telah berbelanja di {{ config('app.name') }}</div>
</div>
@endif

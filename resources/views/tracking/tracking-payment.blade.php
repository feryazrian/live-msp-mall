@if ($access == 1)
<!-- seller -->
<div class="caption">
    <div><b>Pembayaran Sedang Dalam Proses!!</b></div>
    <div>Transaksi sedang menunggu penyelesaian pembayaran dari pembeli <b>maksimal 1x24 jam</b></div>
</div>
@endif

@if ($access == 2)
<!-- buyer -->
<div class="caption">
    <div><b>Pembayaran Sedang Dalam Proses!!</b></div>
    <div>Harap segera menyelesaikan pembayaran apabila anda belum melakukan pembayaran <b>maksimal 1x24 jam</b></div>
</div>

@include($transactionView)
@endif

@if ($status == '6')
<div class="caption">
    <div><b>Transaksi Dibatalkan Penjual</b></div>
    @if (!empty($cancel))
    <div class="notes bg-warning">{{ $cancel }}</div>
    @endif
</div>
@endif

@if ($status == '7')
<div class="caption">
    <div><b>Transaksi Dibatalkan Sistem</b></div>
    @if (!empty($cancel))
    <div class="notes bg-warning">{{ $cancel }}</div>
    @endif
</div>
@endif
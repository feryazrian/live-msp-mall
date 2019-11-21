<!-- Modal Promo Code -->
<div class="modal fade" id="promoCodeModal" tabindex="-1" role="dialog" aria-labelledby="promoCodeModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title">Gunakan Kode Promo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label class="col-form-label">Kode Promo:</label>
                <div class="input-group">
                    <input type="text" id="promoInput" name="promoCode" class="form-control" placeholder="Masukkan Kode Promo" required>
                    <div id="loadingPromo" class="m-0"></div>
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-primary bg-dark" id="submitPromoCode">Gunakan</button>
                    </span>
                </div>
                <br>
                <div class="msg-error-checkout">
                    <span class="text-danger"></span>
                </div>
            </div>
        </div>
        <div class="text-center loading-container" ></div>
    </div>
    </div>
</div>
<!-- /Modal Promo Code -->
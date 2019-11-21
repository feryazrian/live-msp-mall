<div class="tab-pane fade" id="data">
    <form method="get" action="/digital/data/checkout">
        {{ csrf_field() }}
        <div class="row">
            <div class="col-sm-12 col-md-4">
                <div class="form-group">
                    <label for="inputData">No. Handphone</label>
                    <input type="text" class="form-control numeric" name="hp" value="" id="inputData" placeholder="Contoh: 08123453789" minlength="9" maxlength="14" required>
                    <div id="prefixData" class="m-0"></div>
                </div>
            </div>
            <div class="col-sm-12 col-md-4">
                <div class="form-group">
                    <label for="nominalData">Nominal</label>
                    <select class="form-control" id="nominalData" name="pulsa_code" aria-placeholder="Nominal" placeholder="Pilih Nominal">
                        <option value="" disabled selected>Pilih Nominal</option>
                    </select>
                </div>
            </div>
            <div class="col-sm-12 col-md-2">
                <div class="form-group">
                    <label for="hargaData">Harga</label>
                    <p id="hargaData">Rp. 0 </p>
                </div>
            </div>
            <div class="col-sm-12 col-md-2 align-middle">
                <div class="form-group">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary px-5">Beli</button>
                </div>
            </div>
        </div>
        <div class="msg-error-data">
            <span class="text-danger"></span>
        </div>
    </form>
</div>
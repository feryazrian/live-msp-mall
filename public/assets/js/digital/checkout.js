// Initialize Global Variabel for checkout
var monsWallet = 0;
var lifePoint = 0;
var price = 0;
var serviceFee = 0;
var promoAmount = 0;
var totalPayment = 0;
var opts = { separator: '.', precision: 0 };
var promoCodeStore = { pulsa: '', data:'', internet:'', pln:'' };
var localStorage = window.localStorage;
var STORAGE = {
    PROMOS: 'PROMOS'
}
var checkoutType = $('#checkoutContent').data('type');
// var ppobID = $('input[name=type_ppob_id]').val();

// First Load Checout page
$(document).ready(function () {
    monsWallet = $('#monsWallet').text() || 0;
    lifePoint = $('#lifePoint').text() || 0;
    price = $('#price').text();
    serviceFee = $('#serviceFee').text() || 0;
    totalPayment = parseInt(price) + parseInt(serviceFee);
    $('#monsWallet').text(currency(monsWallet, opts).format())
    $('#lifePoint').text(currency(lifePoint,opts).format())
    $('#price').text(currency(price, opts).format());
    $('#serviceFee').text(currency(serviceFee, opts).format());
    calculatePayment()
    getSelectedPayment();
    checkingStore();
})

function calculatePayment() {
    $('#totalPayment').text(currency(totalPayment, opts).format());
}

// disable some payment method
$('.btn-group .btn.disabled').click(function(event) {
    event.stopPropagation();
});

// Active payment method when click
$("#paymentMethod").click(function(){
    $(this).button('active');
});

// trigger content when radio button change
$('input[type=radio][name="paymentMethod"]').change(function() {
    getSelectedPayment()
});

// Set selected content by selected value
function getSelectedPayment(){
    $('#pay-wallet').hide();
    $('#pay-credit').hide();
    var val = $("input[name='paymentMethod']:checked").val();
    switch (val) {
        case '2':
            $('#pay-wallet').show()
            checkMonsWallet()
            break;
        case '3':
            $('#pay-credit').show()
            closePromo()
            checkLifePoint()
            break;
    }
}

// Show promo code modal
$('#promoCodeModal').on('shown.bs.modal', function () {
    ajaxErrorCallback('checkout', '')
    $('#promoInput').val('').trigger('focus')
    // if (ppobID == null || ppobID =='') {
    //     getAllPpobType();
    // }
})

// Trigger submit promo code input when enter
// $('#promoInput').keypress(function (e) {
//     if (e.which == 13 && $(this)[0].value != '') {
//         $('#submitPromoCode').trigger('click');
//     }
// });

// Submit promo code
$('#submitPromoCode').on('click', function (e) {
    var paymentMethod = $("input[name='paymentMethod']:checked").val();
    if (paymentMethod == 2) {
        var typePpobID = $('input[name=type_ppob_id]').val();
        var promoCode = $('#promoInput').val();
        var submitBtn = $('#submitPromoCode');
        if (promoCode != '') {
            submitBtn.text("Please Wait...").attr('disabled','disabled');
            var params = param({ 'code' : promoCode, 'type_ppob_id': typePpobID, 'total_transaction': totalPayment })
            var uri = '/promo/checkpromo?' + params;
            var callback = {
                before:beforeCallbackCheckout,
                complete:completeCallbackCheckout
            }
    
            ajaxGet(uri, callback)
            .then(res =>{
                var titlePromo = res.items.name;
                var promoCodeDetail = '';
                switch (res.items.discount_type_id) {
                    case 1:
                        promoAmount = res.items.promo_price;
                        promoCodeDetail = '<div class="row"><div class="col-md-6"><p class="m-0 pull-left">Kode Promo Aktif : ' + titlePromo + ' <b class="text-brand">' + promoCode.toUpperCase() + '</b> <a href="#" onClick="closePromo()" class="text-danger"><span class="fa fa-times-circle"></span></a></p></div><div class="col-md-6"><p class="pull-right m-0 text-success" id="promoAmount" style="font-size:15px;">Anda berpotensi mendapatkan Cashback Rp '+ currency(promoAmount, opts).format() +'</p></div></div>'
                        break;
                    case 2:
                        promoAmount = res.items.promo_price;
                        totalPayment = parseInt(totalPayment) - parseInt(promoAmount);
                        totalPayment = totalPayment < 0 ? 0 : totalPayment;
                        promoCodeDetail = '<div class="row"><div class="col-md-8"><p class="m-0 pull-left">Kode Promo Aktif : ' + titlePromo + ' <b class="text-brand">' + promoCode.toUpperCase() + '</b> <a href="#" onClick="closePromo()" class="text-danger"><span class="fa fa-times-circle"></span></a></p></div><div class="col-md-4"><p class="pull-right m-0" id="promoAmount">-Rp '+ currency(promoAmount, opts).format() +'</p></div></div>'
                        break;
                    default:
                        break;
                }
                // $('input:hidden[name=type_ppob_id]').val(ppobID)
                $('input:hidden[name=promo_code]').val(promoCode)
                $('#promoCodeValue').prepend(promoCodeDetail)
                $('#promoCodeBtn').hide();
                $('#promoCodeModal').modal('hide')
                calculatePayment()
                checkMonsWallet()
                promoCodeStore[checkoutType] = promoCode;
                storePromoItems()
                submitBtn.text("Gunakan").removeAttr('disabled');
            })
            .catch(e => {
                ajaxErrorCallback('checkout', e.responseJSON.status_message)
                submitBtn.text("Gunakan").removeAttr('disabled');
            })
        }
    } else {
        ajaxErrorCallback('checkout', 'Oops! Promo tidak berlaku untuk metode pembayaran yang anda pilih.')
    }
})

// Checking mons wallet
function checkMonsWallet() {
    var message = ''
    if (monsWallet < totalPayment) {
        message = 'Oops!!. Saldo Mons Wallet anda tidak mencukupi untuk melakukan pembelian';
        ajaxErrorCallback('mons-wallet', message)
        $('button[type=submit]').attr('disabled', true)
    } else{
        ajaxErrorCallback('mons-wallet', message)
        $('button[type=submit]').attr('disabled', false)
    }
}

function checkLifePoint(){
    var message = ''
    if(lifePoint < totalPayment){
        message = 'Oops!!. Saldo life point anda tidak mencukupi untuk melakukan pembelian';
        ajaxErrorCallback('life-point', message)
        $('button[type=submit]').attr('disabled', true)
    }else{
        ajaxErrorCallback('life-point', message)
        $('button[type=submit]').attr('disabled', false)
    }
}

// Close promo code modal
function closePromo() {
    promoCode = null;
    $('#promoCodeBtn').show();
    $('#promoCodeValue div').remove();
    $('#promoInput').val(promoCode);
    $('input:hidden[name=promo_code]').val(promoCode);
    totalPayment = parseInt(price) + parseInt(serviceFee);
    calculatePayment();
    checkMonsWallet();
    promoCodeStore[checkoutType] = '';
    storePromoItems()
}

function checkingStore(){
    var store = JSON.parse(localStorage.getItem(STORAGE.PROMOS))
    if (!store) {
        storePromoItems()
    }
    if (store[checkoutType].length>0) {
        $('#promoInput').val(store[checkoutType])
        $('#submitPromoCode').trigger('click');
    }
}

function storePromoItems(){
    localStorage.setItem(STORAGE.PROMOS, JSON.stringify(promoCodeStore))
}

function beforeCallbackCheckout(){
    var loading = $('#loadingPromo');
    // Show Loading image when fetching
    $('.loading-container').show();
    if (loading.find('img').length == 0) {  // Show Loading Image before fetching
        loading.prepend(LOADING_IMAGE)
    }
}

function completeCallbackCheckout() {
    var loading = $('#loadingPromo');
    $('.loading-container').hide(); // Hide Loading container
    loading.find('img').remove() // Remove Loading Image after fetching complete
}

// function getAllPpobType(){
//     var uri = '/digital/ppobtype';
//     var callback = {
//         before:beforeCallbackCheckout,
//         complete:completeCallbackCheckout
//     }
//     ajaxGet(uri, callback)
//     .then(res =>{
//         var findItem = res.items.find(el => el.slug === checkoutType)
//         ppobID = findItem.id;
//     })
//     .catch(e => {
//         console.log(e)
//     })
// }
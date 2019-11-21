$(document).ready(function() {
    disableSubmitBtn('#pulsa button', true)
    fetchPulsaList()
});

$(window).bind("pageshow", function() {
    $("#inputPulsa").val(''); // Reset input value
    $('#nominalPulsa').find('option').remove().end().append('<option value="" disabled selected>Pilih Nominal</option>'); // Reset Nominal value
    $('#prefixPulsa').find('img').remove();
});

var detailPulsa = {};

// Pulsa Input process
function fetchPulsaList() {
    $("#inputPulsa").on("input propertyChange", function () {
        var val = $(this).val();
        var prefixLogo = $('#prefixPulsa');
        var nominalPulsa = $('#nominalPulsa');
        var hargaPulsa = $('#hargaPulsa');
        var message = '';
        if (val.length >= 4) {
            if (PREPAID.PULSA.length == 0) {
                detailPulsa = getPhonePrefix(val); // get phone prefix
                if (detailPulsa) {
                    // initialize to get pulsa list
                    var path = PATHNAME == '/' ? "digital/pricelist?type=pulsa&provider=" : "pricelist?type=pulsa&provider=";
                    var uri = path + detailPulsa.oprPulsa;
                    var callback = {
                        before:beforeCallbackPulsa,
                        complete:completeCallbackPulsa
                    }

                    // fetch to get pulsa list
                    ajaxGet(uri, callback)
                    .then(res => {
                        if (PREPAID.PULSA.length == 0) {
                            var userRegister = $('.userRegister').val();
                            var filPulsa = 100000;
                            if (userRegister) {
                                if (userRegister > '2019-07-31 00:00:00') {
                                    filPulsa = 50000;
                                }
                            }

                            PREPAID.PULSA = res.data.sort(compare) // sorting array pulsa list
                            PREPAID.PULSA.forEach(el => {
                                if (parseInt(el.pulsa_nominal) <= filPulsa) {
                                    var val = el.pulsa_code;
                                    var text = el.pulsa_type.charAt(0).toUpperCase() + el.pulsa_type.slice(1) + ' ' + el.pulsa_op + ' ' + el.pulsa_nominal;
                                    nominalPulsa.append("<option value='" + val + "'>" + text + "</option>");
                                }
                            });
                        }
                    })
                    .catch(e => {
                        var message = 'Oops!!. Pulsa ' + detailPulsa.name + ' tidak tersedia';
                        ajaxErrorCallback('pulsa', message)
                        disableSubmitBtn('#pulsa button', true)
                    })
                }
            }
            if (val.length < 10) {
                message = 'Nomor yang Anda masukkan kurang dari 10 karakter';
                ajaxErrorCallback('pulsa', message)
                disableSubmitBtn('#pulsa button', true)
            } else{
                ajaxErrorCallback('pulsa', '')
                if ($('#nominalPulsa').val()) {
                    disableSubmitBtn('#pulsa button', false)
                }
            }
            if (!detailPulsa) {
                message = 'Oops!!. No operator yang anda input tidak tersedia';
                ajaxErrorCallback('pulsa', message)
                disableSubmitBtn('#pulsa button', true)
            }
        } else {
            prefixLogo.find('img').remove()
            PREPAID.PULSA = [];
            hargaPulsa.text('Rp. 0')
            nominalPulsa.find('option').remove().end().append('<option value="" disabled selected>Pilih Nominal</option>');
            ajaxErrorCallback('pulsa', '')
            disableSubmitBtn('#pulsa button', true)
        }
    });
};

$(function pulsaSelected() {
    $('#nominalPulsa').on('input change', function() {
        var selectedValue = $(this).val()
        var val = PREPAID.PULSA.filter(x => x.pulsa_code == selectedValue)
        $('#hargaPulsa').text('Rp. ' + currency(val[0].pulsa_price, { separator: '.', precision: 0 }).format())
        if ($('#nominalPulsa').val()) {
            disableSubmitBtn('#pulsa button', false)
        }
    });
})

function beforeCallbackPulsa(){
    var prefixLogo = $('#prefixPulsa');
    // Show Loading image when fetching
    $('.loading-container').show();
    if (prefixLogo.find('img').length == 0) {
        prefixLogo.prepend(LOADING_IMAGE)
    }
}

function completeCallbackPulsa() {
    var prefixLogo = $('#prefixPulsa');
    $('.loading-container').hide(); // Hide Loading container
    prefixLogo.find('img').remove() // Remove Loading Image after fetching success
    if (prefixLogo.find('img').length == 0) {
        // show phone prefix image
        prefixLogo.prepend(detailPulsa.img)
        prefixLogo.css("color", detailPulsa.color)
    }
}
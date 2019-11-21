$(document).ready(function() {
    disableSubmitBtn('#data button', true)
    fetchDataList()
});

$(window).bind("pageshow", function() {
    $("#inputData").val(''); // Reset input value
    $('#nominalData').find('option').remove().end().append('<option value="" disabled selected>Pilih Nominal</option>'); // Reset Nominal value
    $('#prefixData').find('img').remove();
});

var detailData = {};

// Data Input process
function fetchDataList() {
    $("#inputData").on("input propertychange", function () {
        var val = $(this).val();
        var prefixLogo = $('#prefixData');
        var nominalData = $('#nominalData');
        var hargaData = $('#hargaData');
        var message = '';
        if (val.length >= 4) {
            if (PREPAID.DATA.length == 0) {
                detailData = getPhonePrefix(val); // get phone prefix 
                if (detailData) {
                    // initialize to get data internet list
                    var path = PATHNAME == '/' ? "digital/pricelist?type=data&provider=" : "pricelist?type=data&provider=";
                    var uri = path + detailData.oprData;
                    var callback = {
                        before:beforeCallbackData,
                        complete:completeCallbackData
                    }

                    // fetch to get data internet list
                    ajaxGet(uri, callback)
                    .then(res => {
                        if (PREPAID.DATA.length == 0) {
                            var userRegister = $('.userRegister').val();
                            var filPulsa = 100000;
                            if (userRegister) {
                                if (userRegister > '2019-07-31 00:00:00') {
                                    filPulsa = 50000;
                                }
                            }

                            PREPAID.DATA = res.data.sort(compare) // sorting arrat data list
                            PREPAID.DATA.forEach(el => {
                                if (parseInt(el.pulsa_price) <= filPulsa) {
                                    var val = el.pulsa_code;
                                    var text = el.pulsa_type.charAt(0).toUpperCase() + el.pulsa_type.slice(1) + ' ' + el.pulsa_op + ' ' + el.pulsa_nominal;
                                    nominalData.append("<option value='" + val + "'>" + text + "</option>");
                                }
                            });
                        }
                    })
                    .catch(e => {
                        console.log(e)
                        var message = 'Oops!!. Data ' + detailData.name + ' tidak tersedia';
                        ajaxErrorCallback('data', message)
                        disableSubmitBtn('#data button', true)
                    })
                }
            }
            if (val.length < 10) {
                message = 'Nomor yang Anda masukkan kurang dari 10 karakter';
                ajaxErrorCallback('data', message)
                disableSubmitBtn('#data button', true)
            } else{
                ajaxErrorCallback('data', '')
                if ($('#nominalData').val()) {
                    disableSubmitBtn('#data button', false)
                }
            }
            if (!detailData) {
                message = 'Oops!!. No operator yang anda input tidak tersedia';
                ajaxErrorCallback('data', message)
                disableSubmitBtn('#data button', true)
            }
        } else {
            prefixLogo.find('img').remove()
            PREPAID.DATA = [];
            hargaData.text('Rp. 0')
            nominalData.find('option').remove().end().append('<option value="" disabled selected>Pilih Nominal</option>');
            ajaxErrorCallback('data', '')
        }
    });
};

$(function dataSelected() {
    $('#nominalData').on('change', function() {
        var selectedValue = $(this).val()
        var val = PREPAID.DATA.filter(x => x.pulsa_code == selectedValue);
        $('#hargaData').text('Rp. ' + currency(val[0].pulsa_price, { separator: '.', precision: 0 }).format())
        if ($('#nominalData').val()) {
            disableSubmitBtn('#data button', false)
        }
    });
})

function beforeCallbackData(){
    var prefixLogo = $('#prefixData');
    // Show Loading image when fetching
    $('.loading-container').show();
    if (prefixLogo.find('img').length == 0) {
        prefixLogo.prepend(LOADING_IMAGE)
    }
}

function completeCallbackData() {
    var prefixLogo = $('#prefixData');
    $('.loading-container').hide(); // Hide Loading container
    prefixLogo.find('img').remove() // Remove Loading Image after fetching finish
    if (prefixLogo.find('img').length == 0) {
        // show phone prefix image
        prefixLogo.prepend(detailData.img)
        prefixLogo.css("color", detailData.color)
    }
}
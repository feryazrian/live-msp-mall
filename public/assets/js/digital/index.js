// Global Hostname variabel
var HOSTNAME = window.location.hostname;
var PATHNAME = window.location.pathname;

// Global prepaid list variabel
var PREPAID = {
    PULSA: [],
    DATA: []
}

// Global phone prefix
var PHONE_PREFIX = {
    telkomsel : ['0811','0812','0813','0821','0822','0823','0852','0853','0851'],
    indosat : ['0814','0815','0816','0855','0856','0857','0858'],
    xl : ['0817','0818','0819','0859','0877','0878','0879'],
    axis : ['0831','0832','0833','0837','0838'],
    three : ['0896','0897','0898','0899', '0895'],
    smartfren : ['0881','0882','0883','0884','0885','0886','0887','0888','0889'],
}

var LOADING_IMAGE = '<img src="/assets/digital/loading-ripple.svg" alt="" class="prefix-img">';

$(document).ready(function() {
    // Hide floating button See All Digital Payment

    if (PATHNAME == "/") {
        $('#headPPOB').append(
            '<div class="d-table-cell text-right"><a href="/digital/pulsa">LIHAT SEMUANYA ></a></div>'
        );
        $("#tabPulsa").addClass('active')
    } else if(PATHNAME === '/digital'){
        window.location.replace("/digital/pulsa");
    } else {
        triggerDigitalTabs(PATHNAME)
    }
});

$(window).bind("pageshow", function() {
    $('.loading-container').hide()
})

// Trigger Digital Tabs
function triggerDigitalTabs(pathName) {
    // Set Routing name and tab active
    var path = window.location.pathname;
    path = path.replace(/\/$/, "");
    path = decodeURIComponent(path);
    var pathIndex = path.lastIndexOf('/')+1;

    var selector = ".nav a";
    // var selector = "nav a";

    $(selector).each(function () {
        var href = $(this).attr('href');
        var tabName = $(this).data('tab');
        var lastPath = path.substr(pathIndex, href.length);
        // Set Active tab when path = href
        if (lastPath === href.replace("#", "")) {
            $(this).closest('li a').trigger('click').addClass('active');
        }
        // Set Active tab when path = tabname
        lastPath = path.substr(pathIndex, tabName.length);
        if (lastPath === tabName) {
            $(this).closest('li a').trigger('click').addClass('active');
        }
        // trigger click to change routing name
        if (pathName != '/') {
            $(this).click(function () {
                // window.location.assign("/digital/"+tabName)
                window.history.replaceState(null, tabName, tabName)
                $(document).prop('title', capitalize(tabName) + " | Topup & Tagihan MSP Mall");
                // document.title = capitalize(tabName) + " | Topup & Tagihan MSP Mall"
            })
        }
    });
}

function capitalize(str){
    if (typeof str !== 'string') return ''
    return str.charAt(0).toUpperCase() + str.slice(1)
}

// Compare for sorting array list from ASC to DESC
function compare( a, b ) {
    if ( a.pulsa_price < b.pulsa_price ){
        return -1;
    }
    if ( a.pulsa_price > b.pulsa_price ){
        return 1;
    }
    return 0;
}

// Getting phone prefix and image
function getPhonePrefix(prefix)
{
    if (prefix.length > 4) {
        prefix = prefix.substr(0,4);
    }

    try {
        if (PHONE_PREFIX.telkomsel.indexOf(prefix) != -1) {
            return {name : 'TELKOMSEL', color : 'red', oprData: 'telkomsel_paket_internet', oprPulsa : 'telkomsel', img: '<img class="prefix-img" src="/assets/digital/telkomsel.png" />' }
        } else if (PHONE_PREFIX.indosat.indexOf(prefix) != -1) {
            return {name : 'INDOSAT', color : 'orange', oprData: 'indosat_paket_internet', oprPulsa : 'indosat', img: '<img class="prefix-img" src="/assets/digital/indosat.png" />' }
        } else if (PHONE_PREFIX.axis.indexOf(prefix) != -1) {
            return {name : 'AXIS', color : 'purple', oprData: 'axis_paket_internet', oprPulsa : 'axis', img: '<img class="prefix-img" src="/assets/digital/axis.png" />' }
        } else if (PHONE_PREFIX.smartfren.indexOf(prefix) != -1) {
            return {name : 'SMARTFREN', color : '#db203f', oprData: 'smartfren_paket_internet', oprPulsa : 'smart', img: '<img class="prefix-img" src="/assets/digital/smartfren.png" style="background-color:#db203f ;border-radius:3px;" />'}
        } else if (PHONE_PREFIX.three.indexOf(prefix) != -1) {
            return {name : 'THREE', color : 'grey', oprData: 'tri_paket_internet', oprPulsa : 'three', img: '<img class="prefix-img" src="/assets/digital/three.png" />' }
        } else if (PHONE_PREFIX.xl.indexOf(prefix) != -1) {
            return {name : 'XL', color : 'blue', oprData: 'xl_paket_internet', oprPulsa : 'xl', img: '<img class="prefix-img" src="/assets/digital/xl.png" />' }
        } else {
            return false
        }
    } catch (e) {
        return e;
    }
}

// Initialize 
function ajaxFetch(uri='', body={}, callback = {before,complete}, headers={}, method='GET'){
    return new Promise((resolve, reject) => {
        $.ajax({
            type: method.toUpperCase(),
            dataType: "JSON",
            url:uri,
            headers: headers,
            data: JSON.stringify(body),
            beforeSend: callback.before,
            complete: callback.complete,
            success: res => resolve(res),
            error: e => reject(e)
        });
    })
}

function ajaxPost(uri='', data={}, callback={before,complete}, headers=''){
    var method = 'POST'
    var headers = headers || {
        "accept": "application/json",
        "content-type": "application/x-www-form-urlencoded",
        "Access-Control-Allow-Origin": "*"
    }
    return new Promise((resolve, reject) => {
        ajaxFetch(uri, data, callback, headers, method)
        .then(res => resolve(res))
        .catch(e => reject(e))
    })
}

function ajaxGet(uri='', callback={before,complete}, headers=''){
    var method = 'GET'
    var headers = headers || {
        "accept": "application/json",
        "content-type": "application/x-www-form-urlencoded",
        "Access-Control-Allow-Origin": "*"
    }
    return new Promise((resolve, reject) => {
        ajaxFetch(uri, null, callback, headers, method)
        .then(res => resolve(res))
        .catch(e => reject(e))
    })
}

function ajaxErrorCallback(selector, message){
    var pathSelector = '.msg-error-'+selector;
    return new Promise((resolve, reject) => {
        try {
            resolve($(pathSelector).find('span').text(message));
        } catch (error) {
            reject(error)
        }
    })
}

function disableSubmitBtn(selector, boolean){
    $(selector).attr('disabled', boolean)
}

// Set array to param
function param(object){
    var parameters = [];
    for (var property in object) {
        if (object.hasOwnProperty(property)) {
            parameters.push(encodeURI(property + '=' + object[property]));
        }
    }
    return parameters.join('&');
}
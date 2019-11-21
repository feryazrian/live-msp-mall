$(document).ready(function() {
    $("form").submit(function() {
        $(this).submit(function() {
            return false;
        });
        return true;
    }); 
    //manual
    if ($("input[name=check_type]:checked").val() == "0") {
        showAll();
        clearPersentase();
        hidePersentase();
    }
    //otomatis
    else if ($("input[name=check_type]:checked").val() == "1") {
        showAll();
        clearRupiah();
        hideRupiah();
    }

    $('.listing').click(function(){
        if ($(this).val() == "0") {
            showAll();
            hidePersentase();
        } else if ($(this).val() == "1"){
            showAll();
            hideRupiah();
        }
    });
    $('#select_all_product_type').click(function(event) { 
        console.log("Masuk");
        if(this.checked) { // check select status
            $('.product_type').each(function() { 
                this.checked = true;  //select all 
            });
        }else{
            $('.product_type').each(function() { 
                this.checked = false; //deselect all             
            });        
        }
    });
    $('#select_all_ppob_type').click(function(event) { 
        console.log("Masuk");
        if(this.checked) { // check select status
            $('.ppob_type').each(function() { 
                this.checked = true;  //select all 
            });
        }else{
            $('.ppob_type').each(function() { 
                this.checked = false; //deselect all             
            });        
        }
    });
});



function toggle(source) {
    var checkboxes = document.querySelectorAll('input[type="checkbox"]');
    for (var i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i] != source)
            checkboxes[i].checked = source.checked;
    }
}


function hideRupiah() {
    $('#discount_price_form').hide();
}

function hidePersentase() {
    $('#discount_max_form').hide();
    $('#discount_percent_form').hide();
}

function hideAll() {
    $('#discount_price_form').hide();
    $('#discount_max_form').hide();
    $('#discount_percent_form').hide();
}

function showRupiah() {
    $('#discount_price_form').show();
}

function showPersentase() {
    $('#discount_max_form').show();
    $('#discount_percent_form').show();
}

function showAll() {
    $('#discount_price_form').show();
    $('#discount_max_form').show();
    $('#discount_percent_form').show();
}

function clearPersentase(){
    var discount_max = document.getElementById('discount_max');
    var discount_percent = document.getElementById('discount_percent');
    discount_max.val = null;
    discount_percent.val = null;

}

function clearRupiah(){
    var discount_price = document.getElementById('discount_price');
    discount_price.val = null;
}

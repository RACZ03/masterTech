var idOrders = '',
    found = ''
$(document).ready(function() {
    console.log(dataT)
})  


function deleteCustomer(Id){
    Swal.fire({
        title: '¿Está seguro de que desea eliminar el registro?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, Eliminar!'
    }).then((result) => {
        if (result.isConfirmed) {
        window.location = '<?php echo WEB_URL; ?>repaircar/carlist.php?id=' + Id;
        }
    });
}

// Get value Orders
function onSelectOrders(e) {
    idOrders = e.value;
}

function onSearchOrders(opt = 1) {
    found = dataT.find( x => x.repair_car_id == idOrders);
    
    if ( found != undefined && opt == 1 ) {
        showDetail(found);
    } else if ( opt == 2 ) {
        showDetail2(found);
    } else {
        // Show alert
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: '¡Algo salió mal!',
        })
    }
}

function showDetail(found) {
    // console.log(found);
    $('#txtMar').html(found?.make_name);
    $('#txtModels').html(found?.model_name);
    $('#txtSerie').html(found?.year_name);

    var d = new Date(found?.added_date),
        n_date = d.getDate() + '/' + (d.getMonth()+1) + '/' + d.getFullYear();
    $('#txtDate').html(n_date);

    var p = window.location.href;
    _newp = p.split('?')[0];
    var path = _newp.replace('repaircar/carlist.php', '');
    
    var _link = `${ path }estimate/estimate_form.php?carid=${ found?.car_id }&customer_id=${ found?.customer_id }&mechanics_id=${ found?.mechanics_id }`;
    
    $('#addCotization').attr('disabled', false);
    $('#btnConsultC').attr('disabled', false);
    $('#addCotization').attr('href', _link);

}

function showDetail2(found) {
    console.log(found);
    
    var p = window.location.href;
    _newp = p.split('?')[0];
    location.href = `${ _newp }?estimate_no=${ idOrders }`
    // $('#txtMar').html(found?.make_name);
}

$('#btnConsultC').click( function() {
    var p = window.location.href;
    _newp = p.split('?')[0];
    location.href = `${ _newp }?car_id=${ found?.car_id }&customer_id=${ found?.customer_id }&mechanics_id=${ found?.mechanics_id }`

})

function onCloseModal() {
    var p = window.location.href;
    var path = p.split('?');
    location.href = `${ path[0] }`
}
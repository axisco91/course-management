var centersTable

var centersInicializer = () => {
    centersTable = $('#centersTable').DataTable({
        "ajax":{
            url: '/centers/rest',
            data: ''
        },
        "processing": true,
        "searching": true,
        "bLengthChange": false,
        "bPaginate": false,
        "language": {
            "zeroRecords": "No se encontraron resultados",
            "info": "Mostrando página _PAGE_ de _PAGES_",
            "infoEmpty": "No se han encontrado registros",
            "infoFiltered": "(filtrados _MAX_ como máximo)",
            "loadingRecords": 'Procesando ...',
            "search":'Búsqueda',
            "sProcessing": function(){
            },
            "paginate": {
                "first":      "Primero",
                "last":       "Último",
                "next":       "Siguiente",
                "previous":   "Anterior"
            },
            "aria": {
                "sortAscending":  ": activar para ordenar ascendente",
                "sortDescending": ": activar para ordenar descendente"
            }
        },
        "columns": [
            { "data": "name" },
            { "data": "address" },
            { "data": "email" },
            { "data": "telephone" },
            {"data": "accions"}
        ],


        "initComplete": function(){

        }
    })}

window.onload = centersInicializer

$('body').on('click', '#saveCenter', function(){
    new Swal({
        title: 'Espere por favor',
        allowOutsideClick: false
    })
    Swal.showLoading()
    $.ajax({
        url: 'store',
        data: {
            nombre: $('#name').val(),
            address: $('#address').val(),
            email: $('#email').val(),
            telephone: $('#telephone').val()
        },
        type: 'GET',
        dataType: 'json',
        success: function(json) {
            if (json.status === 200){
                console.log(json)
                Swal.fire('Creado',
                    '¡Centro creado con exito!',
                    'success',
                ).then((result) => {
                    if (result.value) {
                        window.location.href = `/centers`
                    }
                })
            }
        }, error: function(xhr, status){
            if (status == 'error'){
                let error_message = ''
                message = JSON.parse(xhr.responseText).error
                if (message != ''){
                    for (const mess in message){
                        error_message += message[mess][0]+'<br>'
                    }
                    Swal.fire('Error', error_message, 'error')
                } else{
                    Swal.fire('Error', 'Error vuelve a intentarlo mas tarde', 'error')
                }
            }

        }
    })
})

$('body').on('click', '#updateCenter', function(){
    new Swal({
        title: 'Espere por favor',
        allowOutsideClick: false
    })
    Swal.showLoading()
    $.ajax({
        url:  '/centers/update',
        data: {
            id: $('#center_id').val(),
            nombre: $('#name').val(),
            address: $('#address').val(),
            email: $('#email').val(),
            telephone: $('#telephone').val(),
        },
        type: 'GET',
        dataType: 'json',
        success: function(json) {
            if (json.status === 200){
                Swal.fire('Actualizado',
                    '¡Centro actualizado con exito!',
                    'success',
                ).then((result) => {
                    if (result.value) {
                        window.location.href = `/centers`
                    }
                })
            }
        }, error: function(xhr, status){
            if (status == 'error'){
                let error_message = ''
                message = JSON.parse(xhr.responseText).error
                if (message != ''){
                    for (const mess in message){
                        error_message += message[mess][0]+'<br>'
                    }
                    Swal.fire('Error', error_message, 'error')
                } else{
                    Swal.fire('Error', 'Error vuelve a intentarlo mas tarde', 'error')
                }
            }

        }
    })
})

$('body').on('click', '.deleteCenter', function(){
    Swal.fire({
        title: '¿Estas seguro?',
        text: 'No podras revertirlo',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Si, eliminar centro'
    }).then((result) => {
        if (result.value) {
            new Swal({
                title: 'Espere por favor',
                allowOutsideClick: false
            })
            Swal.showLoading()
            $.ajax({
                url:  '/centers/delete',
                data: {
                    id: $(this).data('id'),
                },
                type: 'GET',
                dataType: 'json',
                success: function(json) {
                    if (json.status === 200){
                        Swal.fire('Eliminado',
                            '¡Centro eliminado con exito!',
                            'success',
                        ).then((result) => {
                            if (result.value) {
                                centersTable.ajax.reload()
                            }
                        })
                    }
                }, error: function(xhr, status){
                    if (status == 'error'){
                        let error_message = ''
                        message = JSON.parse(xhr.responseText).error
                        if (message != ''){
                            for (const mess in message){
                                error_message += message[mess][0]+'<br>'
                            }
                            Swal.fire('Error', error_message, 'error')
                        } else{
                            Swal.fire('Error', 'Error vuelve a intentarlo mas tarde', 'error')
                        }
                    }

                }
            })
        }
    })
})

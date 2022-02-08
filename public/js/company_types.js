var typesTable

var typesInicializer = () => {
    typesTable = $('#typesTable').DataTable({
        "ajax":{
            url: '/company_types/rest',
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
            { "data": "types" },
            { "data": "accions" },
        ],


        "initComplete": function(){

        }
    })}

window.onload = typesInicializer

$('body').on('click', '#createType', function(){
    Swal.fire({
        title: 'Crear Tipo',
        inputLabel: 'Introduce nombre de tipo',
        input: 'text',
        showCancelButton: true,
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Guardar',
        inputValidator: (value) => {
            if (!value) {
                return 'Debes introducir un tipo'
            }
        }
    }).then((result) => {
        if (result.value) {
            new Swal({
                title: 'Espere por favor',
                allowOutsideClick: false
            })
            Swal.showLoading()
            $.ajax({
                url:  '/company_types/store',
                data: {
                    tipo: result.value,
                },
                type: 'GET',
                dataType: 'json',
                success: function(json) {
                    if (json.status === 200){
                        console.log(json)
                        Swal.fire('Creado',
                            '¡Tipo creado con exito!',
                            'success',
                        ).then((result) => {
                            if (result.value) {
                                typesTable.ajax.reload()
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

$('body').on('click', '#updateType', function(){
    Swal.fire({
        title: 'Editar Tipo',
        inputLabel: 'Introduce nombre de tipo',
        input: 'text',
        inputValue: $(this).data('type'),
        showCancelButton: true,
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Guardar',
        inputValidator: (value) => {
            if (!value) {
                return 'Debes introducir un tipo'
            }
        }
    }).then((result) => {
        if (result.value) {
            new Swal({
                title: 'Espere por favor',
                allowOutsideClick: false
            })
            Swal.showLoading()
            $.ajax({
                url:  '/company_type/update',
                data: {
                    id: $(this).data('id'),
                    type: result.value,
                },
                type: 'GET',
                dataType: 'json',
                success: function(json) {
                    if (json.status === 200){
                        console.log(json)
                        Swal.fire('Editado',
                            '¡Tipo editado con exito!',
                            'success',
                        ).then((result) => {
                            if (result.value) {
                                typesTable.ajax.reload()
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

$('body').on('click', '#deleteType', function(){
    Swal.fire({
        title: '¿Estas seguro?',
        text: 'No podras revertirlo',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Si, eliminar tipo'
    }).then((result) => {
        if (result.value) {
            new Swal({
                title: 'Espere por favor',
                allowOutsideClick: false
            })
            Swal.showLoading()
            $.ajax({
                url:  '/company_type/delete',
                data: {
                    id: $(this).data('id'),
                },
                type: 'GET',
                dataType: 'json',
                success: function(json) {
                    if (json.status === 200){
                        Swal.fire('Eliminado',
                            '¡Tipo eliminado con exito!',
                            'success',
                        ).then((result) => {
                            if (result.value) {
                                typesTable.ajax.reload()
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

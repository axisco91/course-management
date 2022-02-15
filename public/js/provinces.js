var provincesTable

var provincesInicializer = () => {
    provincesTable = $('#provincesTable').DataTable({
        "ajax":{
            url: '/provinces/rest',
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
            { "data": "accions" },
        ],


        "initComplete": function(){

        }
    })}

window.onload = provincesInicializer

$('body').on('click', '#createProvince', function(){
    Swal.fire({
        title: 'Crear Provincia',
        inputLabel: 'Introduce nombre de provincia',
        input: 'text',
        showCancelButton: true,
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Guardar',
        inputValidator: (value) => {
            if (!value) {
                return 'Debes introducir una provincia'
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
                url:  '/provincias/store',
                data: {
                    nombre: result.value,
                },
                type: 'GET',
                dataType: 'json',
                success: function(json) {
                    if (json.status === 200){
                        console.log(json)
                        Swal.fire('Creado',
                            '¡Provincia creado con exito!',
                            'success',
                        ).then((result) => {
                            if (result.value) {
                                provincesTable.ajax.reload()
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

$('body').on('click', '#updateProvince', function(){
    Swal.fire({
        title: 'Editar Provincia',
        inputLabel: 'Introduce nombre de provincia',
        input: 'text',
        inputValue: $(this).data('name'),
        showCancelButton: true,
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Guardar',
        inputValidator: (value) => {
            if (!value) {
                return 'Debes introducir una provincia'
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
                url:  '/provinces/update',
                data: {
                    id: $(this).data('id'),
                    nombre: result.value,
                },
                type: 'GET',
                dataType: 'json',
                success: function(json) {
                    if (json.status === 200){
                        console.log(json)
                        Swal.fire('Editado',
                            '¡Provincia editado con exito!',
                            'success',
                        ).then((result) => {
                            if (result.value) {
                                provincesTable.ajax.reload()
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

$('body').on('click', '#deleteProvince', function(){
    Swal.fire({
        title: '¿Estas seguro?',
        text: 'No podras revertirlo',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Si, eliminar provincia'
    }).then((result) => {
        if (result.value) {
            new Swal({
                title: 'Espere por favor',
                allowOutsideClick: false
            })
            Swal.showLoading()
            $.ajax({
                url:  '/provinces/delete',
                data: {
                    id: $(this).data('id'),
                },
                type: 'GET',
                dataType: 'json',
                success: function(json) {
                    if (json.status === 200){
                        Swal.fire('Eliminado',
                            '¡Provincia eliminado con exito!',
                            'success',
                        ).then((result) => {
                            if (result.value) {
                                provincesTable.ajax.reload()
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

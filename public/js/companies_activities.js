var activitiesTable

var activitiesInicializer = () => {
    activitiesTable = $('#activitiesTable').DataTable({
        "ajax":{
            url: '/companies_activities/rest',
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
            { "data": "activity" },
            { "data": "accions" },
        ],


        "initComplete": function(){

        }
    })}

window.onload = activitiesInicializer

$('body').on('click', '#createActivity', function(){
    Swal.fire({
        title: 'Crear Actividad',
        inputLabel: 'Introduce nombre de actividad',
        input: 'text',
        showCancelButton: true,
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Guardar',
        inputValidator: (value) => {
            if (!value) {
                return 'Debes introducir una actividad'
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
                url:  '/companies_activities/store',
                data: {
                    actividad: result.value,
                },
                type: 'GET',
                dataType: 'json',
                success: function(json) {
                    if (json.status === 200){
                        console.log(json)
                        Swal.fire('Creado',
                            '¡Actividad creado con exito!',
                            'success',
                        ).then((result) => {
                            if (result.value) {
                                activitiesTable.ajax.reload()
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

$('body').on('click', '#updateActivity', function(){
    Swal.fire({
        title: 'Editar Actividad',
        inputLabel: 'Introduce nombre de actividad',
        input: 'text',
        inputValue: $(this).data('activity'),
        showCancelButton: true,
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Guardar',
        inputValidator: (value) => {
            if (!value) {
                return 'Debes introducir una actividad'
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
                url:  '/companies_activities/update',
                data: {
                    id: $(this).data('id'),
                    actividad: result.value,
                },
                type: 'GET',
                dataType: 'json',
                success: function(json) {
                    if (json.status === 200){
                        console.log(json)
                        Swal.fire('Editado',
                            '¡Actividad editado con exito!',
                            'success',
                        ).then((result) => {
                            if (result.value) {
                                activitiesTable.ajax.reload()
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

$('body').on('click', '#deleteActivity', function(){
    Swal.fire({
        title: '¿Estas seguro?',
        text: 'No podras revertirlo',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Si, eliminar actividad'
    }).then((result) => {
        if (result.value) {
            new Swal({
                title: 'Espere por favor',
                allowOutsideClick: false
            })
            Swal.showLoading()
            $.ajax({
                url:  '/companies_activities/delete',
                data: {
                    id: $(this).data('id'),
                },
                type: 'GET',
                dataType: 'json',
                success: function(json) {
                    if (json.status === 200){
                        Swal.fire('Eliminado',
                            '¡Actividad eliminado con exito!',
                            'success',
                        ).then((result) => {
                            if (result.value) {
                                activitiesTable.ajax.reload()
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

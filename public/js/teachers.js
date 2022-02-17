var teachersTable

var teachersInicializer = () => {
teachersTable = $('#teachersTable').DataTable({
    "ajax":{
        url: '/teachers/rest',
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
        { "data": "dni" },
        { "data": "email" },
        { "data": "telephone" },
        {"data": "accions"}
    ],


    "initComplete": function(){

    }
})}

window.onload = teachersInicializer

$('body').on('click', '#saveTeacher', function(){
    new Swal({
        title: 'Espere por favor',
        allowOutsideClick: false
    })
    Swal.showLoading()
    $.ajax({
        url: 'store',
        data: {
            nombre: $('#name').val(),
            apellidos: $('#surname').val(),
            dni: $('#dni').val(),
            email: $('#email').val(),
            telephone: $('#telephone').val(),
            user: $('#user').val(),
            password: $('#password').val(),
            observations: $('#observations').val()
        },
        type: 'GET',
        dataType: 'json',
        success: function(json) {
            if (json.status === 200){
                console.log(json)
                Swal.fire('Creado',
                    '¡Docente creado con exito!',
                    'success',
                    ).then((result) => {
                    if (result.value) {
                        window.location.href = `/teachers`
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

$('body').on('click', '#updateTeacher', function(){
    new Swal({
        title: 'Espere por favor',
        allowOutsideClick: false
    })
    Swal.showLoading()
    $.ajax({
        url:  '/teachers/update_teacher',
        data: {
            id: $('#teacher_id').val(),
            nombre: $('#name').val(),
            apellidos: $('#surname').val(),
            dni: $('#dni').val(),
            email: $('#email').val(),
            telephone: $('#telephone').val(),
            user: $('#user').val(),
            password: $('#password').val(),
            observations: $('#observations').val()
        },
        type: 'GET',
        dataType: 'json',
        success: function(json) {
            if (json.status === 200){
                Swal.fire('Actualizado',
                    '¡Docente actualizado con exito!',
                    'success',
                ).then((result) => {
                    if (result.value) {
                        window.location.href = `/teachers`
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

$('body').on('click', '.deleteTeacher', function(){
    Swal.fire({
        title: '¿Estas seguro?',
        text: 'No podras revertirlo',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Si, eliminar docente'
    }).then((result) => {
        if (result.value) {
            new Swal({
                title: 'Espere por favor',
                allowOutsideClick: false
            })
            Swal.showLoading()
            $.ajax({
                url:  '/teachers/delete',
                data: {
                    id: $(this).data('id'),
                },
                type: 'GET',
                dataType: 'json',
                success: function(json) {
                    if (json.status === 200){
                        Swal.fire('Eliminado',
                            '¡Docente eliminado con exito!',
                            'success',
                        ).then((result) => {
                            if (result.value) {
                                teachersTable.ajax.reload()
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

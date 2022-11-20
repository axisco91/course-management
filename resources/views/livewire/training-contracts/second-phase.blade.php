<div class="card-body">
    <div class="mb-1">
        <a class="btn" href="{{asset('/training_contracts/edit/'.$selected_id)}}">Fase 1</a>
        <a class="btn btn-primary">Fase 2</a>
        <a class="btn" href="{{asset('/training_contracts/excluded_days/'.$selected_id)}}">Dias Excluidos</a>
        <a class="btn" href="{{asset('/training_contract_incidences/'.$selected_id)}}">Historico</a>
    </div>
    <form class="form needs-validation" novalidate>
        <input type="hidden" wire:model="selected_id">
        <div class="row">
        @if($specialties)
            <div class="col-12 col-md-4 mb-1">
                <div wire:ignore>
                    <label class="form-label" for="specialty_id">Especialidades</label>
                    <select wire:model.lazy="specialty_id" class="form-select select2 list" id="specialty_id">
                        <option value="-1">Selecciona especialidad</option>
                        @foreach($specialties as $specialty)
                            <option value="{{$specialty['id']}}">{{$specialty['name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        @endif
        @if($professional_certificate == 1)
            <div class="col-12 col-md-4 mb-1">
                <div wire:ignore>
                    <label class="form-label" for="certification_id">Certificaciones</label>
                    <select wire:model.lazy="certification_id" class="form-select select2 list" id="certification_id">
                        <option value="-1">Selecciona certificación</option>
                        @foreach($certifications as $certification)
                            <option value="{{$certification['id']}}">{{$certification['name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        @endif
        </div>
        <div class="col-12 mb-2">
            @foreach($training_contract_certifications as $training_contract_certification)
                <div class="" style="display: flex; background: white; margin: 5px; padding: 5px; justify-content: space-between;">
                    @if ($training_contract_certification['training_action_id'])
                        <div style="margin-top: 5px;" wire:ignore>
                            {{$training_contract_certification['formative_action']}} - {{$training_contract_certification['training_action_name']}}
                        </div>
                        <div>
                            <span style="background-color: lightgray">Horas: {{$training_contract_certification['training_action_total_hours']}}</span>
                            <span style="background-color: lightgray">Total Dias: {{$training_contract_certification['total_days']}}</span>
                            <span style="background-color: lightgray">Fecha Inicio: {{$training_contract_certification['beginning']}}</span>
                            <span style="background-color: lightgray">Fecha Fin: {{$training_contract_certification['end']}}</span>
                            <div class="btn btn-danger btn-sm unregister" wire:click="unregister({{$training_contract_certification->id}})"><i class="fas fa-minus"></i></div>
                        </div>
                    @elseif($training_contract_certification['certification_id'])
                        <div style="margin-top: 5px;" wire:ignore>
                            {{$training_contract_certification['certification_name']}}
                        </div>
                        <div>
                            <span>Horas: {{$training_contract_certification['training_action_total_hours']}}</span>
                            <span style="background-color: lightgray">Total Dias: {{$training_contract_certification['total_days']}}</span>
                            <span style="background-color: lightgray">Fecha Inicio: {{$training_contract_certification['beginning']}}</span>
                            <span style="background-color: lightgray">Fecha Fin: {{$training_contract_certification['end']}}</span>
                            <div class="btn btn-danger btn-sm unregister" wire:click="unregister({{$training_contract_certification->id}})"><i class="fas fa-minus"></i></div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
        <div class="col-12 mb-1">
            <a wire:ignore class="btn btn-sm btn-info" href="{{url('/training_contracts/create_exams_tutorials/'.$selected_id)}}">
                <i class="fa fa-plus" class="me-50"></i> Añadir Examen / Tutoria
            </a>
        </div>
        <div class="col-12 mb-2">
            <hr class="my-0" />
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Centro</th>
                        <th>Fecha</th>
                        <th>Horario</th>
                        <th>Tipo</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($exams_tutorials as $row)
                        <tr>
                            <th>{{ $row->center }}</th>
                            <td>{{ Carbon\Carbon::parse($row->date)->format('d/m/Y') }}</td>
                            <td>{{ $row->beginning }} - {{$row->end}}</td>
                            <td>{{$row->type}}</td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown">
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item edit" wire:click="edit({{$row['id']}})"><i class="fa-regular fa-pen-to-square"></i> Editar </a>
                                        <a class="dropdown-item eliminate" data-id="{{$row->id}}"><i class="fa fa-trash"></i> Eliminar </a>
                                    </div>
                                </div>
                            </td>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="row">
                <div class="col-md-4 col-12 mb-1">
                    <label class="form-label" for="total_hours">Horas Totales</label>
                    <input wire:model.lazy="total_hours" type="text" class="form-control" id="total_hours" placeholder="Horas Totales" disabled>
                </div>
                <div class="col-md-4 col-12 mb-1">
                    <label class="form-label" for="daily_hours">Horas Diarias</label>
                    <input wire:model.lazy="daily_hours" type="text" class="form-control" id="daily_hours" placeholder="Horas Diarias" disabled>
                </div>
                <div class="col-md-4 col-12 mb-1">
                    <label class="form-label" for="total_days">Dias Totales</label>
                    <input wire:model.lazy="total_days" type="text" class="form-control" id="total_days" placeholder="Dias Totales" disabled>
                </div>
            </div>
            <div class="col-12 mb-1">
                <button id="calculate" type="button" wire:click.prevent="calculate()" class="btn btn-primary" data-bs-dismiss="modal">Calcular</button>
            </div>
        </div>
        <div class="col-12">
            <a href="{{url('/training_contracts')}}" class="btn btn-outlined-secondary">Volver</a>
        </div>
    </form>
@section('vendor-script')
    <!-- vendor files -->
        <script src="{{ asset('app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
@endsection
@section('page-script')
    <!-- Page js files -->
        <script src="{{ asset('app-assets/js/scripts/forms/form-select2.js') }}"></script>
    @endsection
    @section('scripts')
        <script>
            document.addEventListener('livewire:load', function() {
                $( document ).ready(
                    setTimeout(function (){
                        initializeSelect2()
                    }, 100)
                );
                $('.select2').on('change', function(){
                @this.set(this.id, $(this).val())
                })
                $('.list').on('change', function(){
                @this.set(this.id, $(this).val())
                    id = $(this).val();
                    type = $(this).attr('id');
                    Livewire.emit('addElement', id, type)
                })
            })
            $('body').on('click', '#save', function(){
                content = ''
                if ($('#name').val() == ''){
                    content += 'El nombre es requerido<br>'
                }
                if ($('#action_type_id').val() == ''){
                    content += 'El tipo es requerido<br>'
                }
                if ($('#modality_id').val() == ''){
                    content += 'La modalidad es requerido<br>'
                }
                if ($('#training_action_level_id').val() == ''){
                    content += 'El nivel es requerido<br>'
                }
                if ($('#tutoring_id').val() == ''){
                    content += 'La tutoria es requerido<br>'
                }
                if ($('#face_to_face_hours').val() == ''){
                    content += 'Las horas presenciales son requeridos<br>'
                }
                if ($('#teletraining_hours').val() == ''){
                    content += 'Las horas de teletrabajo son requeridos<br>'
                }
                if ($('#price').val() == ''){
                    content += 'El precio es requerido<br>'
                }
                if (content != ''){
                    Swal.fire({
                        icon: 'error',
                        title: 'Falta datos',
                        html: '<div>'+content+'</div>',
                        customClass: {
                            confirmButton: 'btn btn-primary'
                        },
                        buttonsStyling: false
                    });
                }
            })
            $('body').on('click', '.eliminate', function () {
                button = $(this)
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                    title: '¿Estas seguro?',
                    text: "Eliminaras el examen / tutoria!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Si, eliminalo!',
                    cancelButtonText: 'No, cancela!',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        id = $(this).data('id');
                        Livewire.emit('destroy', id)
                        window.addEventListener('eliminated', e=>{
                            if (e.detail.value != ''){
                                swalWithBootstrapButtons.fire(
                                    'Eliminado!',
                                    'Eliminado con exito.',
                                    'success'
                                )
                            } else{
                                swalWithBootstrapButtons.fire(
                                    'Error',
                                    'Fallo al eliminar.',
                                    'error'
                                )
                            }
                        });

                    } else if (
                        /* Read more about handling dismissals below */
                        result.dismiss === Swal.DismissReason.cancel
                    ) {
                        swalWithBootstrapButtons.fire(
                            'Cacelado',
                            'No se ha podido eliminar.',
                            'error'
                        )
                    }
                })
            })
        </script>
    @endsection
</div>

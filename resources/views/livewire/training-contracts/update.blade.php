<div class="card-body">
    <div class="mb-1">
        <a class="btn btn-primary">Fase 1</a>
        <a class="btn" href="{{asset('/training_contracts/second_phase/'.$selected_id)}}">Fase 2</a>
        <a class="btn" href="{{asset('/training_contracts/excluded_days/'.$selected_id)}}">Dias Excluidos</a>
        <a class="btn" href="{{asset('/training_contract_incidences/'.$selected_id)}}">Historico</a>
    </div>
    <form class="form needs-validation" novalidate>
        <input type="hidden" wire:model="selected_id">
        <div class="row">
            <div class="col-12 col-md-3">
                <div class="mb-1">
                    <label class="form-label" for="number_cfa">Número CFA</label>
                    <input wire:model.lazy="number_cfa" type="text" class="form-control" id="number_cfa" placeholder="Número CFA">@error('number_cfa') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="col-12 col-md-3 mb-1">
                <div wire:ignore>
                    <label class="form-label" for="company_id">Empresa</label>
                    <select wire:model.lazy="company_id" class="form-select select2 @error('company_id') is-invalid @enderror" id="company_id">
                        <option value="-1">Seleccione una empresa</option>
                        @foreach($companies as $company)
                            <option value="{{$company['id']}}">{{$company['name']}}</option>
                        @endforeach
                    </select>
                </div>
                @error('company_id') <div class="invalid-feedback">Empresa es requerido</div> @enderror
            </div>
            <div class="col-12 col-md-3 mb-1">
                <div wire:ignore>
                    <label class="form-label" for="student_id">Alumno</label>
                    <select wire:model.lazy="student_id" class="form-select select2 @error('student_id') is-invalid @enderror" id="student_id">
                        <option value="-1">Seleccione un alumno</option>
                        @foreach($students as $student)
                            <option value="{{$student['id']}}">{{$student['name']}} {{$student['surname']}}</option>
                        @endforeach
                    </select>
                </div>
                @error('student_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-12 col-md-3">
                <div class="mb-1">
                    <label class="form-label" for="name">Tutor Empresa</label>
                    <input wire:model.lazy="company_tutor" type="text" class="form-control @error('name') is-invalid @enderror" id="company_tutor" placeholder="Tutor Empresa">@error('name') <div class="invalid-feedback">Tutor empresa es requerido</div> @enderror
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div class="mb-1">
                    <label class="form-label" for="company_tutor_dni">Tutor Empresa DNI</label>
                    <input wire:model.lazy="company_tutor_dni" type="text" class="form-control @error('company_tutor_dni') is-invalid @enderror" id="company_tutor_dni" placeholder="Tutor Empresa DNI">@error('company_tutor_dni') <div class="invalid-feedback">Tutor empresa DNI es requerido</div> @enderror
                </div>
            </div>
            <div class="col-md-3 col-12 mb-1">
                <div wire:ignore>
                    <label class="form-label" for="occupation_id">Ocupación</label>
                    <select wire:model.lazy="occupation_id" class="form-select select2 @error('occupation_id') is-invalid @enderror" id="occupation_id">
                        <option value="">Seleccione una ocupación</option>
                        @foreach($occupations as $occupation)
                            <option value="{{$occupation['id']}}">{{$occupation['name']}}</option>
                        @endforeach
                    </select>
                </div>
                @error('occupation_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-12 col-md-3">
                <div class="mb-1">
                    <label class="form-label" for="center_of_work">Centro de Trabajo</label>
                    <input wire:model.lazy="center_of_work" type="text" class="form-control @error('center_of_work') is-invalid @enderror" id="center_of_work" placeholder="Centro de Trabajo">@error('name') <div class="invalid-feedback">Centro de trabajo es requerido</div> @enderror
                </div>
            </div>
            <div class="col-md-3 col-12 mb-1">
                <div wire:ignore>
                    <label class="form-label" for="province_id">Provincia</label>
                    <select wire:model.lazy="province_id" class="form-select select2 @error('province_id') is-invalid @enderror" id="province_id">
                        <option value="">Seleccione una provincia</option>
                        @foreach($provinces as $province)
                            <option value="{{$province['id']}}">{{$province['name']}}</option>
                        @endforeach
                    </select>
                </div>
                @error('province_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-4 mb-1">
                <label class="form-label">Estado de trabajador</label>
                <br>
                <label class="form-label" for="disabled"><input wire:model.lazy="disabled" id="disabled" type="checkbox" id="disabled" value="disabled"> Discapacitado</label>
                @error('disabled') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-4 mb-1">
                <br>
                <label class="form-label" for="youth_guarantee"><input wire:model.lazy="youth_guarantee" id="youth_guarantee" type="checkbox" id="youth_guarantee" value="youth_guarantee"> Garantia Juvenil</label>
                @error('youth_guarantee') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-4 mb-1">
                <br>
                <label class="form-label" for="social_exclusion"><input wire:model.lazy="social_exclusion" id="social_exclusion" type="checkbox" id="social_exclusion" value="social_exclusion"> Exclusión Social</label>
                @error('social_exclusion') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-3 mb-1">
                <label class="form-label">Tipo Contrato</label>
                <br>
                <label class="form-label" for="specialty"><input wire:model.lazy="specialty" id="specialty" type="checkbox" id="specialty" value="specialty"> Especialidad</label>
                @error('specialty') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-3 mb-1">
                <br>
                <label class="form-label" for="professional_certificate"><input wire:model.lazy="professional_certificate" id="professional_certificate" type="checkbox" id="professional_certificate" value="professional_certificate"> Certificado Profesionalidad</label>
                @error('professional_certificate') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-3 mb-1"></div>
            <div class="col-md-4 col-12 mb-1">
                <label class="form-label" for="beginning">Fecha Inicio</label>
                <input wire:model.lazy="beginning" type="date" class="form-control" id="beginning">@error('beginning') <div class="invalid-feedback">Fecha de inicio es requerido</div> @enderror
            </div>
            <div class="col-md-4 col-12 mb-1">
                <label class="form-label" for="end">Fecha Fin</label>
                <input wire:model.lazy="end" type="date" class="form-control" id="end">@error('end') <div class="invalid-feedback">Fecha de fin es requerido</div> @enderror
            </div>
            <div class="col-md-4 col-12 mb-1">
                <label class="form-label" for="beginning_formation">Fecha Inicio Formación</label>
                <input wire:model.lazy="beginning_formation" type="date" class="form-control" id="beginning_formation">@error('beginning_formation') <div class="invalid-feedback">Fecha de inicio de formación es requerido</div> @enderror
            </div>
            <div class="col-md-4 col-12 mb-1">
                <label class="form-label" for="end_formation">Fecha Fin formación</label>
                <input wire:model.lazy="end_formation" type="date" class="form-control" id="end_formation">@error('end_formation') <div class="invalid-feedback">Fecha de fin de formación es requerido</div> @enderror
            </div>
            <div class="col-md-4 col-12 mb-1">
                <label class="form-label" for="formation_hours">Horas Formación</label>
                <input wire:model.lazy="formation_hours" type="numeric" class="form-control" id="formation_hours">@error('formation_hours') <div class="invalid-feedback"></div> @enderror
            </div>
            <div class="col-md-4 col-12 mb-1">
                <label class="form-label" for="annually_day_hours">Horas Jornada Anual</label>
                <input wire:model.lazy="annually_day_hours" type="numeric" class="form-control" id="annually_day_hours">@error('annually_day_hours') <div class="invalid-feedback"></div> @enderror
            </div>
            <div class="col-12 col-md-3">
                <div class="mb-1">
                    <label class="form-label" for="bonus_hours_first_year">Horas Bonificación 1º Año</label>
                    <input wire:model.lazy="bonus_hours_first_year" type="text" class="form-control @error('bonus_hours_first_year') is-invalid @enderror" id="bonus_hours_first_year" disabled>@error('bonus_hours_first_year') <div class="invalid-feedback"></div> @enderror
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div class="mb-1">
                    <label class="form-label" for="percentage_first_year">Porcentaje 1º Año</label>
                    <input wire:model.lazy="percentage_first_year" type="text" class="form-control @error('percentage_first_year') is-invalid @enderror" id="percentage_first_year">@error('percentage_first_year') <div class="invalid-feedback"></div> @enderror
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div class="mb-1">
                    <label class="form-label" for="formative_hours_first_year">Horas Formativas 1º Año</label>
                    <input wire:model.lazy="formative_hours_first_year" type="text" class="form-control @error('formative_hours_first_year') is-invalid @enderror" id="formative_hours_first_year">@error('formative_hours_first_year') <div class="invalid-feedback"></div> @enderror
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div class="mb-1">
                    <label class="form-label" for="bonus_hours_second_year">Horas Bonificación 2º Año</label>
                    <input wire:model.lazy="bonus_hours_second_year" type="text" class="form-control @error('bonus_hours_second_year') is-invalid @enderror" id="bonus_hours_second_year" placeholder="" disabled>@error('bonus_hours_second_year') <div class="invalid-feedback"></div> @enderror
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div class="mb-1">
                    <label class="form-label" for="percentage_second_year">Porcentaje 2º Año</label>
                    <input wire:model.lazy="percentage_second_year" type="text" class="form-control @error('percentage_second_year') is-invalid @enderror" id="percentage_second_year" placeholder="">@error('percentage_second_year') <div class="invalid-feedback"></div> @enderror
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div class="mb-1">
                    <label class="form-label" for="formative_hours_second_year">Horas Bonificación 2º Año</label>
                    <input wire:model.lazy="formative_hours_second_year" type="text" class="form-control @error('formative_hours_second_year') is-invalid @enderror" id="formative_hours_second_year" placeholder="">@error('formative_hours_second_year') <div class="invalid-feedback"></div> @enderror
                </div>
            </div>
            <div class="col-3 mb-1"></div>
            <div class="col-3 mb-1"></div>
            <div class="col-3 mb-1">
                <label class="form-label">Dias de Impartición</label>
                <br>
                <label class="form-label" for="monday"><input wire:model.lazy="monday" id="monday" type="checkbox" id="monday" value="monday"> Lunes</label>
                @error('monday') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-3 mb-1">
                <br>
                <label class="form-label" for="tuesday"><input wire:model.lazy="tuesday" id="tuesday" type="checkbox" id="tuesday" value="tuesday"> Martes</label>
                @error('tuesday') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-3 mb-1">
                <br>
                <label class="form-label" for="wednesday"><input wire:model.lazy="wednesday" id="wednesday" type="checkbox" id="wednesday" value="wednesday"> Miercoles</label>
                @error('wednesday') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-3 mb-1">
                <br>
                <label class="form-label" for="thursday"><input wire:model.lazy="thursday" id="thursday" type="checkbox" id="thursday" value="thursday"> Jueves</label>
                @error('thursday') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-3 mb-1">
                <br>
                <label class="form-label" for="friday"><input wire:model.lazy="friday" id="friday" type="checkbox" id="friday" value="friday"> Viernes</label>
                @error('friday') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-3 mb-1">
                <br>
                <label class="form-label" for="saturday"><input wire:model.lazy="saturday" id="saturday" type="checkbox" id="saturday" value="saturday"> Sabado</label>
                @error('saturday') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-3 mb-1">
                <br>
                <label class="form-label" for="sunday"><input wire:model.lazy="sunday" id="sunday" type="checkbox" id="sunday" value="sunday"> Domingo</label>
                @error('sunday') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4 col-12 mb-1">
                <label class="form-label" for="training_schedule">Horario Formación</label>
                <input wire:model.lazy="training_schedule" type="text" class="form-control" id="training_schedule" placeholder="Horario Formación">@error('training_schedule') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4 col-12 mb-1">
                <label class="form-label" for="working_hours">Horario Laboral</label>
                <input wire:model.lazy="working_hours" type="text" class="form-control" id="working_hours" placeholder="Horario Laboral">@error('working_hours') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4 col-12 mb-1">
                <label class="form-label" for="complete_schedule">Horario Completo</label>
                <input wire:model.lazy="complete_schedule" type="text" class="form-control" id="complete_schedule" placeholder="Horario Completo">@error('complete_schedule') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-3 col-12 mb-1">
                <div wire:ignore>
                    <label class="form-label" for="training_contract_status_id">Estado</label>
                    <select wire:model.lazy="training_contract_status_id" class="form-select select2 @error('training_contract_status_id') is-invalid @enderror" id="training_contract_status_id">
                        <option value="">Seleccione un estado</option>
                        @foreach($training_contract_statuses as $training_contract_status)
                            <option value="{{$training_contract_status['id']}}">{{$training_contract_status['name']}}</option>
                        @endforeach
                    </select>
                </div>
                @error('training_contract_status_id') <div class="invalid-feedback">Estado</div> @enderror
            </div>
            <div class="col-md-3 col-12 mb-1">
                <div wire:ignore>
                    <label class="form-label" for="on_leave_type_id">Baja</label>
                    <select wire:model.lazy="on_leave_type_id" class="form-select select2 @error('on_leave_type_id') is-invalid @enderror" id="on_leave_type_id">
                        <option value="">Seleccione una baja</option>
                        @foreach($on_leave_types as $on_leave_type)
                            <option value="{{$on_leave_type['id']}}">{{$on_leave_type['name']}}</option>
                        @endforeach
                    </select>
                </div>
                @error('on_leave_type_id') <div class="invalid-feedback">Baja es requerido</div> @enderror
            </div>
            <div class="col-md-4 col-12 mb-1">
                <label class="form-label" for="on_leave_date">Fecha Baja</label>
                <input wire:model.lazy="on_leave_date" type="date" class="form-control" id="on_leave_date">@error('on_leave_date') <div class="invalid-feedback">Fecha de baja es requerido</div> @enderror
            </div>
            <div class="col-md-3 col-12 mb-1">
                <div wire:ignore>
                    <label class="form-label" for="advisor_id">Asesoria</label>
                    <select wire:model.lazy="advisor_id" class="form-select select2 @error('advisor_id') is-invalid @enderror" id="advisor_id">
                        <option value="">Seleccione una asesoria</option>
                        @foreach($advisors as $advisor)
                            <option value="{{$advisor['id']}}">{{$advisor['name']}}</option>
                        @endforeach
                    </select>
                </div>
                @error('advisor_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-3 col-12 mb-1">
                <div wire:ignore>
                    <label class="form-label" for="collaborator_id">Colaborador</label>
                    <select wire:model.lazy="collaborator_id" class="form-select select2 @error('collaborator_id') is-invalid @enderror" id="collaborator_id">
                        <option value="">Seleccione un collaborador</option>
                        @foreach($collaborators as $collaborator)
                            <option value="{{$collaborator['id']}}">{{$collaborator['name']}} {{$collaborator['surname']}}</option>
                        @endforeach
                    </select>
                </div>
                @error('tutoring_id') <div class="invalid-feedback">Colaborador es requerido</div> @enderror
            </div>
            <div class="col-md-3 col-12 mb-1">
                <div wire:ignore>
                    <label class="form-label" for="provider_id">Proveedores</label>
                    <select wire:model.lazy="provider_id" class="form-select select2 @error('provider_id') is-invalid @enderror" id="provider_id">
                        <option value="">Seleccione un proveedor</option>
                        @foreach($providers as $provider)
                            <option value="{{$provider['id']}}">{{$provider['name']}}</option>
                        @endforeach
                    </select>
                </div>
                @error('provider_id') <div class="invalid-feedback">Colaborador es requerido</div> @enderror
            </div>
        </div>
        <div class="col-12">
            <a href="{{url('/training_contracts')}}" class="btn btn-outlined-secondary">Volver</a>
            <button id="save" type="button" wire:click.prevent="update()" class="btn btn-primary">Guardar</button>
            <button id="save" type="button" wire:click="calculate" class="btn btn-primary">Calcular Horas</button>
        </div>
    </form>
    @section('vendor-script')
        <!-- vendor files -->
            <script src="{{ asset('app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
            <script src="{{ asset('app-assets/vendors/js/extensions/toastr.min.js') }}"></script>
    @endsection
    @section('page-script')
        <!-- Page js files -->
        <script src="{{ asset('app-assets/js/scripts/forms/form-select2.js') }}"></script>
        <script src="{{ asset('app-assets/js/scripts/extensions/ext-component-toastr.js') }}"></script>
    @endsection
    @section('scripts')
    <script>
        Livewire.on('toastr', type => {
            if (type == 'success'){
                toastr['success']($('#success-toast').val(), {
                    showMethod: 'slideDown',
                    hideMethod: 'slideUp',
                    timeOut: 2000,
                });
            } else{
                toastr['warning']($('#success-toast').val(), {
                    showMethod: 'slideDown',
                    hideMethod: 'slideUp',
                    timeOut: 2000,
                });
            }
        })
        document.addEventListener('livewire:load', function() {
            $( document ).ready(
                setTimeout(function (){
                    initializeSelect2()
                }, 100)
            );
            $('.select2').on('change', function(){
            @this.set(this.id, $(this).val())
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
    </script>
    @endsection
</div>

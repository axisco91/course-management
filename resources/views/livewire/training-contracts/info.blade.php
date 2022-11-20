<div class="row">
    <div class="col-12 mb-1">
        <a class="btn btn-success right" href="{{url('/training_contracts/edit/'.$this->selected_id)}}" target="_blank"><i class="fa-solid fa-pencil"></i></a>
    </div>
    <div class="col-12 col-md-3">
        <div class="mb-1">
            <label class="form-label" for="number_cfa">Número CFA</label>
            <input wire:model.lazy="number_cfa" type="text" class="form-control" id="number_cfa" disabled placeholder="Número CFA">
        </div>
    </div>
    <div class="col-12 col-md-3 mb-1">
        <label class="form-label" for="company_id">Empresa</label>
        <select wire:model.lazy="company_id" class="form-select @error('company_id') is-invalid @enderror" id="company_id" disabled>
            <option value="-1">Seleccione una empresa</option>
            @foreach($companies as $company)
                <option value="{{$company['id']}}">{{$company['name']}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-12 col-md-3 mb-1">
        <label class="form-label" for="student_id">Alumno</label>
        <select wire:model.lazy="student_id" class="form-select" id="student_id" disabled>
            <option value="-1">Seleccione un alumno</option>
            @foreach($students as $student)
                <option value="{{$student['id']}}">{{$student['name']}} {{$student['surname']}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-12 col-md-3">
        <div class="mb-1">
            <label class="form-label" for="name">Tutor Empresa</label>
            <input wire:model.lazy="company_tutor" type="text" class="form-control" id="company_tutor" placeholder="Tutor Empresa" disabled>
        </div>
    </div>
    <div class="col-12 col-md-3">
        <div class="mb-1">
            <label class="form-label" for="company_tutor_dni">Tutor Empresa DNI</label>
            <input wire:model.lazy="company_tutor_dni" type="text" class="form-control" id="company_tutor_dni" placeholder="Tutor Empresa DNI" disabled>
        </div>
    </div>
    <div class="col-md-3 col-12 mb-1">
        <div wire:ignore>
            <label class="form-label" for="occupation_id">Ocupación</label>
            <select wire:model.lazy="occupation_id" class="form-select" id="occupation_id" disabled>
                <option value="-1">Seleccione una ocupación</option>
                @foreach($occupations as $occupation)
                    <option value="{{$occupation['id']}}">{{$occupation['name']}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-12 col-md-3">
        <div class="mb-1">
            <label class="form-label" for="center_of_work">Centro de Trabajo</label>
            <input wire:model.lazy="center_of_work" type="text" class="form-control" id="center_of_work" placeholder="Centro de Trabajo" disabled>
        </div>
    </div>
    <div class="col-md-3 col-12 mb-1">
        <label class="form-label" for="province_id">Provincia</label>
        <select wire:model.lazy="province_id" class="form-select select2" id="province_id" disabled>
            <option value="-1">Seleccione una provincia</option>
            @foreach($provinces as $province)
                <option value="{{$province['id']}}">{{$province['name']}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2 col-12 mb-1">
        <label class="form-label" for="worker_status">Estado Trabajador</label>
        <select wire:model.lazy="worker_status" class="form-select" id="worker_status" disabled>
            <option value="0">Discapacitado</option>
            <option value="1">Garantia Juvenil</option>
            <option value="2">Exclusión Social</option>
        </select>
    </div>
    <div class="col-md-2 col-12 mb-1">
        <label class="form-label" for="contract_type">Tipo Contrato</label>
        <select wire:model.lazy="contract_type" class="form-select" id="contract_type" disabled>
            <option value="0">Especialidad</option>
            <option value="1">Certificado Profesionalidad</option>
        </select>
    </div>
    <div class="col-md-4 col-12 mb-1">
        <label class="form-label" for="beginning">Fecha Inicio</label>
        <input wire:model.lazy="beginning" type="date" class="form-control" id="beginning" disabled>
    </div>
    <div class="col-md-4 col-12 mb-1">
        <label class="form-label" for="end">Fecha Fin</label>
        <input wire:model.lazy="end" type="date" class="form-control" id="end" disabled>
    </div>
    <div class="col-md-4 col-12 mb-1">
        <label class="form-label" for="beginning_formation">Fecha Inicio Formación</label>
        <input wire:model.lazy="beginning_formation" type="date" class="form-control" id="beginning_formation" disabled>
    </div>
    <div class="col-md-4 col-12 mb-1">
        <label class="form-label" for="end_formation">Fecha Fin formación</label>
        <input wire:model.lazy="end_formation" type="date" class="form-control" id="end_formation" disabled>
    </div>
    <div class="col-md-4 col-12 mb-1">
        <label class="form-label" for="formation_hours">Horas Formación</label>
        <input wire:model.lazy="formation_hours" type="numeric" class="form-control" id="formation_hours" disabled>
    </div>
    <div class="col-md-4 col-12 mb-1">
        <label class="form-label" for="annually_day_hours">Horas Jornada Annual</label>
        <input wire:model.lazy="annually_day_hours" type="numeric" class="form-control" id="annually_day_hours" disabled>
    </div>
    <div class="col-12 col-md-3">
        <div class="mb-1">
            <label class="form-label" for="bonus_hours_first_year">Horas Bonificación 1º Año</label>
            <input wire:model.lazy="bonus_hours_first_year" type="text" class="form-control" id="bonus_hours_first_year" disabled>
        </div>
    </div>
    <div class="col-12 col-md-3">
        <div class="mb-1">
            <label class="form-label" for="bonus_hours_second_year">Horas Bonificación 2º Año</label>
            <input wire:model.lazy="bonus_hours_second_year" type="text" class="form-control" id="bonus_hours_second_year" placeholder="" disabled>
        </div>
    </div>
    <div class="form-group col-3 mb-1">
        <label class="form-label">Dias de Impartición</label>
        <br>
        <label class="form-label" for="monday"><input {{$this->monday == 1 ? 'checked' : ''}} id="monday" type="checkbox" id="monday" value="monday" disabled> Lunes</label>
    </div>
    <div class="form-group col-3 mb-1">
        <br>
        <label class="form-label" for="tuesday"><input {{$this->tuesday == 1 ? 'checked' : ''}} id="tuesday" type="checkbox" id="tuesday" value="tuesday" disabled> Martes</label>
    </div>
    <div class="form-group col-3 mb-1">
        <br>
        <label class="form-label" for="wednesday"><input {{$this->wednesday == 1 ? 'checked' : ''}} id="wednesday" type="checkbox" id="wednesday" value="wednesday" disabled> Miercoles</label>
    </div>
    <div class="form-group col-3 mb-1">
        <br>
        <label class="form-label" for="thursday"><input {{$this->thursday == 1 ? 'checked' : ''}} id="thursday" type="checkbox" id="thursday" value="thursday" disabled> Jueves</label>
    </div>
    <div class="form-group col-3 mb-1">
        <br>
        <label class="form-label" for="friday"><input {{$this->friday == 1 ? 'checked' : ''}} id="friday" type="checkbox" id="friday" value="friday" disabled> Viernes</label>
    </div>
    <div class="form-group col-3 mb-1">
        <br>
        <label class="form-label" for="saturday"><input {{$this->saturday == 1 ? 'checked' : ''}} id="saturday" type="checkbox" id="saturday" value="saturday" disabled> Sabado</label>
    </div>
    <div class="form-group col-3 mb-1">
        <br>
        <label class="form-label" for="sunday"><input {{$this->sunday == 1 ? 'checked' : ''}} id="sunday" type="checkbox" id="sunday" value="sunday" disabled> Domingo</label>
    </div>
    <div class="col-md-4 col-12 mb-1">
        <label class="form-label" for="training_schedule">Horario Formación</label>
        <input wire:model.lazy="training_schedule" type="text" class="form-control" id="training_schedule" placeholder="Horario Formación" disabled>
    </div>
    <div class="col-md-4 col-12 mb-1">
        <label class="form-label" for="working_hours">Horario Laboral</label>
        <input wire:model.lazy="working_hours" type="text" class="form-control" id="working_hours" placeholder="Horario Laboral" disabled>
    </div>
    <div class="col-md-4 col-12 mb-1">
        <label class="form-label" for="complete_schedule">Horario Completo</label>
        <input wire:model.lazy="complete_schedule" type="text" class="form-control" id="complete_schedule" placeholder="Horario Completo" disabled>
    </div>
    <div class="col-md-3 col-12 mb-1">
        <label class="form-label" for="training_contract_status_id">Estado</label>
        <select wire:model.lazy="training_contract_status_id" class="form-select" id="training_contract_status_id" disabled>
            <option value="-1">Seleccione un estado</option>
            @foreach($training_contract_statuses as $training_contract_status)
                <option value="{{$training_contract_status['id']}}">{{$training_contract_status['name']}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3 col-12 mb-1">
        <label class="form-label" for="on_leave_type_id">Baja</label>
        <select wire:model.lazy="on_leave_type_id" class="form-select" id="on_leave_type_id" disabled>
            <option value="-1">Seleccione una baja</option>
            @foreach($on_leave_types as $on_leave_type)
                <option value="{{$on_leave_type['id']}}">{{$on_leave_type['name']}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4 col-12 mb-1">
        <label class="form-label" for="on_leave_date">Fecha Baja</label>
        <input wire:model.lazy="on_leave_date" type="date" class="form-control" id="on_leave_date" disabled>
    </div>
    <div class="col-md-3 col-12 mb-1">
        <label class="form-label" for="advisor_id">Asesoria</label>
        <select wire:model.lazy="advisor_id" class="form-select" id="advisor_id" disabled>
            <option value="-1">Seleccione una asesoria</option>
            @foreach($advisors as $advisor)
                <option value="{{$advisor['id']}}">{{$advisor['name']}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3 col-12 mb-1">
        <label class="form-label" for="collaborator_id">Collaborador</label>
        <select wire:model.lazy="collaborator_id" class="form-select" id="collaborator_id" disabled>
            <option value="-1">Seleccione un collaborador</option>
            @foreach($collaborators as $collaborator)
                <option value="{{$collaborator['id']}}">{{$collaborator['name']}}</option>
            @endforeach
        </select>
    </div>
</div>

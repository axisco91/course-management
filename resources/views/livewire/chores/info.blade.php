<div class="row">
    <div class="col-12 mb-1">
        <a class="btn btn-success right" href="{{url('/chores/edit/'.$this->selected_id)}}" target="_blank"><i class="fa-solid fa-pencil"></i></a>
    </div>
    <div class="col-md-3 col-12 mb-1">
        <label class="form-label" for="name">Nombre</label> @if($this->student_id)<a href="{{url('/students/view/'.$this->student_id)}}" target="_blank" class="view"><i class="fa-regular fa-eye"></i></a>
        @endif
        <input wire:model.lazy="name" type="text" class="form-control" id="name" disabled placeholder="Nombre">@error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-3 col-12 mb-1">
        <label class="form-label" for="surname">Apellidos</label>
        <input wire:model.lazy="surname" type="text" class="form-control" id="surname" disabled placeholder="Apellido">@error('surname') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6 col-12 mb-1">
        <label class="form-label" for="course_name">Curso</label> @if($this->course_id)<a href="{{url('/courses/view/'.$this->course_id)}}" target="_blank" class="view"><i class="fa-regular fa-eye"></i></a>
        @endif
        <input wire:model.lazy="course_name" type="text" class="form-control" id="course_name" disabled placeholder="Nombre Curso">@error('course_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4 col-12 mb-1">
        <label class="form-label" for="membership_tab_status">Ficha Adhesión</label>
        <select class="form-select" wire:model.lazy="membership_tab_status" id="membership_tab_status" disabled>
            <option value="0">Pendiente</option>
            <option value="1">Enviada</option>
            <option value="2">Recibida</option>
            <option value="3">No procede</option>
        </select>
    </div>
    <div class="col-md-4 col-12 mb-1">
        <label class="form-label" for="membership_tab_date">Fecha Ficha Adhesión</label>
        <input type="text" id="membership_tab_date" disabled wire:model.lazy="membership_tab_date" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD"/>
    </div>
    <div class="col-md-4 col-12 mb-1">
        <label class="form-label" for="economic_proposal_status">Propuesta Económica</label>
        <select class="form-select" wire:model.lazy="economic_proposal_status" id="economic_proposal_status" disabled>
            <option value="0">Pendiente</option>
            <option value="1">Enviada</option>
            <option value="2">Recibida</option>
        </select>
    </div>
    <div class="col-md-4 col-12 mb-1">
        <label class="form-label" for="economic_proposal_date">Fecha Propuesta Económica</label>
        <input type="text" id="economic_proposal_date" disabled wire:model.lazy="economic_proposal_date" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD" />
    </div>
    <div class="col-md-4 col-12 mb-1">
        <label class="form-label" for="student_tab_status">Ficha Alumno</label>
        <select class="form-select" wire:model.lazy="student_tab_status" id="student_tab_status" disabled>
            <option value="0">Pendiente</option>
            <option value="1">Enviada</option>
            <option value="2">Recibida</option>
        </select>
    </div>
    <div class="col-md-4 col-12 mb-1">
        <label class="form-label" for="student_tab_date">Fecha Ficha Alumno</label>
        <input type="text" id="student_tab_date" disabled wire:model.lazy="student_tab_date" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD" />
    </div>
    <div class="col-md-4 col-12 mb-1">
        <label class="form-label" for="welcome_guid_status">Guia Bienvenida</label>
        <select class="form-select" wire:model.lazy="welcome_guid_status" id="welcome_guid_status" disabled>
            <option value="0">Pendiente</option>
            <option value="1">Enviada</option>
            <option value="2">Recibida</option>
        </select>
    </div>
    <div class="col-md-4 col-12 mb-1">
        <label class="form-label" for="welcome_guid_date">Fecha Guia Bienvenida</label>
        <input type="text" id="welcome_guid_date" disabled wire:model.lazy="welcome_guid_date" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD" />
    </div>
    <div class="col-md-4 col-12 mb-1">
        <label class="form-label" for="registration_status">Matriculación</label>
        <select class="form-select" wire:model.lazy="registration_status" id="registration_status" disabled>
            <option value="0">Pendiente</option>
            <option value="2">Realizada</option>
        </select>
    </div>
    <div class="col-md-4 col-12 mb-1">
        <label class="form-label" for="registration_date">Fecha Matriculación</label>
        <input type="text" id="registration_date" disabled wire:model.lazy="registration_date" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD" />
    </div>
    <div class="col-md-4 col-12 mb-1">
        <label class="form-label" for="diploma_status">Diploma</label>
        <select class="form-select" wire:model.lazy="diploma_status" id="diploma_status" disabled>
            <option value="0">Pendiente</option>
            <option value="1">Enviada</option>
            <option value="2">No procede</option>
        </select>
    </div>
    <div class="col-md-4 col-12 mb-1">
        <label class="form-label" for="diploma_status_date">Fecha Diploma</label>
        <input type="text" id="diploma_date" disabled wire:model.lazy="diploma_status_date" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD" />
    </div>
    <div class="col-md-4 col-12 mb-1">
        <label class="form-label" for="start_communication_status">Comunicación Inicio</label>
        <select class="form-select" wire:model.lazy="start_communication_status" id="start_communication_status" disabled>
            <option value="0">Pendiente</option>
            <option value="1">Realizada</option>
            <option value="2">No procede</option>
        </select>
    </div>
    <div class="col-md-4 col-12 mb-1">
        <label class="form-label" for="start_communication_date">Fecha Comunicación Inicio</label>
        <input type="text" id="start_communication_date" disabled wire:model.lazy="start_communication_date" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD" />
    </div>
    <div class="col-md-4 col-12 mb-1">
        <label class="form-label" for="close_communication_status">Comunicación Cierre</label>
        <select class="form-select" wire:model.lazy="close_communication_status" id="close_communication_status" disabled>
            <option value="0">Pendiente</option>
            <option value="1">Realizada</option>
            <option value="2">No procede</option>
        </select>
    </div>
    <div class="col-md-4 col-12 mb-1">
        <label class="form-label" for="close_communication_date">Fecha Comunicación Cierre</label>
        <input type="text" id="close_communication_date" disabled wire:model.lazy="close_communication_date" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD" />
    </div>
    <div class="col-md-4 col-12 mb-1">
        <label class="form-label" for="invoiced_status">Facturado</label>
        <select class="form-select" wire:model.lazy="invoiced_status" id="invoiced_status" disabled>
            <option value="0">Pendiente</option>
            <option value="1">Realizada</option>
            <option value="2">No procede</option>
        </select>
    </div>
    <div class="col-md-4 col-12 mb-1">
        <label class="form-label" for="invoiced_date">Fecha Facturado</label>
        <input type="text" id="invoiced_date" disabled wire:model.lazy="invoiced_date" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD" />
    </div>
    <div class="col-md-4 col-12 mb-1">
        <label class="form-label" for="bonus_sent_status">Bonificacion Enviada</label>
        <select class="form-select" wire:model.lazy="bonus_sent_status" id="bonus_sent_status" disabled>
            <option value="0">Pendiente</option>
            <option value="1">Realizada</option>
            <option value="2">No procede</option>
        </select>
    </div>
    <div class="col-md-4 col-12 mb-1">
        <label class="form-label" for="bonus_sent_date">Fecha Bonificacion Enviada</label>
        <input type="text" id="bonus_sent_date" disabled wire:model.lazy="bonus_sent_date" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD" />
    </div>
</div>

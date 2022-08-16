<div class="card-body">
    <form class="form needs-validation" novalidate>
        <input type="hidden" wire:model.lazy="selected_id">
        <div class="row">
            <div class="col-md-3 col-12 mb-1">
                <label class="form-label" for="name">Nombre</label>
                <input wire:model.lazy="name" type="text" class="form-control" id="name" disabled placeholder="Nombre">@error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-3 col-12 mb-1">
                <label class="form-label" for="surname">Apellidos</label>
                <input wire:model.lazy="surname" type="text" class="form-control" id="surname" disabled placeholder="Apellido">@error('surname') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6 col-12 mb-1">
                <label class="form-label" for="course_name">Curso</label>
                <input wire:model.lazy="course_name" type="text" class="form-control" id="course_name" disabled placeholder="Nombre Curso">@error('course_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-3 col-12 mb-1">
                <div wire:ignore>
                    <label class="form-label" for="membership_tab_status">Ficha Adhesión</label>
                    <select class="form-select" wire:model.lazy="membership_tab_status" id="membership_tab_status">
                        <option value="0"><span>Pendiente</span></option>
                        <option value="1">Enviada</option>
                        <option value="2">Recibida</option>
                        <option value="3">No procede</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3 col-12 mb-1">
                <label class="form-label" for="membership_tab_date">Fecha Ficha Adhesión</label>
                <input type="date" id="membership_tab_date" wire:model.lazy="membership_tab_date" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD" />
            </div>
            <div class="col-md-3 col-12 mb-1">
                <label class="form-label" for="economic_proposal_status">Propuesta Económica</label>
                <select class="form-select" wire:model.lazy="economic_proposal_status" id="economic_proposal_status">
                    <option value="0"><span class="badge rounded-pill badge-light-warning">Pendiente</span></option>
                    <option value="1">Enviada</option>
                    <option value="2">Recibida</option>
                </select>
            </div>
            <div class="col-md-3 col-12 mb-1">
                <label class="form-label" for="economic_proposal_date">Fecha Propuesta Económica</label>
                <input type="date" id="economic_proposal_date" wire:model.lazy="economic_proposal_date" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD" />
            </div>
            <div class="col-md-3 col-12 mb-1">
                <label class="form-label" for="student_tab_status">Ficha Alumno</label>
                <select class="form-select" wire:model.lazy="student_tab_status" id="student_tab_status">
                    <option value="0">Pendiente</option>
                    <option value="1">Enviada</option>
                    <option value="2">Recibida</option>
                </select>
            </div>
            <div class="col-md-3 col-12 mb-1">
                <label class="form-label" for="student_tab_date">Fecha Ficha Alumno</label>
                <input type="date" id="student_tab_date" wire:model.lazy="student_tab_date" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD" />
            </div>
            <div class="col-md-3 col-12 mb-1">
                <label class="form-label" for="welcome_guid_status">Guia Bienvenida</label>
                <select class="form-select" wire:model.lazy="welcome_guid_status" id="welcome_guid_status">
                    <option value="0">Pendiente</option>
                    <option value="1">Enviada</option>+
                </select>
            </div>
            <div class="col-md-3 col-12 mb-1">
                <label class="form-label" for="welcome_guid_date">Fecha Guia Bienvenida</label>
                <input type="date" id="welcome_guid_date" wire:model.lazy="welcome_guid_date" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD" />
            </div>
            <div class="col-md-3 col-12 mb-1">
                <label class="form-label" for="registration_status">Matriculación</label>
                <select class="form-select" wire:model.lazy="registration_status" id="registration_status">
                    <option value="0">Pendiente</option>
                    <option value="2">Realizada</option>
                </select>
            </div>
            <div class="col-md-3 col-12 mb-1">
                <label class="form-label" for="registration_date">Fecha Matriculación</label>
                <input type="date" id="registration_date" wire:model.lazy="registration_date" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD" />
            </div>
            <div class="col-md-3 col-12 mb-1">
                <label class="form-label" for="diploma_status">Diploma</label>
                <select class="form-select" wire:model.lazy="diploma_status" id="diploma_status">
                    <option value="0">Pendiente</option>
                    <option value="1">Enviada</option>
                    <option value="2">No procede</option>
                </select>
            </div>
            <div class="col-md-3 col-12 mb-1">
                <label class="form-label" for="diploma_status_date">Fecha Diploma</label>
                <input type="date" id="diploma_date" wire:model.lazy="diploma_status_date" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD" />
            </div>
            <div class="col-md-3 col-12 mb-1">
                <label class="form-label" for="start_communication_status">Comunicación Inicio</label>
                <select class="form-select" wire:model.lazy="start_communication_status" id="start_communication_status">
                    <option value="0">Pendiente</option>
                    <option value="1">Realizada</option>
                    <option value="2">No procede</option>
                </select>
            </div>
            <div class="col-md-3 col-12 mb-1">
                <label class="form-label" for="start_communication_date">Fecha Comunicación Inicio</label>
                <input type="date" id="start_communication_date" wire:model.lazy="start_communication_date" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD" />
            </div>
            <div class="col-md-3 col-12 mb-1">
                <label class="form-label" for="close_communication_status">Comunicación Cierre</label>
                <select class="form-select" wire:model.lazy="close_communication_status" id="close_communication_status">
                    <option value="0">Pendiente</option>
                    <option value="1">Realizada</option>
                    <option value="2">No procede</option>
                </select>
            </div>
            <div class="col-md-3 col-12 mb-1">
                <label class="form-label" for="close_communication_date">Fecha Comunicación Cierre</label>
                <input type="date" id="close_communication_date" wire:model.lazy="close_communication_date" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD" />
            </div>
            <div class="col-md-3 col-12 mb-1">
                <label class="form-label" for="invoiced_status">Facturado</label>
                <select class="form-select" wire:model.lazy="invoiced_status" id="invoiced_status">
                    <option value="0">Pendiente</option>
                    <option value="1">Realizada</option>
                    <option value="2">No procede</option>
                </select>
            </div>
            <div class="col-md-3 col-12 mb-1">
                <label class="form-label" for="invoiced_status">Fecha Facturado</label>
                <input type="date" id="invoiced_date" wire:model.lazy="invoiced_date" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD" />
            </div>
            <div class="col-md-3 col-12 mb-1">
                <label class="form-label" for="bonus_sent_status">Bonificacion Enviada</label>
                <select class="form-select" wire:model.lazy="bonus_sent_status" id="bonus_sent_status">
                    <option value="0">Pendiente</option>
                    <option value="1">Realizada</option>
                    <option value="2">No procede</option>
                </select>
            </div>
            <div class="col-md-3 col-12 mb-1">
                <label class="form-label" for="bonus_sent_date">Fecha Bonificacion Enviada</label>
                <input type="date" id="bonus_sent_date" wire:model.lazy="bonus_sent_date" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD" />
            </div>
        </div>
        <div class="col-12 mb-1">
            <a href="{{url('/chores')}}" class="btn btn-secondary">Volver</a>
            <button type="button" wire:click.prevent="update()" class="btn btn-primary">Guardar</button>
        </div>
    </form>
</div>

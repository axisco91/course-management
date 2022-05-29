<div wire:ignore.self class="modal fade" id="updateChore" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-edit-user">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button" wire:click.prevent="cancel()" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pb-5 px-sm-5 pt-50">
                <div class="text-center mb-2">
                    <h1 class="mb-1">Editar Tarea</h1>
                </div>
                <form id="editChoreForm" class="row gy-1 pt-75" onsubmit="return false">
                    <input type="hidden" wire:model="selected_id">
                    <div class="row">
                        <div class="col-md-4 col-12 mb-1">
                            <label class="form-label" for="membership_tab_status">Ficha Adhesión</label>
                            <select class="form-select" wire:model.lazy="membership_tab_status" id="membership_tab_status">
                                <option value="0">Pendiente</option>
                                <option value="1">Enviada</option>
                                <option value="2">Recibida</option>
                                <option value="3">No procede</option>
                            </select>
                        </div>
                        <div class="col-md-4 col-12 mb-1">
                            <label class="form-label" for="membership_tab_date">Fecha Ficha Adhesión</label>
                            <input type="text" id="membership_tab_date" disabled wire:model.lazy="membership_tab_date" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD" />
                        </div>
                        <div class="col-md-4 col-12 mb-1">
                            <label class="form-label" for="economic_proposal_status">Propuesta Económica</label>
                            <select class="form-select" wire:model.lazy="economic_proposal_status" id="economic_proposal_status">
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
                            <select class="form-select" wire:model.lazy="student_tab_status" id="student_tab_status">
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
                            <select class="form-select" wire:model.lazy="welcome_guid_status" id="welcome_guid_status">
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
                            <select class="form-select" wire:model.lazy="registration_status" id="registration_status">
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
                            <select class="form-select" wire:model.lazy="diploma_status" id="diploma_status">
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
                            <select class="form-select" wire:model.lazy="start_communication_status" id="start_communication_status">
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
                            <select class="form-select" wire:model.lazy="close_communication_status" id="close_communication_status">
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
                            <select class="form-select" wire:model.lazy="invoiced_status" id="invoiced_status">
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
                            <select class="form-select" wire:model.lazy="bonus_sent_status" id="bonus_sent_status">
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
                    <div class="col-12 text-center mt-2 pt-50">
                        <button type="button" class="btn btn-secondary close-btn" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" wire:click.prevent="update()" class="btn btn-primary" data-bs-dismiss="modal">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

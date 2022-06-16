<div wire:ignore.self class="modal fade" id="updateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-edit-user">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button" wire:click.prevent="cancel()" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pb-5 px-sm-5 pt-50">
                <div class="text-center mb-2">
                    <h1 class="mb-1">Editar Seguimiento</h1>
                </div>
                <form id="editTracingForm" class="row gy-1 pt-75" onsubmit="return false">
                    <input type="hidden" wire:model="selected_id">
                    <div class="row">
                        <div class="col-md-4 col-12 mb-1">
                            <label class="form-label" for="name">Nombre</label>
                            <input wire:model.lazy="name" type="text" class="form-control" id="name" disabled placeholder="Nombre">@error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 col-12 mb-1">
                            <label class="form-label" for="surname">Apellidos</label>
                            <input wire:model.lazy="surname" type="text" class="form-control" id="surname" disabled placeholder="Apellido">@error('surname') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 col-12 mb-1">
                            <label class="form-label" for="course_name">Curso</label>
                            <input wire:model.lazy="course_name" type="text" class="form-control" id="course_name" disabled placeholder="Nombre Curso">@error('course_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-2 col-12 mb-1">
                            <div wire:ignore>
                                <label class="form-label" for="performed_hours">Horas Realizadas</label>
                                <input wire:model.lazy="performed_hours" type="number" class="form-control" id="performed_hours" placeholder="Horas Realizadas">
                            </div>
                        </div>
                        <div class="col-md-2 col-12 mb-1">
                            <label class="form-label" for="total_hours">Horas Totales</label>
                            <input wire:model.lazy="total_hours" type="number" class="form-control" id="total_hours" placeholder="Horas Totales" disabled>
                        </div>
                        <div class="col-md-2 col-12 mb-1">
                            <label class="form-label" for="performed_activities">Actividades Realizadas</label>
                            <input wire:model.lazy="performed_activities" type="number" class="form-control" id="performed_activities" placeholder="Actividades Realizadas">
                        </div>
                        <div class="col-md-2 col-12 mb-1">
                            <label class="form-label" for="number_activities">Actividades Totales</label>
                            <input wire:model.lazy="number_activities" type="number" class="form-control" id="number_activities" placeholder="Actividades Totales" disabled>
                        </div>
                        <div class="col-md-2 col-12 mb-1">
                            <div wire:ignore>
                                <label class="form-label" for="performed_units">Unidades Realizadas</label>
                                <input wire:model.lazy="performed_units" type="number" class="form-control" id="performed_units" placeholder="Unidades Realizadas">
                            </div>
                        </div>
                        <div class="col-md-2 col-12 mb-1">
                            <label class="form-label" for="number_units">Unidades Totales</label>
                            <input wire:model.lazy="number_units" type="number" class="form-control" id="number_units" placeholder="Unidades Totales" disabled>
                        </div>
                        <div class="col-md-4 col-12 mb-1">
                            <div wire:ignore>
                                <label class="form-label" for="welcome_message">Bienvenida</label>
                                <select class="form-select" wire:model.lazy="welcome_message" id="welcome_message">
                                    <option value="0">No</option>
                                    <option value="1">Si</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-12 mb-1">
                            <label class="form-label" for="welcome_date_sent">Fecha de bienvenida enviado</label>
                            <input wire:model.lazy="welcome_date_sent" type="date" class="form-control" id="welcome_date_sent">@error('welcome_date_sent') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 col-12 mb-1">
                            <label class="form-label" for="welcome_date">Fecha de bienvenida</label>
                            <input wire:model.lazy="welcome_date" type="date" class="form-control" id="welcome_date" disabled>@error('welcome_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 col-12 mb-1">
                            <div wire:ignore>
                                <label class="form-label" for="quarter_message">Mensaje 25%</label>
                                <select class="form-select" wire:model.lazy="quarter_message" id="quarter_message">
                                    <option value="0">No</option>
                                    <option value="1">Si</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-12 mb-1">
                            <label class="form-label" for="quarter_date_sent">Fecha 25% enviado</label>
                            <input wire:model.lazy="quarter_date_sent" type="date" class="form-control" id="quarter_date_sent">@error('quarter_date_sent') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 col-12 mb-1">
                            <label class="form-label" for="quarter_date">Fecha 25%</label>
                            <input wire:model.lazy="quarter_date" type="date" class="form-control" id="quarter_date" disabled>@error('quarter_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 col-12 mb-1">
                            <div wire:ignore>
                                <label class="form-label" for="half_message">Mensaje 50%</label>
                                <select class="form-select" wire:model.lazy="half_message" id="half_message">
                                    <option value="0">No</option>
                                    <option value="1">Si</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-12 mb-1">
                            <label class="form-label" for="half_date_sent">Fecha 50% envado</label>
                            <input wire:model.lazy="half_date_sent" type="date" class="form-control" id="half_date_sent">@error('half_date_sent') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 col-12 mb-1">
                            <label class="form-label" for="half_date">Fecha 50%</label>
                            <input wire:model.lazy="half_date" type="date" class="form-control" id="half_date" disabled>@error('half_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 col-12 mb-1">
                            <div wire:ignore>
                                <label class="form-label" for="three_quarters_message">Mensaje 75%</label>
                                <select class="form-select" wire:model.lazy="three_quarters_message" id="three_quarters_message">
                                    <option value="0">No</option>
                                    <option value="1">Si</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-12 mb-1">
                            <label class="form-label" for="three_quarters_date_sent">Fecha 75% enviado</label>
                            <input wire:model.lazy="three_quarters_date_sent" type="date" class="form-control" id="three_quarters_date_sent">@error('three_quarters_date_sent') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 col-12 mb-1">
                            <label class="form-label" for="three_quarters_date">Fecha 75%</label>
                            <input wire:model.lazy="three_quarters_date" type="date" class="form-control" id="three_quarters_date" disabled>@error('three_quarters_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 col-12 mb-1">
                            <div wire:ignore>
                                <label class="form-label" for="final_message">Finalizacion</label>
                                <select class="form-select" wire:model.lazy="final_message" id="final_message">
                                    <option value="0">No</option>
                                    <option value="1">Si</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-12 mb-1">
                            <label class="form-label" for="final_date_sent">Fecha Final enviado</label>
                            <input wire:model.lazy="final_date_sent" type="date" class="form-control" id="final_date_sent">@error('final_date_sent') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 col-12 mb-1">
                            <label class="form-label" for="final_date">Fecha Final</label>
                            <input wire:model.lazy="final_date" type="date" class="form-control" id="final_date" disabled>@error('final_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 col-12">
                            <label class="form-label" for="follow_up_date">Fecha Seguimiento</label>
                            <input wire:model.lazy="follow_up_date" type="date" class="form-control" id="follow_up_date">@error('follow_up_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 col-12 mb-1">
                            <div wire:ignore>
                                <label class="form-label" for="final_test">Test Final</label>
                                <select class="form-select" wire:model.lazy="final_test" id="final_test">
                                    <option value="0">Pendiente</option>
                                    <option value="1">Realizado</option>
                                    <option value="2">No realizado</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-12 mb-1">
                            <div wire:ignore>
                                <label class="form-label" for="questionnaire">Cuestionario</label>
                                <select class="form-select" wire:model.lazy="questionnaire" id="questionnaire">
                                    <option value="0">Pendiente</option>
                                    <option value="1">Realizado</option>
                                    <option value="2">No realizado</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="course_observation"></label>
                            <textarea wire:model.lazy="course_observation" class="form-control" id="course_observation" placeholder="Observaciones"></textarea>
                            @error('course_observation') <div class="invalid-feedback">{{ $message }}</div> @enderror
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

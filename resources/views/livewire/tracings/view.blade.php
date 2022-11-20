<div wire:ignore.self class="modal fade" id="updateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
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
                            <label class="form-label" for="performed_activities">Actividades Realizadas</label>
                            <input wire:model.lazy="performed_activities" type="number" class="form-control" id="performed_activities" placeholder="Actividades Realizadas">
                        </div>
                        <div class="col-md-4 col-12 mb-1">
                            <div wire:ignore>
                                <label class="form-label" for="performed_hours">Horas Realizadas</label>
                                <input wire:model.lazy="performed_hours" type="number" class="form-control" id="performed_hours" placeholder="Horas Realizadas">
                            </div>
                        </div>
                        <div class="col-md-4 col-12 mb-1">
                            <div wire:ignore>
                                <label class="form-label" for="performed_units">Unidades Realizadas</label>
                                <input wire:model.lazy="performed_units" type="number" class="form-control" id="performed_units" placeholder="Unidades Realizadas">
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            <label class="form-label" for="follow_up_date">Fecha Seguimiento</label>
                            <input wire:model.lazy="follow_up_date" type="date" class="form-control" id="follow_up_date">@error('follow_up_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 col-12 mb-1">
                            <div wire:ignore>
                                <label class="form-label" for="final_test">Test Final</label>
                                <select class="form-select" wire:model.lazy="final_test" id="final_test">
                                    <option value="0">No realizado</option>
                                    <option value="1">Realizado</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-12 mb-1">
                            <div wire:ignore>
                                <label class="form-label" for="questionnaire">Cuestionario</label>
                                <select class="form-select" wire:model.lazy="questionnaire" id="questionnaire">
                                    <option value="0">No realizado</option>
                                    <option value="1">Realizado</option>
                                </select>
                            </div>
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
                            <div wire:ignore>
                                <label class="form-label" for="quarter_message">Mensaje 25%</label>
                                <select class="form-select" wire:model.lazy="quarter_message" id="quarter_message">
                                    <option value="0">No</option>
                                    <option value="1">Si</option>
                                </select>
                            </div>
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
                            <div wire:ignore>
                                <label class="form-label" for="three_quarters_message">Mensaje 75%</label>
                                <select class="form-select" wire:model.lazy="three_quarters_message" id="three_quarters_message">
                                    <option value="0">No</option>
                                    <option value="1">Si</option>
                                </select>
                            </div>
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

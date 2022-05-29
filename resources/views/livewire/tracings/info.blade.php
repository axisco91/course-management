<!-- Modal -->
<div wire:ignore.self class="modal fade" id="tracingsTabModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-student-tab">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button" wire:click.prevent="cancel()" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pb-5 px-sm-5 pt-50">
                <div class="text-center mb-2">
                    <h1 class="mb-1">{{$this-> course_name}} - {{$this-> student_name}}</h1>
                </div>
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#general">General</a>
                    </li>
                    <li class="nav nav-tabs">
                        <a class="nav-link" data-bs-toggle="tab" href="#"></a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane container active" id="general">
                        <div class="row">
                            <div class="col-md-4 col-12 mb-1">
                                <label class="form-label" for="performed_activities">Actividades Realizadas</label>
                                <input wire:model.lazy="performed_activities" type="number" class="form-control" id="performed_activities" placeholder="Actividades Realizadas" disabled>
                            </div>
                            <div class="col-md-4 col-12 mb-1">
                                <div wire:ignore>
                                    <label class="form-label" for="performed_hours">Horas Realizadas</label>
                                    <input wire:model.lazy="performed_hours" type="number" class="form-control" id="performed_hours" placeholder="Horas Realizadas" disabled>
                                </div>
                            </div>
                            <div class="col-md-4 col-12 mb-1">
                                <div wire:ignore>
                                    <label class="form-label" for="performed_units">Unidades Realizadas</label>
                                    <input wire:model.lazy="performed_units" type="number" class="form-control" id="performed_units" placeholder="Unidades Realizadas" disabled>
                                </div>
                            </div>
                            <div class="col-md-4 col-12">
                                <label class="form-label" for="follow_up_date">Fecha Seguimiento</label>
                                <input wire:model.lazy="follow_up_date" type="date" class="form-control" id="follow_up_date" disabled>@error('follow_up_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4 col-12 mb-1">
                                <div wire:ignore>
                                    <label class="form-label" for="final_test">Test Final</label>
                                    <select class="form-select" wire:model.lazy="final_test" id="final_test" disabled>
                                        <option value="0">No realizado</option>
                                        <option value="1">Realizado</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 col-12 mb-1">
                                <div wire:ignore>
                                    <label class="form-label" for="questionnaire">Cuestionario</label>
                                    <select class="form-select" wire:model.lazy="questionnaire" id="questionnaire" disabled>
                                        <option value="0">No realizado</option>
                                        <option value="1">Realizado</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 col-12 mb-1">
                                <div wire:ignore>
                                    <label class="form-label" for="welcome_message">Bienvenida</label>
                                    <select class="form-select" wire:model.lazy="welcome_message" id="welcome_message" disabled>
                                        <option value="0">No</option>
                                        <option value="1">Si</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 col-12 mb-1">
                                <div wire:ignore>
                                    <label class="form-label" for="quarter_message">Mensaje 25%</label>
                                    <select class="form-select" wire:model.lazy="quarter_message" id="quarter_message" disabled>
                                        <option value="0">No</option>
                                        <option value="1">Si</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 col-12 mb-1">
                                <div wire:ignore>
                                    <label class="form-label" for="half_message">Mensaje 50%</label>
                                    <select class="form-select" wire:model.lazy="half_message" id="half_message" disabled>
                                        <option value="0">No</option>
                                        <option value="1">Si</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 col-12 mb-1">
                                <div wire:ignore>
                                    <label class="form-label" for="three_quarters_message">Mensaje 75%</label>
                                    <select class="form-select" wire:model.lazy="three_quarters_message" id="three_quarters_message" disabled>
                                        <option value="0">No</option>
                                        <option value="1">Si</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 col-12 mb-1">
                                <div wire:ignore>
                                    <label class="form-label" for="final_message">Finalizacion</label>
                                    <select class="form-select" wire:model.lazy="final_message" id="final_message" disabled>
                                        <option value="0">No</option>
                                        <option value="1">Si</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="course_observation"></label>
                                <textarea wire:model.lazy="course_observation" class="form-control" id="course_observation" placeholder="Observaciones" disabled></textarea>
                                @error('course_observation') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

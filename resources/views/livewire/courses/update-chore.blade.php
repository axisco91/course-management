<!-- Modal -->
<div wire:ignore.self class="modal fade" id="updateChore" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-chore-tab">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="editChore({{$this->company_name}})"></button>
            </div>
            <div class="modal-body pb-5 px-sm-5 pt-50">
                <div class="text-center mb-2">
                    <h1 class="mb-1">Tarea de {{$this->student_name}}</h1>
                </div>
                <form class="row gy-1 pt-75 form">
                    <div class="col-md-4 col-12 mb-1">
                        <div wire:ignore>
                            <label class="form-label" for="membership_tab_status">Ficha Adhesión</label>
                            <select class="form-select @error('membership_tab_status') is-invalid @enderror" wire:model.lazy="membership_tab_status" id="membership_tab_status">
                                <option value="0">No recibida</option>
                                <option value="1">Enviada</option>
                                <option value="2">Recibida</option>
                                <option value="3">No procede</option>
                            </select>
                        </div>
                        @error('company_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4 col-12 mb-1">
                        <label class="form-label" for="membership_tab_date">Ficha Adhesión</label>
                        <input type="text" id="membership_tab_date" wire:model.lazy="membership_tab_date" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD" />
                    </div>
                    <div class="col-md-4 col-12 mb-1">
                        <div wire:ignore>
                            <label class="form-label" for="economic_proposal_status">Propuesta Económica</label>
                            <select class="form-select @error('economic_proposal_status') is-invalid @enderror" wire:model.lazy="economic_proposal_status" id="economic_proposal_status">
                                <option value="0">No recibida</option>
                                <option value="1">Enviada</option>
                                <option value="2">Recibida</option>
                                <option value="3">No procede</option>
                            </select>
                        </div>
                        @error('economic_proposal_status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4 col-12 mb-1">
                        <label class="form-label" for="membership_tab_date">Ficha Adhesión</label>
                        <input type="text" id="membership_tab_date" wire:model.lazy="membership_tab_date" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD" />
                    </div>
                    <div class="col-md-4 col-12 mb-1">
                        <div wire:ignore>
                            <label class="form-label" for="economic_proposal_status">Propuesta Económica</label>
                            <select class="form-select @error('economic_proposal_status') is-invalid @enderror" wire:model.lazy="economic_proposal_status" id="economic_proposal_status">
                                <option value="0">No recibida</option>
                                <option value="1">Enviada</option>
                                <option value="2">Recibida</option>
                                <option value="3">No procede</option>
                            </select>
                        </div>
                        @error('economic_proposal_status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4 col-12 mb-1">
                        <label class="form-label" for="membership_tab_date">Ficha Adhesión</label>
                        <input type="text" id="membership_tab_date" wire:model.lazy="membership_tab_date" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD" />
                    </div>
                    <div class="col-md-4 col-12 mb-1">
                        <div wire:ignore>
                            <label class="form-label" for="economic_proposal_status">Propuesta Económica</label>
                            <select class="form-select @error('economic_proposal_status') is-invalid @enderror" wire:model.lazy="economic_proposal_status" id="economic_proposal_status">
                                <option value="0">No recibida</option>
                                <option value="1">Enviada</option>
                                <option value="2">Recibida</option>
                                <option value="3">No procede</option>
                            </select>
                        </div>
                        @error('economic_proposal_status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4 col-12 mb-1">
                        <label class="form-label" for="membership_tab_date">Ficha Adhesión</label>
                        <input type="text" id="membership_tab_date" wire:model.lazy="membership_tab_date" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD" />
                    </div>
                    <div class="col-md-4 col-12 mb-1">
                        <div wire:ignore>
                            <label class="form-label" for="economic_proposal_status">Propuesta Económica</label>
                            <select class="form-select @error('economic_proposal_status') is-invalid @enderror" wire:model.lazy="economic_proposal_status" id="economic_proposal_status">
                                <option value="0">No recibida</option>
                                <option value="1">Enviada</option>
                                <option value="2">Recibida</option>
                                <option value="3">No procede</option>
                            </select>
                        </div>
                        @error('economic_proposal_status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4 col-12 mb-1">
                        <label class="form-label" for="membership_tab_date">Ficha Adhesión</label>
                        <input type="text" id="membership_tab_date" wire:model.lazy="membership_tab_date" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

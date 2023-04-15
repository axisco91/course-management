<div wire:ignore.self class="modal fade" id="updateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button" wire:click.prevent="cancel()" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pb-5 px-sm-5 pt-50">
                <div class="text-center mb-2">
                    <h1 class="mb-1">Editar Credito</h1>
                </div>
                <form id="updateCreditForm" class="row gy-1 pt-75" onsubmit="return false">
                    <input wire:model="company_id" type="text" hidden class="form-control" id="company_id">
                    <div class="col-12 col-md-4">
                        <label class="form-label" for="available_credit">Credito Disponible</label>
                        <input wire:model.lazy="available_credit" type="number" class="form-control @error('available_credit') is-invalid @enderror" id="available_credit" placeholder="Credito Disponible">
                        @error('available_credit') <div class="invalid-feedback">Credito disponible requerido</div> @enderror
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label" for="consumed_credit">Credito Consumido</label>
                        <input wire:model.lazy="consumed_credit" type="number" class="form-control @error('consumed_credit') is-invalid @enderror" id="consumed_credit" placeholder="Credito Consumido">
                        @error('consumed_credit') <div class="invalid-feedback">Credito consumido es requerido</div> @enderror
                    </div>
                    <div class="col-md-4 col-12 mb-1">
                        <div wire:ignore>
                            <label class="form-label" for="year">Año</label>
                            <select class="form-select" wire:model.lazy="year" id="year">
                                <option value="2022">2022</option>
                                <option value="2021">2021</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-12 text-center mt-2 pt-50">
                <button type="button" class="btn btn-secondary close-btn" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" wire:click.prevent="createCredit()" class="btn btn-primary close-model">Guardar</button>
            </div>
        </div>
    </div>
</div>

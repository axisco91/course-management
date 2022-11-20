<div wire:ignore.self class="modal fade" id="updateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button" wire:click.prevent="cancel()" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pb-5 px-sm-5 pt-50">
                <div class="text-center mb-2">
                    <h1 class="mb-1">Editar Dia Excluido</h1>
                </div>
                <form id="editCnaeForm" class="row gy-1 pt-75" onsubmit="return false">
                    <input type="hidden" wire:model="selected_id">
                    <div class="col-12">
                        <label class="form-label" for="day">Dia Excluido</label>
                        <input wire:model="day" type="date" class="form-control @error('day') is-invalid @enderror" id="name" placeholder="Dia Excluido">@error('day') <div class="invalid-feedback">Dia excluido es requerido</div> @enderror
                    </div>
                    <div class="col-12 text-center mt-2 pt-50">
                        <button type="button" wire:click.prevent="cancel()" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" wire:click.prevent="update()" class="btn btn-primary close-model">Guardar</button>
                    </div>
                </form>
            </div>
       </div>
    </div>
</div>

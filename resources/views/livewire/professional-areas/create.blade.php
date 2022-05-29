<div wire:ignore.self class="modal fade" id="createDataModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button" wire:click.prevent="cancel()" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pb-5 px-sm-5 pt-50">
                <div class="text-center mb-2">
                    <h1 class="mb-1">Crear Área profesional</h1>
                </div>
                <form id="createAreaForm" class="row gy-1 pt-75" onsubmit="return false">
                    <div class="row">
                        <div class="col-12">
                            <label class="form-label" for="name">Área</label>
                            <input wire:model="name" type="text" class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Área">@error('name') <div class="invalid-feedback">Categoría es requerido</div> @enderror
                        </div>
                    </div>
                    <div class="col-12 text-center mt-2 pt-50">
                        <button type="button" class="btn btn-secondary close-btn" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" wire:click.prevent="store()" class="btn btn-primary close-modal">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

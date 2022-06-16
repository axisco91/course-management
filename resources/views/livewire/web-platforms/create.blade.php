<div wire:ignore.self class="modal fade" id="createDataModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button" wire:click.prevent="cancel()" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pb-5 px-sm-5 pt-50">
                <div class="text-center mb-2">
                    <h1 class="mb-1">Crear Plataforma</h1>
                </div>
                <form id="createPlatformForm" class="row gy-1 pt-75" onsubmit="return false">
                    <input type="hidden" wire:model="selected_id">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="name">Nombre</label>
                            <input wire:model="name" type="text" class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Name">@error('name') <div class="invalid-feedback">Nombre es requerido</div> @enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="url">URL</label>
                            <input wire:model="url" type="text" class="form-control @error('url') is-invalid @enderror" id="url" placeholder="Url">@error('url') <div class="invalid-feedback">URL es requerido</div> @enderror
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

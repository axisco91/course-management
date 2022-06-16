<div wire:ignore.self class="modal fade" id="updateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button" wire:click.prevent="cancel()" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pb-5 px-sm-5 pt-50">
                <div class="text-center mb-2">
                    <h1 class="mb-1">Editar Centro</h1>
                </div>
                <form id="editCenterForm" class="row gy-1 pt-75" onsubmit="return false">
					<input type="hidden" wire:model="selected_id">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="name">Nombre</label>
                            <input wire:model="name" type="text" class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Nombre">@error('name') <div class="invalid-feedback">Nombre es requerido</div> @enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="address">Dirección</label>
                            <input wire:model="address" type="text" class="form-control @error('address') is-invalid @enderror" id="address" placeholder="Dirección">@error('address') <div class="invalid-feedback">Dirección es requerido</div> @enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="email">Correo</label>
                            <input wire:model="email" type="email" class="form-control @error('email') is-invalid @enderror" id="email" placeholder="Correo">@error('email') <div class="invalid-feedback">Correo es requerido</div> @enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="telephone">Telefono</label>
                            <input wire:model="telephone" type="text" class="form-control @error('telephone') is-invalid @enderror" id="telephone" placeholder="Telefono">@error('telephone') <div class="invalid-feedback">Telefono es requerido</div> @enderror
                        </div>
                    </div>
                    <div class="col-12 text-center mt-2 pt-50">
                        <button type="button" wire:click.prevent="cancel()" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" wire:click.prevent="update()" class="btn btn-primary" data-bs-dismiss="modal">Guardar</button>
                    </div>
                </form>
            </div>
       </div>
    </div>
</div>

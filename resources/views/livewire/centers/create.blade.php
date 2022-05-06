<!-- Modal -->
<div wire:ignore.self class="modal fade" id="createDataModal" data-bs-backdrop="static" role="dialog" aria-labelledby="createDataModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createDataModalLabel">Crear Centro</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
           <div class="modal-body">
				<form>
                    <div class="row">
                        <div class="form-group col-6">
                            <label for="name">Nombre</label>
                            <input wire:model="name" type="text" class="form-control" id="name" placeholder="Nombre">@error('name') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-6">
                            <label for="address">Dirección</label>
                            <input wire:model="address" type="text" class="form-control" id="address" placeholder="Dirección">@error('address') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-6">
                            <label for="email">Correo</label>
                            <input wire:model="email" type="email" class="form-control" id="email" placeholder="Correo">@error('email') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-6">
                            <label for="telephone">Telefono</label>
                            <input wire:model="telephone" type="text" class="form-control" id="telephone" placeholder="Telefono">@error('telephone') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-btn" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" wire:click.prevent="store()" class="btn btn-primary close-modal">Gaurdar</button>
            </div>
        </div>
    </div>
</div>

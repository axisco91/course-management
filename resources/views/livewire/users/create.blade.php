<!-- Modal -->
<div wire:ignore.self class="modal fade" id="createDataModal" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="createDataModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createDataModalLabel">Crear Usuario</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
           <div class="modal-body">
				<form>
                    <div class="row">
                       <div class="form-group col-4">
                            <label for="name"></label>
                            <input wire:model="name" type="text" class="form-control" id="name" placeholder="Nombre">@error('name') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                       <div class="form-group col-4">
                            <label for="surname"></label>
                            <input wire:model="surname" type="text" class="form-control" id="surname" placeholder="Apellidos">@error('surname') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                       <div class="form-group col-4">
                            <label for="username"></label>
                            <input wire:model="username" type="text" class="form-control" id="username" placeholder="Usuario">@error('username') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                       <div class="form-group col-4">
                            <label for="email"></label>
                            <input wire:model="email" type="text" class="form-control" id="email" placeholder="Correo">@error('email') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-btn" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" wire:click.prevent="store()" class="btn btn-primary close-modal">Guardar</button>
            </div>
        </div>
    </div>
</div>

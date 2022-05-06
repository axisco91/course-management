<div wire:ignore.self>
    <div class="modal-body">
        <form>
            <div class="row">
                <div class="form-group col-4">
                    <label for="name"></label>
                    <input wire:model.ignore="name" type="text" class="form-control" id="name" placeholder="Nombre">@error('name') <span class="error text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
        </form>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary close-btn" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" wire:click.prevent="store()" class="btn btn-primary close-modal">Guardar</button>
    </div>
</div>

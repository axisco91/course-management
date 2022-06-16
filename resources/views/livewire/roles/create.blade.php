<div wire:ignore.self>
    <div class="modal-body">
        <form>
            <div class="row">
               <div class="col-md-4 col-12">
                    <label class="form-label" for="name"></label>
                    <input wire:model.ignore="name" type="text" class="form-control" id="name" placeholder="Nombre">@error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </form>
    </div>
      <div class="col-12 text-center mt-2 pt-50">
        <button type="button" class="btn btn-secondary close-btn" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" wire:click.prevent="store()" class="btn btn-primary close-modal">Guardar</button>
    </div>
</div>

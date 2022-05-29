<div wire:ignore.self>
    <div class="modal-body">
        <form>
            <input type="hidden" wire:model="selected_id">
            <div class="row">
                @foreach($permissions as $permission)
                        <label class="form-label">
                            <input wire:model="selected" type="checkbox" value="{{$permission['id']}}"> {{$permission['name']}}
                        </label>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                @endforeach
            </div>
        </form>
    </div>
      <div class="col-12 text-center mt-2 pt-50">
        <button type="button" class="btn btn-secondary close-btn" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" wire:click.prevent="store()" class="btn btn-primary" data-bs-dismiss="modal">Guardar</button>
    </div>
</div>

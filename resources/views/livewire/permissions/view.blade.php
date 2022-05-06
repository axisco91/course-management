<div wire:ignore.self>
    <div class="modal-body">
        <form>
            <input type="hidden" wire:model="selected_id">
            <div class="row">
                @foreach($permissions as $permission)
                        <label>
                            <input wire:model="selected" type="checkbox" value="{{$permission['id']}}"> {{$permission['name']}}
                        </label>
                        @error('name') <span class="error text-danger">{{ $message }}</span> @enderror
                @endforeach
            </div>
        </form>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary close-btn" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" wire:click.prevent="store()" class="btn btn-primary" data-bs-dismiss="modal">Guardar</button>
    </div>
</div>

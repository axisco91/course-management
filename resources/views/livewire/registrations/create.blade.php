<div wire:ignore.self class="modal fade" id="createDataModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button" wire:click.prevent="cancel()" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pb-5 px-sm-5 pt-50">
                <div class="text-center mb-2">
                    <h1 class="mb-1">{{$this->name}} {{$this->surname}}</h1>
                </div>
                <form id="createRegisterForm" class="row gy-1 pt-75" onsubmit="return false">
                    <input type="hidden" wire:model="student_id">
                    <div class="row">
                        <div class="col-md-6 col-12">
                            <div class="mb-1">
                                <label class="form-label" for="price">Precio</label>
                                <input wire:model.lazy="price" type="text" class="form-control" id="price" placeholder="Precio">@error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-6 col-12 mb-1">
                            <label class="form-label" for="is_bonus">Bonificado</label>
                            <select wire:model.lazy="is_bonus" class="form-control" id="is_bonus">
                                <option value="0">No</option>
                                <option value="1">Si</option>
                            </select>
                            @error('company_bonus') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="col-12 text-center mt-2 pt-50">
                        <button type="button" class="btn btn-secondary close-btn" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" wire:click.prevent="register()" class="btn btn-primary close-modal" data-bs-dismiss="modal">Guardar</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

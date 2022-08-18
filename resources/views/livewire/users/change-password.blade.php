<div wire:ignore.self class="modal fade" id="passwordModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button" wire:click.prevent="cancel()" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pb-5 px-sm-5 pt-50">
                <div class="text-center mb-2">
                    <h1 class="mb-1">Editar Contraseña</h1>
                </div>
                <form id="editTypeForm" class="row gy-1 pt-75" onsubmit="return false">
                    <input type="hidden" wire:model="selected_id">
                    <div class="row">
                        <div class="col-12">
                            <label class="form-label" for="name">Contraseña</label>
                            <input wire:model="password" type="password" class="form-control @error('password') is-invalid @enderror" id="password" placeholder="Contraseña">@error('password') <div class="invalid-feedback">Contraseña es requerido</div> @enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="password_confirmation">Repetir contraseña</label>
                            <input wire:model="password_confirmation" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" placeholder="Repetir contraseña">@error('password_confirmation') <div class="invalid-feedback">Contraseña es requerido</div> @enderror
                        </div>
                    </div>
                    <div class="col-12 text-center mt-2 pt-50">
                        <button type="button" wire:click.prevent="cancel()" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" wire:click.prevent="saveChangePassword()" class="btn btn-primary close-model">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div wire:ignore.self class="modal fade" id="createBonusDataModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button" wire:click.prevent="cancel()" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pb-5 px-sm-5 pt-50">
                <div class="text-center mb-2">
                    <h1 class="mb-1">Enviar Correo Bonificado</h1>
                </div>
                <form id="sendEmailForm" class="row gy-1 pt-75" onsubmit="return false">
                    <div class="col-12">
                        <label class="form-label" for="send_form">Correo</label>
                        <input wire:model="send_form" type="email" class="form-control" id="send_form" placeholder="Correo">@error('send_form') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-12 text-center mt-2 pt-50">
                        <button type="button" class="btn btn-secondary close-btn" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" wire:click.prevent="sendBonusEmail()" class="btn btn-primary close-modal">Enviar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

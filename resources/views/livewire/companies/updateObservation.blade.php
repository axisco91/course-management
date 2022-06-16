<!-- Modal -->
<div wire:ignore.self class="modal fade" id="updateObservation" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
       <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateModalLabel">Editar Observacion</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span wire:click.prevent="cancel()" aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
					<input type="hidden" wire:model="selected_id">
                    <input wire:model="company_id" type="text" hidden class="form-control" id="company_id">
                    <div class="form-group">
                        <label class="form-label" for="observation">Observación</label>
                        <textarea wire:model="observation" class="form-control" id="observation" placeholder="Observación"></textarea>@error('observation') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </form>
            </div>
              <div class="col-12 text-center mt-2 pt-50">
                <button type="button" wire:click.prevent="cancel()" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" wire:click.prevent="updateObservation()" class="btn btn-primary" data-bs-dismiss="modal">Guardar</button>
            </div>
       </div>
    </div>
</div>

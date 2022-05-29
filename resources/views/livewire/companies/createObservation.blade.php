<!-- Modal -->
<div wire:ignore.self class="modal fade" id="createObservationModal" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="createDataModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createDataModalLabel">Crear Obseervación</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <input type="hidden" wire:model="selected_id">
                    <div class="form-group">
                        <label class="form-label" for="observation"></label>
                        <textarea wire:model="observation" class="form-control" id="observation" placeholder="Observation"></textarea>@error('observation') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </form>
            </div>
              <div class="col-12 text-center mt-2 pt-50">
                <button type="button" class="btn btn-secondary close-btn" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" wire:click.prevent="createObservation()" class="btn btn-primary" data-bs-dismiss="modal">Guardar</button>
            </div>
        </div>
    </div>
</div>
<?php

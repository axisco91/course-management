<!-- Modal -->
<div wire:ignore.self class="modal fade" id="createDataModal" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="createDataModalLabel" aria-hidden="true">
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
                    @if (isset($company_id))
                        <input type="hidden" wire:model="selected_id">
                    @else
                        <div class="form-group">
                            <label for="company_id"></label>
                            <input wire:model="company_id" type="text" class="form-control" id="company_id" placeholder="Company Id">@error('company_id') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                    @endif
                    <div class="form-group">
                        <label for="observation"></label>
                        <textarea wire:model="observation" class="form-control" id="observation" placeholder="Observation"></textarea>@error('observation') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-btn" data-bs-dismiss="modal">Close</button>
                <button type="button" wire:click.prevent="store()" class="btn btn-primary close-modal">Save</button>
            </div>
        </div>
    </div>
</div>

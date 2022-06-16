<!-- Modal -->
<div wire:ignore.self class="modal fade" id="createDataModal" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="createDataModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createDataModalLabel">Create New Areas Teacher Area</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
           <div class="modal-body">
				<form>
            <div class="form-group">
                <label class="form-label" for="teacher_id"></label>
                <input wire:model="teacher_id" type="text" class="form-control" id="teacher_id" placeholder="Teacher Id">@error('teacher_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label class="form-label" for="teacher_area_id"></label>
                <input wire:model="teacher_area_id" type="text" class="form-control" id="teacher_area_id" placeholder="Teacher Area Id">@error('teacher_area_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

                </form>
            </div>
              <div class="col-12 text-center mt-2 pt-50">
                <button type="button" class="btn btn-secondary close-btn" data-bs-dismiss="modal">Close</button>
                <button type="button" wire:click.prevent="store()" class="btn btn-primary close-modal">Save</button>
            </div>
        </div>
    </div>
</div>

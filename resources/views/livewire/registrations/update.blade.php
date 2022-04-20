<!-- Modal -->
<div wire:ignore.self class="modal fade" id="updateModal" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
       <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateModalLabel">Update Registration</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span wire:click.prevent="cancel()" aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
					<input type="hidden" wire:model="selected_id">
            <div class="form-group">
                <label for="course_id"></label>
                <input wire:model="course_id" type="text" class="form-control" id="course_id" placeholder="Course Id">@error('course_id') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="company_id"></label>
                <input wire:model="company_id" type="text" class="form-control" id="company_id" placeholder="Company Id">@error('company_id') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="student_id"></label>
                <input wire:model="student_id" type="text" class="form-control" id="student_id" placeholder="Student Id">@error('student_id') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="tracing_id"></label>
                <input wire:model="tracing_id" type="text" class="form-control" id="tracing_id" placeholder="Tracing Id">@error('tracing_id') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="chore_id"></label>
                <input wire:model="chore_id" type="text" class="form-control" id="chore_id" placeholder="Chore Id">@error('chore_id') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="price"></label>
                <input wire:model="price" type="text" class="form-control" id="price" placeholder="Price">@error('price') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click.prevent="cancel()" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" wire:click.prevent="update()" class="btn btn-primary" data-bs-dismiss="modal">Save</button>
            </div>
       </div>
    </div>
</div>

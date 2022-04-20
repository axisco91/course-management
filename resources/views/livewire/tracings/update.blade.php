<!-- Modal -->
<div wire:ignore.self class="modal fade" id="updateModal" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
       <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateModalLabel">Update Tracing</h5>
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
                <label for="performed_activities"></label>
                <input wire:model="performed_activities" type="text" class="form-control" id="performed_activities" placeholder="Performed Activities">@error('performed_activities') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="performed_hours"></label>
                <input wire:model="performed_hours" type="text" class="form-control" id="performed_hours" placeholder="Performed Hours">@error('performed_hours') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="performed_units"></label>
                <input wire:model="performed_units" type="text" class="form-control" id="performed_units" placeholder="Performed Units">@error('performed_units') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="follow_up_date"></label>
                <input wire:model="follow_up_date" type="text" class="form-control" id="follow_up_date" placeholder="Follow Up Date">@error('follow_up_date') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="final_test"></label>
                <input wire:model="final_test" type="text" class="form-control" id="final_test" placeholder="Final Test">@error('final_test') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="questionnaire"></label>
                <input wire:model="questionnaire" type="text" class="form-control" id="questionnaire" placeholder="Questionnaire">@error('questionnaire') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="welcome_message"></label>
                <input wire:model="welcome_message" type="text" class="form-control" id="welcome_message" placeholder="Welcome Message">@error('welcome_message') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="quarter_message"></label>
                <input wire:model="quarter_message" type="text" class="form-control" id="quarter_message" placeholder="Quarter Message">@error('quarter_message') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="half_message"></label>
                <input wire:model="half_message" type="text" class="form-control" id="half_message" placeholder="Half Message">@error('half_message') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="three_quarters_message"></label>
                <input wire:model="three_quarters_message" type="text" class="form-control" id="three_quarters_message" placeholder="Three Quarters Message">@error('three_quarters_message') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="final_message"></label>
                <input wire:model="final_message" type="text" class="form-control" id="final_message" placeholder="Final Message">@error('final_message') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="observation"></label>
                <input wire:model="observation" type="text" class="form-control" id="observation" placeholder="Observation">@error('observation') <span class="error text-danger">{{ $message }}</span> @enderror
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

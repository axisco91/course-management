<!-- Modal -->
<div wire:ignore.self class="modal fade" id="updateModal" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
       <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateModalLabel">Update Chore</h5>
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
                <label for="membership_tab_status"></label>
                <input wire:model="membership_tab_status" type="text" class="form-control" id="membership_tab_status" placeholder="Membership Tab Status">@error('membership_tab_status') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="membership_tab_date"></label>
                <input wire:model="membership_tab_date" type="text" class="form-control" id="membership_tab_date" placeholder="Membership Tab Date">@error('membership_tab_date') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="economic_proposal_status"></label>
                <input wire:model="economic_proposal_status" type="text" class="form-control" id="economic_proposal_status" placeholder="Economic Proposal Status">@error('economic_proposal_status') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="economic_proposal_date"></label>
                <input wire:model="economic_proposal_date" type="text" class="form-control" id="economic_proposal_date" placeholder="Economic Proposal Date">@error('economic_proposal_date') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="student_tab_status"></label>
                <input wire:model="student_tab_status" type="text" class="form-control" id="student_tab_status" placeholder="Student Tab Status">@error('student_tab_status') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="student_tab_date"></label>
                <input wire:model="student_tab_date" type="text" class="form-control" id="student_tab_date" placeholder="Student Tab Date">@error('student_tab_date') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="welcome_guid_status"></label>
                <input wire:model="welcome_guid_status" type="text" class="form-control" id="welcome_guid_status" placeholder="Welcome Guid Status">@error('welcome_guid_status') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="welcome_guid_date"></label>
                <input wire:model="welcome_guid_date" type="text" class="form-control" id="welcome_guid_date" placeholder="Welcome Guid Date">@error('welcome_guid_date') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="registration_status"></label>
                <input wire:model="registration_status" type="text" class="form-control" id="registration_status" placeholder="Registration Status">@error('registration_status') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="registration_status_date"></label>
                <input wire:model="registration_status_date" type="text" class="form-control" id="registration_status_date" placeholder="Registration Status Date">@error('registration_status_date') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="diploma_status"></label>
                <input wire:model="diploma_status" type="text" class="form-control" id="diploma_status" placeholder="Diploma Status">@error('diploma_status') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="diploma_status_date"></label>
                <input wire:model="diploma_status_date" type="text" class="form-control" id="diploma_status_date" placeholder="Diploma Status Date">@error('diploma_status_date') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="start_communication_status"></label>
                <input wire:model="start_communication_status" type="text" class="form-control" id="start_communication_status" placeholder="Start Communication Status">@error('start_communication_status') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="start_communication_date"></label>
                <input wire:model="start_communication_date" type="text" class="form-control" id="start_communication_date" placeholder="Start Communication Date">@error('start_communication_date') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="close_communication_status"></label>
                <input wire:model="close_communication_status" type="text" class="form-control" id="close_communication_status" placeholder="Close Communication Status">@error('close_communication_status') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="close_communication_date"></label>
                <input wire:model="close_communication_date" type="text" class="form-control" id="close_communication_date" placeholder="Close Communication Date">@error('close_communication_date') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="invoiced_status"></label>
                <input wire:model="invoiced_status" type="text" class="form-control" id="invoiced_status" placeholder="Invoiced Status">@error('invoiced_status') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="invoiced_date"></label>
                <input wire:model="invoiced_date" type="text" class="form-control" id="invoiced_date" placeholder="Invoiced Date">@error('invoiced_date') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="bonus_sent_status"></label>
                <input wire:model="bonus_sent_status" type="text" class="form-control" id="bonus_sent_status" placeholder="Bonus Sent Status">@error('bonus_sent_status') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="bonus_sent_date"></label>
                <input wire:model="bonus_sent_date" type="text" class="form-control" id="bonus_sent_date" placeholder="Bonus Sent Date">@error('bonus_sent_date') <span class="error text-danger">{{ $message }}</span> @enderror
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

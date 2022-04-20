<!-- Modal -->
<div wire:ignore.self class="modal fade" id="updateModal" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
       <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateModalLabel">Update Bonus</h5>
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
                <label for="course_status_id"></label>
                <input wire:model="course_status_id" type="text" class="form-control" id="course_status_id" placeholder="Course Status Id">@error('course_status_id') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="number_students"></label>
                <input wire:model="number_students" type="text" class="form-control" id="number_students" placeholder="Number Students">@error('number_students') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="billing"></label>
                <input wire:model="billing" type="text" class="form-control" id="billing" placeholder="Billing">@error('billing') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="bonus"></label>
                <input wire:model="bonus" type="text" class="form-control" id="bonus" placeholder="Bonus">@error('bonus') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="total_training_activity"></label>
                <input wire:model="total_training_activity" type="text" class="form-control" id="total_training_activity" placeholder="Total Training Activity">@error('total_training_activity') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="organization_expenses"></label>
                <input wire:model="organization_expenses" type="text" class="form-control" id="organization_expenses" placeholder="Organization Expenses">@error('organization_expenses') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="only_organizing_entity"></label>
                <input wire:model="only_organizing_entity" type="text" class="form-control" id="only_organizing_entity" placeholder="Only Organizing Entity">@error('only_organizing_entity') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="average_template"></label>
                <input wire:model="average_template" type="text" class="form-control" id="average_template" placeholder="Average Template">@error('average_template') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="salary_cost"></label>
                <input wire:model="salary_cost" type="text" class="form-control" id="salary_cost" placeholder="Salary Cost">@error('salary_cost') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="payment_id"></label>
                <input wire:model="payment_id" type="text" class="form-control" id="payment_id" placeholder="Payment Id">@error('payment_id') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="start_communication_date"></label>
                <input wire:model="start_communication_date" type="text" class="form-control" id="start_communication_date" placeholder="Start Communication Date">@error('start_communication_date') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="close_communication_date"></label>
                <input wire:model="close_communication_date" type="text" class="form-control" id="close_communication_date" placeholder="Close Communication Date">@error('close_communication_date') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="invoiced"></label>
                <input wire:model="invoiced" type="text" class="form-control" id="invoiced" placeholder="Invoiced">@error('invoiced') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="invoice_number"></label>
                <input wire:model="invoice_number" type="text" class="form-control" id="invoice_number" placeholder="Invoice Number">@error('invoice_number') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="invoice_date"></label>
                <input wire:model="invoice_date" type="text" class="form-control" id="invoice_date" placeholder="Invoice Date">@error('invoice_date') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="collection_date"></label>
                <input wire:model="collection_date" type="text" class="form-control" id="collection_date" placeholder="Collection Date">@error('collection_date') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="status_bonus"></label>
                <input wire:model="status_bonus" type="text" class="form-control" id="status_bonus" placeholder="Status Bonus">@error('status_bonus') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="date"></label>
                <input wire:model="date" type="text" class="form-control" id="date" placeholder="Date">@error('date') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="company_bonus"></label>
                <input wire:model="company_bonus" type="text" class="form-control" id="company_bonus" placeholder="Company Bonus">@error('company_bonus') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="observations"></label>
                <input wire:model="observations" type="text" class="form-control" id="observations" placeholder="Observations">@error('observations') <span class="error text-danger">{{ $message }}</span> @enderror
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

<!-- Modal -->
<div wire:ignore.self class="modal fade" id="createDataModal" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="createDataModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createDataModalLabel">Create New Bonus</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
           <div class="modal-body">
				<form>
            <div class="form-group">
                <label class="form-label" for="course_id"></label>
                <input wire:model="course_id" type="text" class="form-control" id="course_id" placeholder="Course Id">@error('course_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label class="form-label" for="company_id"></label>
                <input wire:model="company_id" type="text" class="form-control" id="company_id" placeholder="Company Id">@error('company_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label class="form-label" for="course_status_id"></label>
                <input wire:model="course_status_id" type="text" class="form-control" id="course_status_id" placeholder="Course Status Id">@error('course_status_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label class="form-label" for="number_students"></label>
                <input wire:model="number_students" type="text" class="form-control" id="number_students" placeholder="Number Students">@error('number_students') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label class="form-label" for="billing"></label>
                <input wire:model="billing" type="text" class="form-control" id="billing" placeholder="Billing">@error('billing') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label class="form-label" for="bonus"></label>
                <input wire:model="bonus" type="text" class="form-control" id="bonus" placeholder="Bonus">@error('bonus') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label class="form-label" for="total_training_activity"></label>
                <input wire:model="total_training_activity" type="text" class="form-control" id="total_training_activity" placeholder="Total Training Activity">@error('total_training_activity') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label class="form-label" for="organization_expenses"></label>
                <input wire:model="organization_expenses" type="text" class="form-control" id="organization_expenses" placeholder="Organization Expenses">@error('organization_expenses') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label class="form-label" for="only_organizing_entity"></label>
                <input wire:model="only_organizing_entity" type="text" class="form-control" id="only_organizing_entity" placeholder="Only Organizing Entity">@error('only_organizing_entity') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label class="form-label" for="average_template"></label>
                <input wire:model="average_template" type="text" class="form-control" id="average_template" placeholder="Average Template">@error('average_template') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label class="form-label" for="salary_cost"></label>
                <input wire:model="salary_cost" type="text" class="form-control" id="salary_cost" placeholder="Salary Cost">@error('salary_cost') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label class="form-label" for="payment_id"></label>
                <input wire:model="payment_id" type="text" class="form-control" id="payment_id" placeholder="Payment Id">@error('payment_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label class="form-label" for="start_communication_date"></label>
                <input wire:model="start_communication_date" type="text" class="form-control" id="start_communication_date" placeholder="Start Communication Date">@error('start_communication_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label class="form-label" for="close_communication_date"></label>
                <input wire:model="close_communication_date" type="text" class="form-control" id="close_communication_date" placeholder="Close Communication Date">@error('close_communication_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label class="form-label" for="invoiced"></label>
                <input wire:model="invoiced" type="text" class="form-control" id="invoiced" placeholder="Invoiced">@error('invoiced') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label class="form-label" for="invoice_number"></label>
                <input wire:model="invoice_number" type="text" class="form-control" id="invoice_number" placeholder="Invoice Number">@error('invoice_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label class="form-label" for="invoice_date"></label>
                <input wire:model="invoice_date" type="text" class="form-control" id="invoice_date" placeholder="Invoice Date">@error('invoice_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label class="form-label" for="collection_date"></label>
                <input wire:model="collection_date" type="text" class="form-control" id="collection_date" placeholder="Collection Date">@error('collection_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label class="form-label" for="status_bonus"></label>
                <input wire:model="status_bonus" type="text" class="form-control" id="status_bonus" placeholder="Status Bonus">@error('status_bonus') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label class="form-label" for="date"></label>
                <input wire:model="date" type="text" class="form-control" id="date" placeholder="Date">@error('date') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label class="form-label" for="company_bonus"></label>
                <input wire:model="company_bonus" type="text" class="form-control" id="company_bonus" placeholder="Company Bonus">@error('company_bonus') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label class="form-label" for="observations"></label>
                <input wire:model="observations" type="text" class="form-control" id="observations" placeholder="Observations">@error('observations') <div class="invalid-feedback">{{ $message }}</div> @enderror
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

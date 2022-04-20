<!-- Modal -->
<div wire:ignore.self class="modal fade" id="updateModal" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
       <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateModalLabel">Actualizar Rentabilidad</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span wire:click.prevent="cancel()" aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
					<input type="hidden" wire:model="selected_id">
            <div class="form-group">
                <label for="course_id">Curso</label>
                <select wire:model.lazy="course_id" class="form-control select2" id="course_id" disabled>
                    <option value="-1">Seleccione un tipo</option>
                    @foreach($courses as $course)
                        <option value="{{$course['id']}}" {{$course['id'] == $course_id ? 'selected' : ''}}>{{$course['name']}}</option>
                    @endforeach
                </select>
                @error('course_id') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="company_id">Empresa</label>
                <select wire:model.lazy="company_id" class="form-control select2" id="company_id" disabled>
                    <option value="-1">Seleccione un tipo</option>
                    @foreach($companies as $company)
                        <option value="{{$company['id']}}" {{$company['id'] == $company_id ? 'selected' : ''}}>{{$company['name']}}</option>
                    @endforeach
                </select>
                @error('company_id') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="student_id">Alumno</label>
                <select wire:model.lazy="student_id" class="form-control select2" id="student_id" disabled>
                    @foreach($students as $student)
                        <option value="{{$student['id']}}" {{$student['id'] == $student_id ? 'selected' : ''}}>{{$student['name'].' '.$student['surname']}}</option>
                    @endforeach
                </select>
                @error('student_id') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="price">Precio</label>
                <input wire:model.lazy="price" type="text" class="form-control" id="price" placeholder="Precio" value="{{$price}}">@error('price') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="license">Licencia</label>
                <input wire:model.lazy="license" type="number" class="form-control" id="license" placeholder="Licencia" value="{{$license}}">@error('license') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="teacher">Docente</label>
                <input wire:model.lazy="teacher" type="text" class="form-control" id="teacher" placeholder="Docente" value="{{$teacher}}">@error('teacher') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="management">Gestión</label>
                <input wire:model.lazy="management" type="text" class="form-control" id="management" placeholder="Gestión" value="{{$management}}">@error('management') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="nebrija_title">Titulo Nebrija</label>
                <input wire:model.lazy="nebrija_title" type="text" class="form-control" id="nebrija_title" placeholder="Titulo Nebrija" value="{{$nebrija_title}}">@error('nebrija_title') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="discount">Descuento</label>
                <input wire:model.lazy="discount" type="text" class="form-control" id="discount" placeholder="Descuento" value="{{$discount}}">@error('discount') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="collaborator_commission">Comisión Colaborador</label>
                <input wire:model.lazy="collaborator_commission" type="text" class="form-control" id="collaborator_commission" placeholder="Comisión Colaborador" value="{{$collaborator_commission}}">@error('collaborator_commission') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="advisor_commission">Comisión Asesoria</label>
                <input wire:model.lazy="advisor_commission" type="text" class="form-control" id="advisor_commission" placeholder="Comision Asesoria" value="{{$advisor_commission}}">@error('advisor_commission') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="total">Total</label>
                <input wire:model.lazy="total" type="text" class="form-control" id="total" value="{{$total}}" disabled>@error('total') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="benefits">Beneficios</label>
                <input wire:model.lazy="benefits" type="text" class="form-control" id="benefits" disabled {{$benefits}}>@error('benefits') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="observations">Observaciones</label>
                <textarea wire:model.lazy="observations" class="form-control" id="observations">{{$observations}}</textarea>
                @error('observations') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click.prevent="cancel()" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" wire:click.prevent="update()" class="btn btn-primary" data-dismiss="modal">Save</button>
            </div>
       </div>
    </div>
</div>

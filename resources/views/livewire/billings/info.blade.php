 <div class="row">
     <div class="col-12 mb-1">
         <a class="btn btn-success right" href="{{url('/billings/edit/'.$this->selected_id)}}" target="_blank"><i class="fa-solid fa-pencil"></i></a>
     </div>
    <div class="col-md-4 col-12">
        <div class="mb-1">
            <label class="form-label" for="billing_number">Nº Factura</label>
            <input wire:model.lazy="billing_number" type="text" class="form-control" id="billing_number" placeholder="Nº Factura" disabled>@error('billing_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

    </div>
    <div class="col-md-4 col-12 mb-1">
        <label class="form-label" for="course_id">Curso</label> @if($this->course_id)<a href="{{url('/courses/view/'.$this->course_id)}}" target="_blank" class="view"><i class="fa-regular fa-eye"></i></a>
        @endif
        <div wire:ignore>
            <select wire:model.lazy="course_id" class="form-control select2" id="course_id" disabled>
                @foreach($courses as $course)
                    <option value="{{$course['id']}}">{{$course['name']}}</option>
                @endforeach
            </select>
        </div>
        @error('course_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
     <div class="col-md-4 col-12">
         <div class="mb-1">
             <label class="form-label" for="group">Grupo</label>
             <input wire:model.lazy="group" type="text" class="form-control" id="group" placeholder="Grupo" disabled>@error('bonus') <div class="invalid-feedback">{{ $message }}</div> @enderror
         </div>
     </div>
    <div class="col-md-4 col-12 mb-1">
        <label class="form-label" for="company_id">Empresa</label> @if($this->company_id)<a href="{{url('/companies/view/'.$this->company_id)}}" target="_blank" class="view"><i class="fa-regular fa-eye"></i></a>
        @endif
        <div wire:ignore>
            <select wire:model.lazy="company_id" class="form-control" id="company_id" disabled>
                <option value="-1">Seleccione una empresa</option>
                @foreach($companies as $company)
                    <option value="{{$company['id']}}">{{$company['name']}}</option>
                @endforeach
            </select>
        </div>
        @error('company_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4 col-12">
        <div class="mb-1">
            <label class="form-label" for="number_students">Numero Alumnos</label>
            <input wire:model.lazy="number_students" type="number" class="form-control" id="number_students" placeholder="Numero Alumnos" disabled>@error('number_students') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
    <div class="col-md-4 col-12">
        <div class="mb-1">
            <label class="form-label" for="billing">Facturacion</label>
            <input wire:model.lazy="billing" type="text" class="form-control" id="billing" placeholder="Facturación" disabled>@error('billing') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
     @if($is_bonus)
        <div class="col-md-4 col-12">
            <div class="mb-1">
                <label class="form-label" for="bonus">Bonificación</label>
                <input wire:model.lazy="bonus" type="text" class="form-control" id="bonus" placeholder="Bonificación" disabled>@error('bonus') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
     @endif
     @if($is_bonus)
        <div class="col-md-4 col-12">
            <div class="mb-1">
                <label class="form-label" for="total_training_activity">Total Actividad Formativa</label>
                <input wire:model.lazy="total_training_activity" type="text" class="form-control" id="total_training_activity" placeholder="Total Actividad Formativa" disabled>@error('total_training_activity') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
     @endif
     @if($is_bonus)
    <div class="col-md-4 col-12">
        <div class="mb-1">
            <label class="form-label" for="expenses">Gastos de Organización</label>
            <input wire:model.lazy="expenses" type="text" class="form-control" id="expenses" placeholder="Gastos de Organización" disabled>@error('expenses') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
     @endif
    <div class="col-md-4 col-12">
        <div class="mb-1">
            <label class="form-label" for="only_organizing_entity">Solamente Entidad Organizadora</label>
            <select wire:model.lazy="only_organizing_entity" class="form-control" id="only_organizing_entity" disabled>
                <option value="0">No</option>
                <option value="1">Si</option>
            </select>
        </div>
    </div>
     @if($is_bonus)
        <div class="col-md-4 col-12">
            <div class="mb-1">
                <label class="form-label" for="salary_costs">Costes Salariales</label>
                <input wire:model.lazy="salary_costs" type="text" class="form-control" id="salary_costs" placeholder="Costes Salariales" disabled>@error('salary_costs') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
     @endif
    <div class="col-md-4 col-12 mb-1">
        <label class="form-label" for="payment_id">Forma de Pago</label>
        <select wire:model.lazy="payment_id" class="form-control" id="payment_id" disabled>
            <option value="-1">Seleccióne una forma de pago</option>
            @foreach($payments as $payment)
                <option value="{{$payment['id']}}">{{$payment['name']}}</option>
            @endforeach
        </select>
        @error('payment_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
     @if($is_bonus)
        <div class="col-md-4 col-12">
            <div class="mb-1">
                <label class="form-label" for="communication_start_date">Fecha Comunicación Inicio</label>
                <input wire:model.lazy="communication_start_date" type="date" class="form-control" id="communication_start_date" disabled>@error('communication_start_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
     @endif
    <div class="col-md-4 col-12">
        <div class="mb-1">
            <label class="form-label" for="communication_end_date">Fecha Comunicación Cierre</label>
            <input wire:model.lazy="communication_end_date" type="date" class="form-control" id="communication_end_date"disabled>@error('communication_end_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
    <div class="col-md-4 col-12 mb-1">
        <label class="form-label" for="invoiced">Facturado</label>
        <select wire:model.lazy="invoiced" class="form-control" id="invoiced" disabled>
            <option value="0">No</option>
            <option value="1">Si</option>
        </select>
        @error('invoiced') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4 col-12">
        <div class="mb-1">
            <label class="form-label" for="billing_date">Fecha Factura</label>
            <input wire:model.lazy="billing_date" type="date" class="form-control" id="billing_date" disabled>@error('billing_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
    <div class="col-md-4 col-12">
        <div class="mb-1">
            <label class="form-label" for="collection_date">Fecha Cobro</label>
            <input wire:model.lazy="collection_date" type="date" class="form-control" id="collection_date" disabled>@error('collection_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
     @if($is_bonus)
        <div class="col-md-4 col-12 mb-1">
            <label class="form-label" for="bonus_status">Estado Bonificación</label>
            <select wire:model.lazy="bonus_status" class="form-control" id="bonus_status" disabled>
                <option value="0">Pendiente</option>
                <option value="1">Enviada</option>
            </select>
            @error('bonus_status') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
     @endif
     @if($is_bonus)
        <div class="col-md-4 col-12 mb-1">
            <label class="form-label" for="company_bonus">Bonificación empresa</label>
            <select wire:model.lazy="company_bonus" class="form-control" id="company_bonus" disabled>
                <option value="0">No</option>
                <option value="1">Si</option>
            </select>
            @error('company_bonus') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
     @endif
    <div class="col-12">
        <div class="mb-1">
            <label class="form-label" for="observation">Observación</label>
            <textarea wire:model="observation" class="form-control" id="observation" placeholder="Observación" disabled></textarea>@error('observation') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
 </div>

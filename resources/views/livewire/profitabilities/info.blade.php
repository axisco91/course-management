<div class="row">
    <div class="col-12 mb-1">
        <a class="btn btn-success right" href="{{url('/profitabilities/edit/'.$this->selected_id)}}" target="_blank"><i class="fa-solid fa-pencil"></i></a>
    </div>
    <div class="col-md-4 col-12 mb-1">
        <label class="form-label" for="course_id">Curso</label> @if($this->course_id)<a href="{{url('/courses/view/'.$this->course_id)}}" target="_blank" class="view"><i class="fa-regular fa-eye"></i></a>
        @endif
        <div wire:ignore>
            <select wire:model.lazy="course_id" class="form-select" id="course_id" disabled>
                <option value="-1">Seleccione un curso</option>
                @foreach($courses as $course)
                    <option value="{{$course['id']}}">{{$course['name']}}</option>
                @endforeach
            </select>
            @error('course_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
            @error('company_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
    @if($registrations)
        @if(count($registrations) == 1)
            <div class="col-md-4 col-12 mb-1">
                <label class="form-label" for="student_id">Alumno</label> @if($this->student_id)<a href="{{url('/students/view/'.$this->student_id)}}" target="_blank" class="view"><i class="fa-regular fa-eye"></i></a>@endif
                <div wire:ignore>
                    <select wire:model.lazy="student_id" class="form-control" id="student_id" disabled>
                        @foreach($students as $student)
                            <option value="{{$student['id']}}">{{$student['name'].' '.$student['surname']}}</option>
                        @endforeach
                    </select>
                    @error('student_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        @endif
    @endif
    <div class="col-md-4 col-12">
        <div class="mb-1">
            <label class="form-label" for="price">Precio</label>
            <input wire:model.lazy="price" type="text" class="form-control" id="price" placeholder="Precio" value="{{$price}}" disabled>@error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
    <div class="col-md-4 col-12">
        <div class="mb-1">
            <label class="form-label" for="license">Licencia</label>
            <input wire:model.lazy="license" type="number" class="form-control" id="license" placeholder="Licencia" value="{{$license}}" disabled>@error('license') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
    <div class="col-md-4 col-12">
        <div class="mb-1">
            <label class="form-label" for="teacher">Docente</label>
            <input wire:model.lazy="teacher" type="text" class="form-control" id="teacher" placeholder="Docente" value="{{$teacher}}" disabled>@error('teacher') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
    <div class="col-md-4 col-12">
        <div class="mb-1">
            <label class="form-label" for="management">Gestión</label>
            <input wire:model.lazy="management" type="text" class="form-control" id="management" placeholder="Gestión" value="{{$management}}" disabled>@error('management') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
    <div class="col-md-4 col-12">
        <div class="mb-1">
            <label class="form-label" for="nebrija_title">Titulo Nebrija</label>
            <input wire:model.lazy="nebrija_title" type="text" class="form-control" id="nebrija_title" placeholder="Titulo Nebrija" value="{{$nebrija_title}}" disabled>@error('nebrija_title') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
    <div class="col-md-4 col-12">
        <div class="mb-1">
            <label class="form-label" for="discount">Descuento</label>
            <input wire:model.lazy="discount" type="text" class="form-control" id="discount" placeholder="Descuento" value="{{$discount}}" disabled>@error('discount') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
    <div class="col-md-4 col-12">
        <div class="mb-1">
            <label class="form-label" for="collaborator_percentage">Porcentaje Colaborador</label>
            <input wire:model.lazy="collaborator_percentage" type="text" class="form-control" id="collaborator_percentage" placeholder="Porcentaje Colaborador" value="{{$collaborator_percentage}}" disabled>@error('collaborator_percentage') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
    <div class="col-md-4 col-12">
        <div class="mb-1">
            <label class="form-label" for="collaborator_commission">Comisión Colaborador</label>
            <input wire:model.lazy="collaborator_commission" type="text" class="form-control" id="collaborator_commission" placeholder="Comisión Colaborador" value="{{$collaborator_commission}}" disabled>@error('collaborator_commission') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
    <div class="col-md-4 col-12">
        <div class="mb-1">
            <label class="form-label" for="advisor_percentage">Porcentaje Asesoria</label>
            <input wire:model.lazy="advisor_percentage" type="text" class="form-control" id="advisor_percentage" placeholder="Porcentaje Asesoria" value="{{$advisor_percentage}}" disabled>@error('advisor_percentage') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
    <div class="col-md-4 col-12">
        <div class="mb-1">
            <label class="form-label" for="advisor_commission">Comisión Asesoria</label>
            <input wire:model.lazy="advisor_commission" type="text" class="form-control" id="advisor_commission" placeholder="Comision Asesoria" value="{{$advisor_commission}}" disabled>@error('advisor_commission') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
    <div class="col-md-4 col-12">
        <div class="mb-1">
            <label class="form-label" for="total">Total</label>
            <input wire:model.lazy="total" type="text" class="form-control" id="total" value="{{$total}}" disabled>@error('total') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
    <div class="col-md-4 col-12">
        <div class="mb-1">
            <label class="form-label" for="benefits">Beneficios</label>
            <input wire:model.lazy="benefits" type="text" class="form-control" id="benefits" disabled value="{{$benefits}}">@error('benefits') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
    <div class="col-12">
        <div class="mb-1">
            <label class="form-label" for="observations">Observaciones</label>
            <textarea wire:model.lazy="observations" class="form-control" id="observations" disabled>{{$observations}}</textarea>
            @error('observations') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
</div>
